<?php

namespace App\Http\Controllers\Payment;

use App\Plan;
use App\User;
use App\Coupon;
use Carbon\Carbon;
use App\Transaction;
use App\BusinessCard;
use App\AppliedCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class MercadoPagoController extends Controller
{
    // Mercado Pago
    public function prepareMercadoPago(Request $request, $planId, $couponId)
    {
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

            // Validate Mercado Pago access token
            if ($config[48]->config_value == null || $config[48]->config_value == "YOUR_MERCADO_PAGO_ACCESS_TOKEN") {
                return redirect()->route('user.plans')->with('failed', trans('Something went wrong!'));
            } else {
                $mercado_pago_access_token = $config[48]->config_value;
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

                // Set Mercado Pago API endpoint for creating payments
                $url = 'https://api.mercadopago.com/checkout/preferences';

                // Prepare the payload
                $payload = [
                    'items' => [
                        [
                            'title' => $plan_details->name,
                            'quantity' => 1,
                            'unit_price' => $amountToBePaidPaise, // Set the plan price
                            'currency_id' => $config[1]->config_value
                        ]
                    ],
                    'back_urls' => [
                        'success' => route('mercadopago.payment.status'),
                        'failure' => route('mercadopago.payment.failure'),
                        'pending' => route('mercadopago.payment.pending')
                    ],
                    'auto_return' => 'approved',
                ];

                // Make the request to Mercado Pago's API
                $response = Http::withToken($mercado_pago_access_token)->post($url, $payload);

                // Check for success and retrieve the preference ID
                if ($response->successful()) {
                    $preferenceId = $response['id']; // This is the Mercado Pago transaction ID

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
                    $transaction->transaction_id = $preferenceId;
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
                        $appliedCoupon->transaction_id = $preferenceId;
                        $appliedCoupon->user_id = Auth::user()->id;
                        $appliedCoupon->coupon_id = $couponId;
                        $appliedCoupon->status = 0;
                        $appliedCoupon->save();
                    }

                    // Redirect to Mercado Pago payment page
                    return redirect($response['init_point']);
                } else {
                    // Handle API error
                    return redirect()->route('user.plans')->with('failed', trans('Unable to create payment'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    // Success
    public function mercadoPagoPaymentStatus(Request $request)
    {
        // Retrieve necessary inputs from the query parameters
        $preferenceId = $request->query('preference_id');
        $merchant_order_id = $request->query('merchant_order_id'); // This is the Mercado Pago preference_id

        if ($preferenceId == null || $merchant_order_id == null) {
            return redirect()->route('user.plans')->with('failed', trans('Transaction not found or already processed.'));
        } else {
            // Config
            $config = DB::table('config')->get();

            // Get transaction details based on the preference_id
            $transaction_details = Transaction::where('transaction_id', $preferenceId)->first();

            if (!$transaction_details) {
                return redirect()->route('user.plans')->with('failed', trans('Transaction not found or already processed.'));
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
                $transaction_details->update([
                    'transaction_id' => $preferenceId,
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                // Update user details
                $user_details->update([
                    'plan_id' => $transaction_details->plan_id,
                    'term' => $term_days,
                    'plan_validity' => $plan_validity,
                    'plan_activation_date' => now(),
                    'plan_details' => $plan_data
                ]);

                // Save applied coupon
                AppliedCoupon::where('transaction_id', $preferenceId)->update([
                    'status' => 1
                ]);

                $message = 'Plan activation success!';
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
                $transaction_details->update([
                    'transaction_id' => $preferenceId,
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                // Update user details
                $user_details->update([
                    'plan_id' => $transaction_details->plan_id,
                    'term' => $term_days,
                    'plan_validity' => $plan_validity,
                    'plan_activation_date' => now(),
                    'plan_details' => $plan_data
                ]);

                // Save applied coupon
                AppliedCoupon::where('transaction_id', $preferenceId)->update([
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
                'gobiz_transaction_id' => $preferenceId,
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
                // Handle email sending failure if needed
            }

            // Redirect to the user's plans page with success message
            return redirect()->route('user.plans')->with('success', $message);
        }
    }

    // Failure
    public function mercadoPagoPaymentFailure(Request $request)
    {
        // Get the preference_id from the request
        $preferenceId = $request->query('preference_id');

        // Update the transaction status to FAILED
        Transaction::where('transaction_id', $preferenceId)->update(['payment_status' => 'FAILED']);

        return redirect()->route('user.plans')->with('failed', trans("Payment failed"));
    }

    // Pending
    public function mercadoPagoPaymentPending(Request $request)
    {
        // Get the preference_id from the request
        $preferenceId = $request->query('preference_id');

        // Update the transaction status to PENDING
        Transaction::where('transaction_id', $preferenceId)->update(['payment_status' => 'PENDING']);

        return redirect()->route('user.plans')->with('failed', trans("Payment is pending"));
    }
}
