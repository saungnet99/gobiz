<?php

namespace App\Http\Controllers\Payment;

use App\Plan;
use App\User;
use App\Coupon;

use Carbon\Carbon;
use App\BusinessCard;
use App\Transaction; 
use App\AppliedCoupon;
use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalController extends Controller
{
    protected $apiContext;

    public function __construct()
    {
        // Fetch PayPal configuration from database
        $paypalConfiguration = DB::table('config')->get();

        // Set up PayPal environment
        $clientId = $paypalConfiguration[4]->config_value;
        $clientSecret = $paypalConfiguration[5]->config_value;
        $mode = $paypalConfiguration[3]->config_value;

        if ($mode == "sandbox") {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $this->apiContext = new PayPalHttpClient($environment);
    }

    public function payWithPayPal(Request $request, $planId, $couponId)
    {
        if (Auth::check()) {
            $planDetails = Plan::where('plan_id', $planId)->where('status', 1)->first();
            $paypalConfiguration = DB::table('config')->get();
            $userData = Auth::user();

            if ($planDetails == null) {
                return view('errors.404');
            } else {
                // Check applied coupon
                $couponDetails = Coupon::where('coupon_id', $couponId)->first();

                // Applied tax in total
                $appliedTaxInTotal = 0;

                // Discount price
                $discountPrice = 0;

                // Applied coupon
                $appliedCoupon = null;

                // Check coupon type
                if ($couponDetails != null) {
                    if ($couponDetails->coupon_type == 'fixed') {
                        // Applied tax in total
                        $appliedTaxInTotal = ((float)($planDetails->plan_price) * (float)($paypalConfiguration[25]->config_value) / 100);

                        // Get discount in plan price
                        $discountPrice = $couponDetails->coupon_amount;

                        // Total
                        $amountToBePaid = ($planDetails->plan_price + $appliedTaxInTotal) - $discountPrice;

                        // Coupon is applied
                        $appliedCoupon = $couponDetails->coupon_code;
                    } else {
                        // Applied tax in total
                        $appliedTaxInTotal = ((float)($planDetails->plan_price) * (float)($paypalConfiguration[25]->config_value) / 100);

                        // Get discount in plan price
                        $discountPrice = $planDetails->plan_price * $couponDetails->coupon_amount / 100;

                        // Total
                        $amountToBePaid = ($planDetails->plan_price + $appliedTaxInTotal) - $discountPrice;

                        // Coupon is applied
                        $appliedCoupon = $couponDetails->coupon_code;
                    }
                } else {
                    // Applied tax in total
                    $appliedTaxInTotal = ((float)($planDetails->plan_price) * (float)($paypalConfiguration[25]->config_value) / 100);

                    // Total
                    $amountToBePaid = ($planDetails->plan_price + $appliedTaxInTotal);
                }

                // Construct PayPal order request
                $request = new OrdersCreateRequest();
                $request->prefer('return=representation');
                $request->body = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => $paypalConfiguration[1]->config_value,
                            'value' => $amountToBePaid,
                        ]
                    ]],
                    'application_context' => [
                        'cancel_url' => route('paypalPaymentStatus'),
                        'return_url' => route('paypalPaymentStatus'),
                    ]
                ];

                try {
                    // Create PayPal order
                    $response = $this->apiContext->execute($request);
                    foreach ($response->result->links as $link) {
                        if ($link->rel == 'approve') {
                            $redirectUrl = $link->href;
                            break;
                        }
                    }

                    // Store transaction details in database before redirecting to PayPal
                    $transaction = new Transaction();
                    $transaction->gobiz_transaction_id = uniqid();
                    $transaction->transaction_date = now();
                    $transaction->transaction_id = $response->result->id;
                    $transaction->user_id = Auth::id();
                    $transaction->plan_id = $planDetails->plan_id;
                    $transaction->desciption = $planDetails->plan_name . " Plan";
                    $transaction->payment_gateway_name = "PayPal";
                    $transaction->transaction_amount = $amountToBePaid;
                    $transaction->transaction_currency = $paypalConfiguration[1]->config_value;
                    $transaction->invoice_details = json_encode($this->prepareInvoiceDetails($paypalConfiguration, $userData, $amountToBePaid, $planDetails, $appliedCoupon, $discountPrice));
                    $transaction->payment_status = "PENDING";
                    $transaction->save();

                    // Coupon is not applied
                    if ($couponId != " ") {
                        // Save applied coupon
                        $appliedCoupon = new AppliedCoupon;
                        $appliedCoupon->applied_coupon_id = uniqid();
                        $appliedCoupon->transaction_id = $response->result->id;
                        $appliedCoupon->user_id = Auth::user()->id;
                        $appliedCoupon->coupon_id = $couponId;
                        $appliedCoupon->status = 0;
                        $appliedCoupon->save();
                    }

                    // Redirect to PayPal for payment
                    return Redirect::away($redirectUrl);
                } catch (\Exception $ex) {
                    if (config('app.debug')) {
                        return redirect()->route('user.plans')->with('failed', trans('Payment failed, Something went wrong!'));
                    } else {
                        return redirect()->route('user.plans')->with('failed', trans('Payment failed, Something went wrong!'));
                    }
                    return redirect()->route('user.plans');
                }
            }
        } else {
            return redirect()->route('login');
        }
    }


    public function paypalPaymentStatus(Request $request)
    {
        if (empty($request->PayerID) || empty($request->token)) {
            Session::put('error', 'Payment cancelled!');
            return redirect()->route('user.plans');
        }

        try {
            // Get the payment ID from the request
            $paymentId = $request->token;
            $orderId = $paymentId;
            $transactionDetails = Transaction::where('transaction_id', $paymentId)->where('status', 1)->first();
            $user_details = User::find(Auth::user()->id);
            $config = DB::table('config')->get();

            $request = new OrdersCaptureRequest($paymentId);
            $response = $this->apiContext->execute($request);

            if ($response->statusCode == 201) {
                $plan_data = Plan::where('plan_id', $transactionDetails->plan_id)->first();
                $term_days = $plan_data->validity;


                if ($user_details->plan_validity == "") {

                    // Add days
                    if ($term_days == "9999") {
                        $plan_validity = "2050-12-30 23:23:59";
                    } else {
                        $plan_validity = Carbon::now();
                        $plan_validity->addDays($term_days);
                    }

                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    Transaction::where('transaction_id', $orderId)->update([
                        'transaction_id' => $orderId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'payment_status' => 'SUCCESS',
                    ]);

                    User::where('user_id', Auth::user()->user_id)->update([
                        'plan_id' => $transactionDetails->plan_id,
                        'term' => $term_days,
                        'plan_validity' => $plan_validity,
                        'plan_activation_date' => now(),
                        'plan_details' => $plan_data
                    ]);

                    // Save applied coupon
                    AppliedCoupon::where('transaction_id', $orderId)->update([
                        'status' => 1
                    ]);

                    $encode = json_decode($transactionDetails['invoice_details'], true);
                    $details = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'gobiz_transaction_id' => $transactionDetails->gobiz_transaction_id,
                        'to_billing_name' => $encode['to_billing_name'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'subtotal' => $encode['subtotal'],
                        'tax_amount' => (float)($plan_data->plan_price) * (float)($config[25]->config_value) / 100,
                        'applied_coupon' => $encode['applied_coupon'],
                        'discounted_price' => $encode['discounted_price'],
                        'invoice_amount' => $encode['invoice_amount'],
                        'invoice_id' => $config[15]->config_value . $invoice_number,
                        'invoice_date' => $transactionDetails->created_at,
                        'description' => $transactionDetails->desciption,
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                    } catch (\Exception $e) {
                    }

                    return redirect()->route('user.plans')->with('success', trans('Plan activation success!'));
                } else {

                    $message = "";
                    if ($user_details->plan_id == $transactionDetails->plan_id) {

                        // Check if plan validity is expired or not.
                        $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $user_details->plan_validity);
                        $current_date = Carbon::now();
                        $remaining_days = $current_date->diffInDays($plan_validity, false);

                        // Check plan remaining days
                        if ($remaining_days > 0) {
                            // Add days
                            if ($term_days == "9999") {
                                $plan_validity = "2050-12-30 23:23:59";
                                $message = trans("Plan activated successfully!");
                            } else {
                                $plan_validity = Carbon::parse($user_details->plan_validity);
                                $plan_validity->addDays($term_days);
                                $message = trans("Plan activated successfully!");
                            }
                        } else {
                            // Add days
                            if ($term_days == "9999") {
                                $plan_validity = "2050-12-30 23:23:59";
                                $message = trans("Plan activated successfully!");
                            } else {
                                $plan_validity = Carbon::now();
                                $plan_validity->addDays($term_days);
                                $message = trans("Plan activated successfully!");
                            }
                        }

                        // Making all cards inactive, For Plan change
                        BusinessCard::where('user_id', Auth::user()->user_id)->update([
                            'card_status' => 'inactive',
                        ]);
                    } else {

                        // Making all cards inactive, For Plan change
                        BusinessCard::where('user_id', Auth::user()->user_id)->update([
                            'card_status' => 'inactive',
                        ]);

                        // Add days
                        if ($term_days == "9999") {
                            $plan_validity = "2050-12-30 23:23:59";
                            $message = trans("Plan activated successfully!");
                        } else {
                            $plan_validity = Carbon::now();
                            $plan_validity->addDays($term_days);
                            $message = trans("Plan activated successfully!");
                        }
                    }

                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    Transaction::where('transaction_id', $orderId)->update([
                        'transaction_id' => $orderId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'payment_status' => 'SUCCESS',
                    ]);

                    User::where('user_id', Auth::user()->user_id)->update([
                        'plan_id' => $transactionDetails->plan_id,
                        'term' => $term_days,
                        'plan_validity' => $plan_validity,
                        'plan_activation_date' => now(),
                        'plan_details' => $plan_data
                    ]);

                    // Save applied coupon
                    AppliedCoupon::where('transaction_id', $orderId)->update([
                        'status' => 1
                    ]);

                    $encode = json_decode($transactionDetails['invoice_details'], true);
                    $details = [
                        'from_billing_name' => $encode['from_billing_name'],
                        'from_billing_email' => $encode['from_billing_email'],
                        'from_billing_address' => $encode['from_billing_address'],
                        'from_billing_city' => $encode['from_billing_city'],
                        'from_billing_state' => $encode['from_billing_state'],
                        'from_billing_country' => $encode['from_billing_country'],
                        'from_billing_zipcode' => $encode['from_billing_zipcode'],
                        'gobiz_transaction_id' => $transactionDetails->gobiz_transaction_id,
                        'to_billing_name' => $encode['to_billing_name'],
                        'invoice_currency' => $transactionDetails->transaction_currency,
                        'subtotal' => $encode['subtotal'],
                        'tax_amount' => (float)($plan_data->plan_price) * (float)($config[25]->config_value) / 100,
                        'applied_coupon' => $encode['applied_coupon'],
                        'discounted_price' => $encode['discounted_price'],
                        'invoice_amount' => $encode['invoice_amount'],
                        'invoice_id' => $config[15]->config_value . $invoice_number,
                        'invoice_date' => $transactionDetails->created_at,
                        'description' => $transactionDetails->desciption,
                        'email_heading' => $config[27]->config_value,
                        'email_footer' => $config[28]->config_value,
                    ];

                    try {
                        Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                    } catch (\Exception $e) {
                    }

                    return redirect()->route('user.plans')->with('success', trans($message));
                }
            } else {
                Transaction::where('transaction_id', $orderId)->update([
                    'transaction_id' => $orderId,
                    'payment_status' => 'FAILED',
                ]);

                return redirect()->route('user.plans')->with('failed', trans('Payment cancelled!'));
            }
        } catch (HttpException $e) { // Corrected class name
            // Handle the HTTP exception
            // Log the error or display an error message
            // Example: Log::error('PayPal HTTP Exception: ' . $e->getMessage());

            // Set an error message for the user
            Session::flash('error', 'An error occurred while communicating with PayPal. Please try again later.');

            // Redirect back to the user plans page or any other appropriate page
            return redirect()->route('user.plans');
        }
    }

    private function prepareInvoiceDetails($config, $userData, $amountToBePaid, $planDetails, $appliedCoupon, $discountPrice)
    {
        // Prepare invoice details
        $invoiceDetails = [
            'from_billing_name' => $config[16]->config_value,
            'from_billing_address' => $config[19]->config_value,
            'from_billing_city' => $config[20]->config_value,
            'from_billing_state' => $config[21]->config_value,
            'from_billing_zipcode' => $config[22]->config_value,
            'from_billing_country' => $config[23]->config_value,
            'from_vat_number' => $config[26]->config_value,
            'from_billing_phone' => $config[18]->config_value,
            'from_billing_email' => $config[17]->config_value,
            'to_billing_name' => $userData->billing_name,
            'to_billing_address' => $userData->billing_address,
            'to_billing_city' => $userData->billing_city,
            'to_billing_state' => $userData->billing_state,
            'to_billing_zipcode' => $userData->billing_zipcode,
            'to_billing_country' => $userData->billing_country,
            'to_billing_phone' => $userData->billing_phone,
            'to_billing_email' => $userData->billing_email,
            'to_vat_number' => $userData->vat_number,
            'tax_name' => $config[24]->config_value,
            'tax_type' => $config[14]->config_value,
            'tax_value' => $config[25]->config_value,
            'applied_coupon' => $appliedCoupon,
            'discounted_price' => $discountPrice,
            'invoice_amount' => $amountToBePaid,
            'subtotal' => $planDetails->plan_price,
            'tax_amount' => (float)($planDetails->plan_price) * (float)($config[25]->config_value) / 100
        ];

        return $invoiceDetails;
    }
}
