<?php

namespace App\Http\Controllers\Payment;

use App\Plan;
use App\User;
use App\Coupon;
use Carbon\Carbon;
use App\Transaction;
use App\BusinessCard;
use App\AppliedCoupon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ToyyibpayController extends Controller
{
    protected $apiKey;
    protected $categoryCode;
    protected $baseUrl;

    public function __construct()
    {
        // Get API key and category code from config table
        $config = DB::table('config')->get();

        // Set API key and category code
        $this->apiKey = $config[49]->config_value;
        $this->categoryCode = $config[50]->config_value;
        // $this->baseUrl = "https://dev.toyyibpay.com/"; // Development URL
        $this->baseUrl = "https://toyyibpay.com/"; // Production URL
    }

    public function prepareToyyibpay(Request $request, $planId, $couponId)
    {
        // Check if user is logged in
        if (Auth::user()) {
            // Queries
            $config = DB::table('config')->get();

            // Get user details
            $userData = User::where('id', Auth::user()->id)->first();

            // Get plan details
            $plan_details = Plan::where('plan_id', $planId)->where('status', 1)->first();
            if (!$plan_details) {
                return redirect()->route('user.plans')->with('failed', trans('Invalid plan!'));
            }

            // Check plan details
            if ($plan_details == null) {
                return view('errors.404');
            } else {
                $gobiz_transaction_id = uniqid();

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
                        $appliedTaxInTotal = ((float)($plan_details->plan_price) * (float)($config[25]->config_value) / 100);

                        // Get discount in plan price
                        $discountPrice = $couponDetails->coupon_amount;

                        // Total
                        $amountToBePaid = ($plan_details->plan_price + $appliedTaxInTotal) - $discountPrice;

                        // Coupon is applied
                        $appliedCoupon = $couponDetails->coupon_code;
                    } else {
                        // Applied tax in total
                        $appliedTaxInTotal = ((float)($plan_details->plan_price) * (float)($config[25]->config_value) / 100);

                        // Get discount in plan price
                        $discountPrice = $plan_details->plan_price * $couponDetails->coupon_amount / 100;

                        // Total
                        $amountToBePaid = ($plan_details->plan_price + $appliedTaxInTotal) - $discountPrice;

                        // Coupon is applied
                        $appliedCoupon = $couponDetails->coupon_code;
                    }
                } else {
                    // Applied tax in total
                    $appliedTaxInTotal = ((float)($plan_details->plan_price) * (float)($config[25]->config_value) / 100);

                    // Total
                    $amountToBePaid = ($plan_details->plan_price + $appliedTaxInTotal);
                }

                $amountToBePaidPaise = $amountToBePaid * 100;

                $client = new Client(['base_uri' => $this->baseUrl]);

                // Prepare bill details
                $billDetails = [
                    'billName' => 'Payment for #' . $gobiz_transaction_id,
                    'billDescription' => 'Plan Payment',
                    'billAmount' => $amountToBePaidPaise, // Amount in cents
                    'billReturnUrl' => route('toyyibpay.payment.success'),
                    'billCallbackUrl' => route('toyyibpay.payment.status'),
                    'billExternalReferenceNo' => $gobiz_transaction_id,
                    'userSecretKey' => $this->apiKey,
                    'categoryCode' => $this->categoryCode,
                    'billPriceSetting' => 1,
                    'billPayorInfo' => 1,
                    'billTo' => Auth::user()->name,
                    'billEmail' => Auth::user()->email,
                    'billPhone' => Auth::user()->billing_phone == null ? '9876543210' : Auth::user()->billing_phone,
                ];

                // Create a bill with ToyyibPay
                $response = $client->post('index.php/api/createBill', [
                    'form_params' => $billDetails,
                ]);

                $responseBody = json_decode($response->getBody(), true);

                if (isset($responseBody[0]['BillCode'])) {
                    $billCode = $responseBody[0]['BillCode'];

                    // Prepare invoice details
                    $invoice_details = [
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
                        'subtotal' => $plan_details->plan_price,
                        'tax_name' => $config[24]->config_value,
                        'tax_type' => $config[14]->config_value,
                        'tax_value' => $config[25]->config_value,
                        'tax_amount' => $appliedTaxInTotal,
                        'applied_coupon' => $appliedCoupon,
                        'discounted_price' => $discountPrice,
                        'invoice_amount' => $amountToBePaid,
                    ];

                    // Create a new transaction entry in the database
                    $transaction = new Transaction();
                    $transaction->gobiz_transaction_id = $gobiz_transaction_id;
                    $transaction->transaction_date = now();
                    $transaction->transaction_id = $billCode;
                    $transaction->user_id = Auth::user()->id;
                    $transaction->plan_id = $plan_details->plan_id;
                    $transaction->desciption = $plan_details->plan_name . " Plan";
                    $transaction->payment_gateway_name = "Phonepe";
                    $transaction->transaction_amount = $amountToBePaid;
                    $transaction->transaction_currency = $config[1]->config_value;
                    $transaction->invoice_details = json_encode($invoice_details);
                    $transaction->payment_status = "PENDING";
                    $transaction->save();

                    // Coupon is not applied
                    if ($couponId != " ") {
                        // Save applied coupon
                        $appliedCoupon = new AppliedCoupon;
                        $appliedCoupon->applied_coupon_id = uniqid();
                        $appliedCoupon->transaction_id = $billCode;
                        $appliedCoupon->user_id = Auth::user()->id;
                        $appliedCoupon->coupon_id = $couponId;
                        $appliedCoupon->status = 0;
                        $appliedCoupon->save();
                    }

                    return redirect()->to("https://dev.toyyibpay.com/{$billCode}");
                }

                return back()->with('failed', trans('Failed to initiate payment.'));
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function toyyibpayPaymentStatus(Request $request)
    {
        // Get the bill code from the request
        $statusId = $request['status_id'];
        $billCode = $request['billcode'];
        $transactionId = $request['transaction_id'];

        // Call the static function
        $updatedData = $this->toyyibpayPaymentSuccessStatic($statusId, $billCode, $transactionId);

        return redirect()->route('user.plans')->with($updatedData);
    }

    public function toyyibpayPaymentSuccess(Request $request)
    {
        // Get the bill code from the request
        $statusId = $request['status_id'];
        $billCode = $request['billcode'];
        $transactionId = $request['transaction_id'];

        // Call the static function
        $updatedData = $this->toyyibpayPaymentSuccessStatic($statusId, $billCode, $transactionId);

        return redirect()->route('user.plans')->with($updatedData);
    }

    // Static function call
    public function toyyibpayPaymentSuccessStatic($statusId, $billCode, $transactionId)
    {
        // Get the bill code from the request
        $statusId = $statusId;
        $billCode = $billCode;
        $transactionId = $transactionId;

        if ($billCode == null) {
            // Update the transaction status to PENDING
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'FAILED']);

            return [
                'failed' => trans('Transaction not found or already processed.'),
            ];
        } else {
            // Payment success
            if ($statusId == 1) {
                // Config
                $config = DB::table('config')->get();

                // Get transaction details based on the preference_id
                $transaction_details = Transaction::where('transaction_id', $billCode)->first();

                if (!$transaction_details) {
                    return [
                        'failed' => trans('Transaction not found or already processed.'),
                    ];
                }

                // Get user details
                $user_details = User::find(Auth::user()->id);

                // Get plan data
                $plan_data = Plan::where('plan_id', $transaction_details->plan_id)->first();
                $term_days = $plan_data->validity;

                if ($user_details->plan_validity == "") {
                    // Add days
                    if ($term_days == "9999") {
                        $plan_validity = "2050-12-30 23:23:59";
                    } else {
                        $plan_validity = Carbon::now();
                        $plan_validity->addDays($term_days);
                    }

                    // Generate invoice number
                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    // Update transaction details
                    Transaction::where('transaction_id', $transaction_details->transaction_id)->update([
                        'transaction_id' => $transactionId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'payment_status' => 'SUCCESS',
                    ]);

                    // Update user details
                    User::where('id', Auth::user()->id)->update([
                        'plan_id' => $transaction_details->plan_id,
                        'term' => $term_days,
                        'plan_validity' => $plan_validity,
                        'plan_activation_date' => now(),
                        'plan_details' => $plan_data
                    ]);

                    // Save applied coupon
                    AppliedCoupon::where('transaction_id', $billCode)->update([
                        'status' => 1
                    ]);

                    $message = trans('Plan activation success!');
                } else {
                    // Renew existing plan
                    $plan_validity = Carbon::createFromFormat('Y-m-d H:i:s', $user_details->plan_validity);
                    $current_date = Carbon::now();
                    $remaining_days = $current_date->diffInDays($plan_validity, false);

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

                        $message = 'Plan renewed successfully!';
                    } else {
                        // Add days
                        if ($term_days == "9999") {
                            $plan_validity = "2050-12-30 23:23:59";
                            $message = trans("Plan activated successfully!");
                        } else {
                            $plan_validity = Carbon::parse($user_details->plan_validity);
                            $plan_validity->addDays($term_days);
                            $message = trans("Plan activated successfully!");
                        }

                        $message = trans("Plan activated successfully!");
                    }

                    // Generate invoice number
                    $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                    $invoice_number = $invoice_count + 1;

                    // Update transaction details
                    Transaction::where('transaction_id', $transaction_details->transaction_id)->update([
                        'transaction_id' => $transactionId,
                        'invoice_prefix' => $config[15]->config_value,
                        'invoice_number' => $invoice_number,
                        'payment_status' => 'SUCCESS',
                    ]);

                    // Update user details
                    User::where('id', Auth::user()->id)->update([
                        'plan_id' => $transaction_details->plan_id,
                        'term' => $term_days,
                        'plan_validity' => $plan_validity,
                        'plan_activation_date' => now(),
                        'plan_details' => $plan_data
                    ]);

                    // Save applied coupon
                    AppliedCoupon::where('transaction_id', $billCode)->update([
                        'status' => 1
                    ]);
                }

                // Making all cards inactive, For Plan change
                BusinessCard::where('user_id', Auth::user()->user_id)->update([
                    'card_status' => 'inactive',
                ]);

                // Generate and send invoice details
                $encode = json_decode($transaction_details->invoice_details, true);

                $details = [
                    'from_billing_name' => $encode['from_billing_name'],
                    'from_billing_email' => $encode['from_billing_email'],
                    'from_billing_address' => $encode['from_billing_address'],
                    'from_billing_city' => $encode['from_billing_city'],
                    'from_billing_state' => $encode['from_billing_state'],
                    'from_billing_country' => $encode['from_billing_country'],
                    'from_billing_zipcode' => $encode['from_billing_zipcode'],
                    'gobiz_transaction_id' => $transactionId,
                    'to_billing_name' => $encode['to_billing_name'],
                    'to_vat_number' => $encode['to_vat_number'],
                    'invoice_currency' => $transaction_details->transaction_currency,
                    'subtotal' => $encode['subtotal'],
                    'tax_amount' => (float)($plan_data->plan_price) * (float)($config[25]->config_value) / 100,
                    'applied_coupon' => $encode['applied_coupon'],
                    'discounted_price' => $encode['discounted_price'],
                    'invoice_amount' => $encode['invoice_amount'],
                    'invoice_id' => $config[15]->config_value . $invoice_number,
                    'invoice_date' => $transaction_details->created_at,
                    'description' => $transaction_details->desciption,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                // Send invoice via email
                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                    
                }

                return [
                    'success' => trans($message),
                ];
            }
        }

        // Payment pending
        if($statusId == 2 || $statusId == 4) {
            // Update the transaction status to FAILED
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'PENDING']);

            return [
                'failed' => trans('Payment pending'),
            ];
        }

        // Payment failed
        if($statusId == 3) {
            // Update the transaction status to FAILED
            Transaction::where('transaction_id', $billCode)->update(['payment_status' => 'FAILED']);

            return [
                'failed' => trans('Payment failed'),
            ];
        }
    }
}
