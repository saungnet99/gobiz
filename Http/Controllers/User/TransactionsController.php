<?php

namespace App\Http\Controllers\User;

use App\Plan;
use App\User;
use App\Setting;
use App\Currency;
use App\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
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

    public function indexTransactions(Request $request)
    {
        $active_plan = Plan::where('plan_id', Auth::user()->plan_id)->first();
        $plan = User::where('user_id', Auth::user()->user_id)->first();
        $active_plan = json_decode($plan->plan_details);

        if ($active_plan != null) {
            if ($request->ajax()) {
                $transactions = Transaction::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
                
                return DataTables::of($transactions)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($transaction) {
                        return $transaction->created_at->format('d-m-Y H:i:s A');
                    })
                    ->addColumn('payment_gateway_name', function ($transaction) {
                        return __($transaction->payment_gateway_name);
                    })
                    ->addColumn('transaction_amount', function ($transaction) {
                        return Currency::where('iso_code', $transaction->transaction_currency)->first()->symbol . $transaction->transaction_amount;
                    })
                    ->addColumn('payment_status', function ($transaction) {
                        if ($transaction->payment_status == 'SUCCESS') {
                            return '<span class="badge bg-green text-white">' . __('Paid') . '</span>';
                        } elseif ($transaction->payment_status == 'FAILED') {
                            return '<span class="badge bg-red text-white">' . __('Failed') . '</span>';
                        } else {
                            return '<span class="badge bg-yellow text-white">' . __('Pending') . '</span>';
                        }
                    })
                    ->addColumn('action', function ($transaction) {
                        // Invoice
                        $invoice = '<a href="'.route('user.view.invoice', ['id' => $transaction->gobiz_transaction_id]).'" class="btn btn-primary btn-icon" title="' . trans('Invoice') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-invoice"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M19 12v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-14a2 2 0 0 1 2 -2h7l5 5v4.25" /></svg>
                                    </a>';
                        if ($transaction->invoice_number > 0) {
                            return $invoice;
                        } else {
                            return "-";
                        }
                    })
                    ->rawColumns(['payment_status', 'action'])
                    ->make(true);
            }

            $settings = Setting::where('status', 1)->first();
            $currencies = Currency::get();

            return view('user.pages.transactions.index', compact('settings', 'currencies'));
        } else {
            return redirect()->route('user.plans');
        }
    }

    // View invoice
    public function viewInvoice($id)
    {
        // Queries
        $transaction = Transaction::where('gobiz_transaction_id', $id)->orWhere('transaction_id', $id)->first();

        if ($transaction) {
            $settings = Setting::where('status', 1)->first();
            $config = DB::table('config')->get();
            $currencies = Currency::get();
            $transaction['billing_details'] = json_decode($transaction['invoice_details'], true);

            return view('user.pages.transactions.view-invoice', compact('transaction', 'settings', 'config', 'currencies'));
        } else {
            return back()->with('failed', trans('Invoice not found!'));
        }
    }
}
