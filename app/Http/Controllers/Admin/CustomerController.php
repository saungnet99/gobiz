<?php

namespace App\Http\Controllers\Admin;

use App\Plan;
use App\User;
use App\Gallery;
use App\Payment;
use App\Service;
use App\Setting;
use Carbon\Carbon;
use App\Transaction;
use App\BusinessCard;
use App\BusinessHour;
use App\StoreProduct;
use App\BusinessField;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // All Customers
    public function customers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role_id', '2')->get();

            return DataTables::of($data)
                ->addIndexColumn('id')
                ->addColumn('name', function ($row) {
                    $viewUrl = route('admin.view.customer', $row->user_id);
                    return '<a href="' . $viewUrl . '">' . $row->name . '</a>';
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('plan', function ($row) {
                    $plan_data = json_decode($row->plan_details, true);
                    $config = DB::table('config')->pluck('config_value', 'config_key')->toArray();
                    if ($plan_data == null) {
                        return __('No Plan');
                    } else {
                        $plan_name = __($plan_data['plan_name']);
                        $plan_price = isset($plan_data['plan_price']) && $plan_data['plan_price'] == '0'
                            ? __('Free')
                            : (isset($config[1]) ? $config[1] . ' ' . $plan_data['plan_price'] : $plan_data['plan_price']);
                        return '<strong>' . $plan_name . ' <span>(' . $plan_price . ')</span></strong>';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y h:i A', strtotime($row->created_at));
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 0
                        ? '<span class="badge bg-red text-white">' . __('Inactive') . '</span>'
                        : '<span class="badge bg-green text-white">' . __('Active') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.edit.customer', $row->user_id);
                    $changePlanUrl = route('admin.change.customer.plan', $row->user_id);
                    $activateDeactivate = $row->status == 0 ? 'Activate' : 'Deactivate';
                    $activateDeactivateFunction = $row->status == 0 ? 'activateUser' : 'deactivateUser';

                    return '
                        <div class="dropdown text-end">
                            <button class="btn small-btn dropdown-toggle align-text-top" id="dropdownMenuButton" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">
                            ' . __('Actions') . '
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="' . $editUrl . '">' . __('Edit') . '</a>
                                <a class="dropdown-item" href="' . $changePlanUrl . '">' . __('Change Plan') . '</a>
                                <a class="dropdown-item" href="#" onclick="' . $activateDeactivateFunction . '(\'' . $row->user_id . '\'); return false;">' . __($activateDeactivate) . '</a>
                                <a class="dropdown-item" href="#" onclick="deleteUser(\'' . $row->user_id . '\'); return false;">' . __('Delete') . '</a>
                            </div>
                        </div>';
                })
                ->rawColumns(['name', 'plan', 'status', 'action'])
                ->make(true);
        }

        $settings = Setting::where('status', 1)->first();
        $config = DB::table('config')->get();

        return view('admin.pages.customers.index', compact('settings', 'config'));
    }

    // View Customer
    public function viewCustomer(Request $request, $id)
    {
        $user_details = User::where('user_id', $id)->first();
        if ($user_details == null) {
            return redirect()->route('admin.customers')->with('failed', trans('User not found!'));
        } else {
            $user_cards = BusinessCard::where('user_id', $user_details->user_id)->where('status', 1)->get();
            $settings = Setting::where('status', 1)->first();
            return view('admin.pages.customers.view', compact('user_details', 'user_cards', 'settings'));
        }
    }

    // Edit Customer
    public function editCustomer(Request $request, $id)
    {
        $user_details = User::where('user_id', $id)->first();
        $settings = Setting::where('status', 1)->first();
        if ($user_details == null) {
            return redirect()->route('admin.customers')->with('failed', trans('Customer not found!'));
        } else {
            return view('admin.pages.customers.edit', compact('user_details', 'settings'));
        }
    }

    // Update Customer
    public function updateCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'full_name' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Queries
        $emailExists = User::where('email', $request->email)->count();

        if ($emailExists != 1) {
            if ($request->password == null) {
                User::where('user_id', $request->user_id)->update([
                    'name' => $request->full_name,
                    'email' => $request->email
                ]);
            } else {
                User::where('user_id', $request->user_id)->update([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
            }

            return redirect()->route('admin.customers')->with('success', 'Customer Updated Successfully!');
        } else {
            return redirect()->route('admin.customers')->with('failed', trans('This email address already registered. Try to another email address.'));
        }
    }

    // Change Customer plan
    public function ChangeCustomerPlan(Request $request, $id)
    {
        // Queries
        $user_details = User::where('user_id', $id)->first();

        if ($user_details) {
            // Queries
            $plans = Plan::where('status', 1)->get();
            $settings = Setting::where('status', 1)->first();
            $config = DB::table('config')->get();

            if ($plans == null) {
                return redirect()->route('admin.customers')->with('failed', trans('User not found!'));
            } else {
                return view('admin.pages.customers.change-plan', compact('user_details', 'plans', 'settings', 'config'));
            }
        } else {
            return redirect()->route('admin.customers')->with('failed', trans('User not found!'));
        }
    }

    // Upgrade Customer plan
    public function UpdateCustomerPlan(Request $request)
    {
        $config = DB::table('config')->get();

        $user_details = User::where('user_id', $request->user_id)->first();

        $plan_data = Plan::where('plan_id', $request->plan_id)->first();
        $term_days = $plan_data->validity;

        $amountToBePaid = ((float)($plan_data->plan_price) * (float)($config[25]->config_value) / 100) + (float)($plan_data->plan_price);

        if ($user_details->plan_validity == "") {

            $plan_validity = Carbon::now();
            $plan_validity->addDays($term_days);

            $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
            $invoice_number = $invoice_count + 1;

            $gobiz_transaction_id = uniqid();

            $invoice_details = [];

            $invoice_details['from_billing_name'] = $config[16]->config_value;
            $invoice_details['from_billing_address'] = $config[19]->config_value;
            $invoice_details['from_billing_city'] = $config[20]->config_value;
            $invoice_details['from_billing_state'] = $config[21]->config_value;
            $invoice_details['from_billing_zipcode'] = $config[22]->config_value;
            $invoice_details['from_billing_country'] = $config[23]->config_value;
            $invoice_details['from_vat_number'] = $config[26]->config_value;
            $invoice_details['from_billing_phone'] = $config[18]->config_value;
            $invoice_details['from_billing_email'] = $config[17]->config_value;
            $invoice_details['to_billing_name'] = $user_details->billing_name;
            $invoice_details['to_billing_address'] = $user_details->billing_address;
            $invoice_details['to_billing_city'] = $user_details->billing_city;
            $invoice_details['to_billing_state'] = $user_details->billing_state;
            $invoice_details['to_billing_zipcode'] = $user_details->billing_zipcode;
            $invoice_details['to_billing_country'] = $user_details->billing_country;
            $invoice_details['to_billing_phone'] = $user_details->billing_phone;
            $invoice_details['to_billing_email'] = $user_details->billing_email;
            $invoice_details['to_vat_number'] = $user_details->vat_number;
            $invoice_details['tax_name'] = $config[24]->config_value;
            $invoice_details['tax_type'] = $config[14]->config_value;
            $invoice_details['tax_value'] = $config[25]->config_value;
            $invoice_details['invoice_amount'] = $amountToBePaid;
            $invoice_details['subtotal'] = $plan_data->plan_price;
            $invoice_details['tax_amount'] = (float)($plan_data->plan_price) * (float)($config[25]->config_value) / 100;

            // If order is created from stripe
            $transaction = new Transaction();
            $transaction->gobiz_transaction_id = $gobiz_transaction_id;
            $transaction->transaction_date = now();
            $transaction->transaction_id = "";
            $transaction->user_id = $user_details->id;
            $transaction->plan_id = $plan_data->plan_id;
            $transaction->desciption = $plan_data->plan_name . " Plan";
            $transaction->payment_gateway_name = "Offline";
            $transaction->transaction_amount = $amountToBePaid;
            $transaction->invoice_prefix = $config[15]->config_value;
            $transaction->invoice_number = $invoice_number;
            $transaction->transaction_currency = $config[1]->config_value;
            $transaction->invoice_details = json_encode($invoice_details);
            $transaction->payment_status = "SUCCESS";
            $transaction->save();

            User::where('id', $user_details->id)->update([
                'plan_id' => $request->plan_id,
                'term' => $term_days,
                'plan_validity' => $plan_validity,
                'plan_activation_date' => now(),
                'plan_details' => $plan_data
            ]);

            $details = [
                'from_billing_name' => $config[16]->config_value,
                'from_billing_email' => $config[17]->config_value,
                'from_billing_address' => $config[19]->config_value,
                'from_billing_city' => $config[20]->config_value,
                'from_billing_state' => $config[21]->config_value,
                'from_billing_country' => $config[23]->config_value,
                'from_billing_zipcode' => $config[22]->config_value,
                'gobiz_transaction_id' => $gobiz_transaction_id,
                'to_billing_name' => $user_details->billing_name,
                'invoice_currency' => $config[1]->config_value,
                'invoice_amount' => $plan_data->plan_price,
                'invoice_id' => $config[15]->config_value . $invoice_number,
                'invoice_date' => Carbon::now(),
                'description' => $plan_data->plan_name . ' plan Upgrade',
                'email_heading' => $config[27]->config_value,
                'email_footer' => $config[28]->config_value,
            ];

            try {
                Mail::to($user_details->email)->send(new \App\Mail\SendEmailInvoice($details));
            } catch (\Exception $e) {
            }

            return redirect()->route('admin.offline.transactions')->with('success', trans('Plan changed success!'));
        } else {
            $message = "";
            if ($user_details->plan_id == $request->plan_id) {


                // Check if plan validity is expired or not.
                $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $user_details->plan_validity);
                $current_date = Carbon::now();
                $remaining_days = $current_date->diffInDays($plan_validity, false);

                if ($remaining_days > 0) {
                    $plan_validity = Carbon::parse($user_details->plan_validity);
                    $plan_validity->addDays($term_days);
                    $message = "Plan changed successfully!";
                } else {
                    $plan_validity = Carbon::now();
                    $plan_validity->addDays($term_days);
                    $message = "Plan changed successfully!";
                }
            } else {

                // Making all cards inactive, For Plan change
                BusinessCard::where('user_id', $user_details->user_id)->update([
                    'card_status' => 'inactive',
                ]);

                $plan_validity = Carbon::now();
                $plan_validity->addDays($term_days);
                $message = "Plan changed successfully!";
            }

            $invoice_count = Transaction::where("invoice_prefix", $config[15]->config_value)->count();
            $invoice_number = $invoice_count + 1;

            $gobiz_transaction_id = uniqid();

            $invoice_details = [];

            $invoice_details['from_billing_name'] = $config[16]->config_value;
            $invoice_details['from_billing_address'] = $config[19]->config_value;
            $invoice_details['from_billing_city'] = $config[20]->config_value;
            $invoice_details['from_billing_state'] = $config[21]->config_value;
            $invoice_details['from_billing_zipcode'] = $config[22]->config_value;
            $invoice_details['from_billing_country'] = $config[23]->config_value;
            $invoice_details['from_vat_number'] = $config[26]->config_value;
            $invoice_details['from_billing_phone'] = $config[18]->config_value;
            $invoice_details['from_billing_email'] = $config[17]->config_value;
            $invoice_details['to_billing_name'] = $user_details->billing_name;
            $invoice_details['to_billing_address'] = $user_details->billing_address;
            $invoice_details['to_billing_city'] = $user_details->billing_city;
            $invoice_details['to_billing_state'] = $user_details->billing_state;
            $invoice_details['to_billing_zipcode'] = $user_details->billing_zipcode;
            $invoice_details['to_billing_country'] = $user_details->billing_country;
            $invoice_details['to_billing_phone'] = $user_details->billing_phone;
            $invoice_details['to_billing_email'] = $user_details->billing_email;
            $invoice_details['to_vat_number'] = $user_details->vat_number;
            $invoice_details['tax_name'] = $config[24]->config_value;
            $invoice_details['tax_type'] = $config[14]->config_value;
            $invoice_details['tax_value'] = $config[25]->config_value;
            $invoice_details['invoice_amount'] = $amountToBePaid;
            $invoice_details['subtotal'] = $plan_data->plan_price;
            $invoice_details['tax_amount'] = (float)($plan_data->plan_price) * (float)($config[25]->config_value) / 100;

            // If order is created from stripe
            $transaction = new Transaction();
            $transaction->gobiz_transaction_id = $gobiz_transaction_id;
            $transaction->transaction_date = now();
            $transaction->transaction_id = "";
            $transaction->user_id = $user_details->id;
            $transaction->plan_id = $plan_data->plan_id;
            $transaction->desciption = $plan_data->plan_name . " Plan";
            $transaction->payment_gateway_name = "Offline";
            $transaction->transaction_amount = $amountToBePaid;
            $transaction->invoice_prefix = $config[15]->config_value;
            $transaction->invoice_number = $invoice_number;
            $transaction->transaction_currency = $config[1]->config_value;
            $transaction->invoice_details = json_encode($invoice_details);
            $transaction->payment_status = "SUCCESS";
            $transaction->save();

            User::where('id', $user_details->id)->update([
                'plan_id' => $request->plan_id,
                'term' => $term_days,
                'plan_validity' => $plan_validity,
                'plan_activation_date' => now(),
                'plan_details' => $plan_data
            ]);

            $details = [
                'from_billing_name' => $config[16]->config_value,
                'from_billing_email' => $config[17]->config_value,
                'from_billing_address' => $config[19]->config_value,
                'from_billing_city' => $config[20]->config_value,
                'from_billing_state' => $config[21]->config_value,
                'from_billing_country' => $config[23]->config_value,
                'from_billing_zipcode' => $config[22]->config_value,
                'gobiz_transaction_id' => $gobiz_transaction_id,
                'to_billing_name' => $user_details->billing_name,
                'invoice_currency' => $config[1]->config_value,
                'invoice_amount' => $plan_data->plan_price,
                'invoice_id' => $config[15]->config_value . $invoice_number,
                'invoice_date' => Carbon::now(),
                'description' => $plan_data->plan_name . ' plan Upgrade',
                'email_heading' => $config[27]->config_value,
                'email_footer' => $config[28]->config_value,
            ];

            try {
                Mail::to($user_details->email)->send(new \App\Mail\SendEmailInvoice($details));
            } catch (\Exception $e) {
            }

            return redirect()->route('admin.change.customer.plan', $request->user_id)->with('success', trans($message));
        }
    }

    // Update status
    public function updateStatus(Request $request)
    {
        $user_details = User::where('user_id', $request->query('id'))->first();
        if ($user_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        User::where('user_id', $request->query('id'))->update(['status' => $status]);

        return redirect()->route('admin.customers')->with('success', 'Updated!');
    }

    // Delete Customer
    public function deleteCustomer(Request $request)
    {
        // Queries
        $allcards = BusinessCard::where('user_id', $request->query('id'))->get();
        $user = User::where('user_id', $request->query('id'))->first();
        for ($i = 0; $i < count($allcards); $i++) {
            if ($allcards != null) {
                BusinessField::where('card_id', $allcards[$i]->card_id)->delete();
                BusinessHour::where('card_id', $allcards[$i]->card_id)->delete();
                Gallery::where('card_id', $allcards[$i]->card_id)->delete();
                Payment::where('card_id', $allcards[$i]->card_id)->delete();
                Service::where('card_id', $allcards[$i]->card_id)->delete();
                StoreProduct::where('card_id', $allcards[$i]->card_id)->delete();
            }
        }

        $transactions = Transaction::where('user_id', $request->query('id'))->first();
        $businessCards = BusinessCard::where('user_id', $request->query('id'))->first();

        if ($transactions != null) {
            $transactions->delete();
        }

        if ($businessCards != null) {
            $businessCards->delete();
        }

        User::where('user_id', $request->query('id'))->delete();

        return redirect()->route('admin.customers')->with('success', 'Removed!');
    }

    // Login As Customer
    public function authAs(Request $request, $id)
    {
        $user_details = User::where('user_id', $id)->where('status', 1)->first();
        if (isset($user_details)) {
            Auth::loginUsingId($user_details->id);
            return redirect()->route('user.dashboard');
        } else {
            return redirect()->route('admin.customers')->with('info', 'Unable to find user account!');
        }
    }
}
