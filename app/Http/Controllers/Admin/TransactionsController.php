<?php

namespace App\Http\Controllers\Admin;

use App\Plan;
use App\User;
use App\Setting;
use App\Currency;
use Carbon\Carbon;
use App\Transaction;
use App\BusinessCard;
use App\AppliedCoupon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TransactionsController extends Controller
{
    // All online transactions
    public function indexTransactions(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::where('payment_gateway_name', '!=', 'Offline')->orderBy('id', 'desc')->get();

            return DataTables::of($transactions)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s A');
                })
                ->addColumn('gobiz_transaction_id', function ($row) {
                    return $row->gobiz_transaction_id;
                })
                ->addColumn('transaction_id', function ($row) {
                    return $row->transaction_id;
                })
                ->addColumn('user', function ($row) {
                    $user_details = User::where('id', $row->user_id)->first();
                    if ($user_details) {
                        return '<a href="' . route('admin.view.user', $user_details->user_id) . '">' . $user_details->name . '</a>';
                    } else {
                        return '<a href="#">' . __("Customer not available") . '</a>';
                    }
                })
                ->addColumn('payment_gateway_name', function ($row) {
                    return __($row->payment_gateway_name);
                })
                ->addColumn('transaction_amount', function ($row) {
                    $currencies = Currency::pluck('symbol', 'iso_code')->toArray();
                    $symbol = $currencies[$row->transaction_currency] ?? '';
                    return $symbol . $row->transaction_amount;
                })
                ->addColumn('payment_status', function ($row) {
                    $status = '';
                    if ($row->payment_status == 'SUCCESS') {
                        $status = '<span class="badge bg-green text-white">' . __('Paid') . '</span>';
                    } elseif ($row->payment_status == 'FAILED') {
                        $status = '<span class="badge bg-red text-white">' . __('Failed') . '</span>';
                    } elseif ($row->payment_status == 'PENDING') {
                        $status = '<span class="badge bg-orange text-white">' . __('Pending') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="dropdown">
                                    <button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button>
                                    <div class="actions dropdown-menu dropdown-menu-end">';
                    if ($row->payment_status == "SUCCESS") {
                        $actions .= '<a class="dropdown-item" target="_blank" href="' . route('admin.view.invoice', ['id' => $row->gobiz_transaction_id]) . '">' . __('Invoice') . '</a>';
                    }
                    if ($row->payment_status != "SUCCESS") {
                        $actions .= '<a class="dropdown-item" href="' . route('admin.trans.status', ['id' => $row->gobiz_transaction_id, 'status' => 'PENDING']) . '">' . __('Pending') . '</a>';
                        $actions .= '<a class="dropdown-item" href="#" onclick="getTransaction(\'' . $row->gobiz_transaction_id . '\'); return false;">' . __('Success') . '</a>';
                        $actions .= '<a class="dropdown-item" href="' . route('admin.trans.status', ['id' => $row->gobiz_transaction_id, 'status' => 'FAILED']) . '">' . __('Failed') . '</a>';
                    }
                    $actions .= '</div></div>';
                    return $actions;
                })
                ->rawColumns(['user', 'payment_status', 'action'])
                ->make(true);
        }

        $settings = Setting::where('status', 1)->first();
        $currencies = Currency::get();

        return view('admin.pages.transactions.index', compact('settings', 'currencies'));
    }

    // Update transaction status
    public function transactionStatus(Request $request, $id, $status)
    {
        // Transaction details
        $transaction_details = Transaction::where('gobiz_transaction_id', $id)->where('status', 1)->first();
        $user_details = User::find($transaction_details->user_id);

        // Success to failed or pending
        if ($transaction_details->payment_status == "SUCCESS" && $status != "SUCCESS") {
            // Update transaction status
            Transaction::where('gobiz_transaction_id', $id)->update([
                'invoice_prefix' => null,
                'invoice_number' => null,
                'payment_status' => $status,
            ]);
        }

        // If offline status is "SUCCESS"
        if ($status == "SUCCESS") {

            // Get config details
            $config = DB::table('config')->get();

            // Get plan validity
            $plan_data = Plan::where('plan_id', $transaction_details->plan_id)->first();
            $term_days = $plan_data->validity;

            // Customer plan validity
            if ($user_details->plan_validity == "") {
                // Add days
                if ($term_days == "9999") {
                    $plan_validity = "2050-12-30 23:23:59";
                } else {
                    $plan_validity = Carbon::now();
                    $plan_validity->addDays($term_days);
                }

                // Invoice count generate
                $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                $invoice_number = $invoice_count + 1;

                // Update transaction status
                Transaction::where('gobiz_transaction_id', $id)->update([
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                // Update customer plan details
                User::where('id', $user_details->id)->update([
                    'plan_id' => $transaction_details->plan_id,
                    'term' => $term_days,
                    'plan_validity' => $plan_validity,
                    'plan_activation_date' => now(),
                    'plan_details' => $plan_data
                ]);

                // Save applied coupon
                AppliedCoupon::where('transaction_id', $transaction_details->transaction_id)->update([
                    'status' => 1
                ]);

                // Generate invoice to customer
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
                    'description' => $transaction_details->description,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                // Send email to customer
                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                }

                // Return and alert
                return redirect()->route('admin.offline.transactions')->with('success', trans('Plan activation success!'));
            } else {
                $message = "";
                if ($user_details->plan_id == $transaction_details->plan_id) {


                    // Check if plan validity is expired or not.
                    $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $user_details->plan_validity);
                    $current_date = Carbon::now();
                    $remaining_days = $current_date->diffInDays($plan_validity, false);

                    if ($remaining_days > 0) {
                        // Add days
                        if ($term_days == "9999") {
                            $plan_validity = "2050-12-30 23:23:59";
                            $message = "Plan activated successfully!";
                        } else {
                            $plan_validity = Carbon::parse($user_details->plan_validity);
                            $plan_validity->addDays($term_days);
                            $message = "Plan activated successfully!";
                        }
                    } else {
                        // Add days
                        if ($term_days == "9999") {
                            $plan_validity = "2050-12-30 23:23:59";
                            $message = "Plan activated successfully!";
                        } else {
                            $plan_validity = Carbon::now();
                            $plan_validity->addDays($term_days);
                            $message = "Plan activated successfully!";
                        }
                    }
                } else {

                    // Making all cards inactive, For Plan change
                    BusinessCard::where('user_id', $user_details->user_id)->update([
                        'card_status' => 'inactive',
                    ]);

                    // Add days
                    if ($term_days == "9999") {
                        $plan_validity = "2050-12-30 23:23:59";
                        $message = "Plan activated successfully!";
                    } else {
                        $plan_validity = Carbon::now();
                        $plan_validity->addDays($term_days);
                        $message = "Plan activated successfully!";
                    }
                }

                $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                $invoice_number = $invoice_count + 1;

                Transaction::where('gobiz_transaction_id', $id)->update([
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                User::where('id', $user_details->id)->update([
                    'plan_id' => $transaction_details->plan_id,
                    'term' => $term_days,
                    'plan_validity' => $plan_validity,
                    'plan_activation_date' => now(),
                    'plan_details' => $plan_data
                ]);

                // Save applied coupon
                AppliedCoupon::where('transaction_id', $transaction_details->transaction_id)->update([
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
                    'description' => $transaction_details->description,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                }

                return redirect()->route('admin.transactions')->with('success', trans($message));
            }
        } else {
            Transaction::where('gobiz_transaction_id', $id)->update([
                'payment_status' => $status,
            ]);

            return redirect()->route('admin.transactions')->with('success', trans('Transaction updated successfully'));
        }
    }

    // View invoice
    public function viewInvoice($id)
    {
        // Queries
        $transaction = Transaction::where('gobiz_transaction_id', $id)->where('payment_status', 'SUCCESS')->first();

        if ($transaction) {
            $settings = Setting::where('status', 1)->first();
            $config = DB::table('config')->get();
            $currencies = Currency::get();
            $transaction['billing_details'] = json_decode($transaction['invoice_details'], true);

            // View
            return view('admin.pages.transactions.view-invoice', compact('transaction', 'settings', 'config', 'currencies'));
        } else {
            return back()->with('failed', trans('Invoice not found!'));
        }
    }

    // All offline transactions
    public function offlineTransactions(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::where('payment_gateway_name', 'Offline')->orderBy('id', 'desc')->get();

            return DataTables::of($transactions)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s A');
                })
                ->addColumn('gobiz_transaction_id', function ($row) {
                    return $row->gobiz_transaction_id;
                })
                ->addColumn('transaction_id', function ($row) {
                    return $row->transaction_id != null ? $row->transaction_id : '-';
                })
                ->addColumn('user', function ($row) {
                    $user_details = User::where('id', $row->user_id)->first();
                    if ($user_details) {
                        return '<a href="' . route('admin.view.user', $user_details->user_id) . '">' . $user_details->name . '</a>';
                    } else {
                        return '<a href="#">' . __("Customer not available") . '</a>';
                    }
                })
                ->addColumn('payment_gateway_name', function ($row) {
                    return __($row->payment_gateway_name);
                })
                ->addColumn('transaction_amount', function ($row) {
                    $currencies = Currency::pluck('symbol', 'iso_code')->toArray();
                    $symbol = $currencies[$row->transaction_currency] ?? '';
                    return $symbol . $row->transaction_amount;
                })
                ->addColumn('payment_status', function ($row) {
                    $status = '';
                    if ($row->payment_status == 'SUCCESS') {
                        $status = '<span class="badge bg-green text-white">' . __('Paid') . '</span>';
                    } elseif ($row->payment_status == 'FAILED') {
                        $status = '<span class="badge bg-red text-white">' . __('Failed') . '</span>';
                    } elseif ($row->payment_status == 'PENDING') {
                        $status = '<span class="badge bg-orange text-white">' . __('Pending') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="dropdown">
                                    <button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button>
                                    <div class="actions dropdown-menu dropdown-menu-end">';
                    if ($row->invoice_number > 0) {
                        $actions .= '<a class="dropdown-item" target="_blank" href="' . route('admin.view.invoice', ['id' => $row->gobiz_transaction_id]) . '">' . __('Invoice') . '</a>';
                    }
                    if ($row->payment_status != "SUCCESS") {
                        $actions .= '<a class="dropdown-item" href="#" onclick="getOfflineTransaction(\'' . $row->gobiz_transaction_id . '\'); return false;">' . __('Success') . '</a>';
                        $actions .= '<a class="dropdown-item" href="' . route('admin.offline.trans.status', ['id' => $row->gobiz_transaction_id, 'status' => 'PENDING']) . '">' . __('Pending') . '</a>';
                        $actions .= '<a class="dropdown-item" href="' . route('admin.offline.trans.status', ['id' => $row->gobiz_transaction_id, 'status' => 'FAILED']) . '">' . __('Failed') . '</a>';
                    }
                    $actions .= '</div></div>';
                    return $actions;
                })
                ->rawColumns(['user', 'payment_status', 'action'])
                ->make(true);
        }

        $settings = Setting::where('status', 1)->first();
        $currencies = Currency::get();

        return view('admin.pages.transactions.offline', compact('settings', 'currencies'));
    }

    // Offline
    public function offlineTransactionStatus(Request $request, $id, $status)
    {
        // Transaction details
        $transaction_details = Transaction::where('gobiz_transaction_id', $id)->where('status', 1)->first();
        $user_details = User::find($transaction_details->user_id);

        // Success to failed or pending
        if ($transaction_details->payment_status == "SUCCESS" && $status != "SUCCESS") {
            // Update transaction status
            Transaction::where('gobiz_transaction_id', $id)->update([
                'invoice_prefix' => null,
                'invoice_number' => null,
                'payment_status' => $status,
            ]);
        }

        // If offline status is "SUCCESS"
        if ($status == "SUCCESS") {

            // Get config details
            $config = DB::table('config')->get();

            // Transaction details
            $transaction_details = Transaction::where('gobiz_transaction_id', $id)->where('status', 1)->first();
            $user_details = User::find($transaction_details->user_id);

            // Get plan validity
            $plan_data = Plan::where('plan_id', $transaction_details->plan_id)->first();
            $term_days = $plan_data->validity;

            // Customer plan validity
            if ($user_details->plan_validity == "") {
                // Add days
                if ($term_days == "9999") {
                    $plan_validity = "2050-12-30 23:23:59";
                } else {
                    $plan_validity = Carbon::now();
                    $plan_validity->addDays($term_days);
                }

                // Invoice count generate
                $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                $invoice_number = $invoice_count + 1;

                // Update transaction status
                Transaction::where('gobiz_transaction_id', $id)->update([
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                // Update customer plan details
                User::where('id', $user_details->id)->update([
                    'plan_id' => $transaction_details->plan_id,
                    'term' => $term_days,
                    'plan_validity' => $plan_validity,
                    'plan_activation_date' => now(),
                    'plan_details' => $plan_data
                ]);

                // Save applied coupon
                AppliedCoupon::where('transaction_id', $transaction_details->transaction_id)->update([
                    'status' => 1
                ]);

                // Generate invoice to customer
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
                    'description' => $transaction_details->description,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                // Send email to customer
                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                }

                // Return and alert
                return redirect()->route('admin.offline.transactions')->with('success', trans('Plan activation success!'));
            } else {
                $message = "";
                if ($user_details->plan_id == $transaction_details->plan_id) {


                    // Check if plan validity is expired or not.
                    $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $user_details->plan_validity);
                    $current_date = Carbon::now();
                    $remaining_days = $current_date->diffInDays($plan_validity, false);

                    if ($remaining_days > 0) {
                        // Add days
                        if ($term_days == "9999") {
                            $plan_validity = "2050-12-30 23:23:59";
                            $message = "Plan activated successfully!";
                        } else {
                            $plan_validity = Carbon::parse($user_details->plan_validity);
                            $plan_validity->addDays($term_days);
                            $message = "Plan activated successfully!";
                        }
                    } else {
                        // Add days
                        if ($term_days == "9999") {
                            $plan_validity = "2050-12-30 23:23:59";
                            $message = "Plan activated successfully!";
                        } else {
                            $plan_validity = Carbon::now();
                            $plan_validity->addDays($term_days);
                            $message = "Plan activated successfully!";
                        }
                    }
                } else {

                    // Making all cards inactive, For Plan change
                    BusinessCard::where('user_id', $user_details->user_id)->update([
                        'card_status' => 'inactive',
                    ]);

                    // Add days
                    if ($term_days == "9999") {
                        $plan_validity = "2050-12-30 23:23:59";
                        $message = "Plan activated successfully!";
                    } else {
                        $plan_validity = Carbon::now();
                        $plan_validity->addDays($term_days);
                        $message = "Plan activated successfully!";
                    }
                }

                $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
                $invoice_number = $invoice_count + 1;

                Transaction::where('gobiz_transaction_id', $id)->update([
                    'invoice_prefix' => $config[15]->config_value,
                    'invoice_number' => $invoice_number,
                    'payment_status' => 'SUCCESS',
                ]);

                User::where('id', $user_details->id)->update([
                    'plan_id' => $transaction_details->plan_id,
                    'term' => $term_days,
                    'plan_validity' => $plan_validity,
                    'plan_activation_date' => now(),
                    'plan_details' => $plan_data
                ]);

                // Save applied coupon
                AppliedCoupon::where('transaction_id', $transaction_details->transaction_id)->update([
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
                    'description' => $transaction_details->description,
                    'email_heading' => $config[27]->config_value,
                    'email_footer' => $config[28]->config_value,
                ];

                try {
                    Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));
                } catch (\Exception $e) {
                }

                return redirect()->route('admin.offline.transactions')->with('success', trans($message));
            }
        } else {
            Transaction::where('gobiz_transaction_id', $id)->update([
                'payment_status' => $status,
            ]);

            return redirect()->route('admin.offline.transactions')->with('success', trans('Transaction updated successfully'));
        }
    }
}
