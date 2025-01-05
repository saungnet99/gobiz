<?php

namespace App\Classes;

use App\Plan;
use App\User;
use Carbon\Carbon;
use App\Transaction;
use App\BusinessCard;
use App\AppliedCoupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UpgradePlan
{
    public function upgrade($transactionId, $res)
    {
        // Queries
        $config = DB::table('config')->get();
        
        $orderId = $transactionId;

        $transaction_details = Transaction::where('transaction_id', $orderId)->where('status', 1)->first();
        $user_details = User::find($transaction_details->user_id);

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

            $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
            $invoice_number = $invoice_count + 1;

            Transaction::where('transaction_id', $orderId)->update([
                'transaction_id' => $transactionId,
                'invoice_prefix' => $config[15]->config_value,
                'invoice_number' => $invoice_number,
                'payment_status' => 'SUCCESS',
            ]);

            User::where('user_id', $user_details->user_id)->update([
                'plan_id' => $transaction_details->plan_id,
                'term' => $term_days,
                'plan_validity' => $plan_validity,
                'plan_activation_date' => now(),
                'plan_details' => $plan_data
            ]);

            // Save applied coupon
            AppliedCoupon::where('transaction_id', $orderId)->update([
                'status' => 1
            ]);

            $encode = json_decode($transaction_details['invoice_details'], true);
            $details = [
                'from_billing_name' => $encode['from_billing_name'],
                'from_billing_email' => $encode['from_billing_email'],
                'from_billing_address' => $encode['from_billing_address'],
                'from_billing_city' => $encode['from_billing_city'],
                'from_billing_state' => $encode['from_billing_state'],
                'from_billing_country' => $encode['from_billing_country'],
                'from_billing_zipcode' => $encode['from_billing_zipcode'],
                'gobiz_transaction_id' => $transaction_details->gobiz_transaction_id,
                'to_billing_name' => $encode['to_billing_name'],
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

            try {
                Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
            } catch (\Exception $e) {
            }

            alert()->success(trans('Plan activation success!'));
            return redirect()->route('user.plans');
        } else {

            $message = "";
            if ($user_details->plan_id == $transaction_details->plan_id) {


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
                BusinessCard::where('user_id', $user_details->user_id)->update([
                    'card_status' => 'inactive',
                ]);
            } else {

                // Making all cards inactive, For Plan change
                BusinessCard::where('user_id', $user_details->user_id)->update([
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
                'transaction_id' => $transactionId,
                'invoice_prefix' => $config[15]->config_value,
                'invoice_number' => $invoice_number,
                'payment_status' => 'SUCCESS',
            ]);

            User::where('user_id', $user_details->user_id)->update([
                'plan_id' => $transaction_details->plan_id,
                'term' => $term_days,
                'plan_validity' => $plan_validity,
                'plan_activation_date' => now(),
                'plan_details' => $plan_data
            ]);

            // Save applied coupon
            AppliedCoupon::where('transaction_id', $orderId)->update([
                'status' => 1
            ]);

            $encode = json_decode($transaction_details['invoice_details'], true);
            $details = [
                'from_billing_name' => $encode['from_billing_name'],
                'from_billing_email' => $encode['from_billing_email'],
                'from_billing_address' => $encode['from_billing_address'],
                'from_billing_city' => $encode['from_billing_city'],
                'from_billing_state' => $encode['from_billing_state'],
                'from_billing_country' => $encode['from_billing_country'],
                'from_billing_zipcode' => $encode['from_billing_zipcode'],
                'gobiz_transaction_id' => $transaction_details->gobiz_transaction_id,
                'to_billing_name' => $encode['to_billing_name'],
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

            try {
                Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
            } catch (\Exception $e) {
            }

            Auth::loginUsingId($user_details->id);
            
            alert()->success($message);
            return redirect()->route('user.plans');
        }
    }
}
