<?php

namespace App\Http\Controllers\Admin;

use App\Gateway;
use App\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
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

    // All Payment Methods
    public function paymentMethods(Request $request)
    {
        if ($request->ajax()) {
            $payment_methods = Gateway::orderBy('created_at', 'desc')->get();

            return DataTables::of($payment_methods)
                ->addIndexColumn()
                ->addColumn('payment_gateway_logo', function ($payment_method) {
                    return '<span class="avatar me-2" style="background-image: url(' . asset($payment_method->payment_gateway_logo) . ')"></span>';
                })
                ->addColumn('payment_gateway_name', function ($payment_method) {
                    return __($payment_method->payment_gateway_name);
                })
                ->addColumn('is_status', function ($payment_method) {
                    return $payment_method->is_status == 'disabled' ? __('Not Installed Yet') : __('Installed');
                })
                ->addColumn('status', function ($payment_method) {
                    if ($payment_method->status == 0) {
                        return '<span class="badge bg-red text-white">' . __('Inactive') . '</span>';
                    } else {
                        return '<span class="badge bg-green text-white">' . __('Active') . '</span>';
                    }
                })
                ->addColumn('action', function ($payment_method) {
                    if ($payment_method->status == 0) {
                        return '<a class="btn small-btn btn-primary btn-sm" href="#" onclick="getPaymentMethod(`' . $payment_method->payment_gateway_id . '`); return false;">' . __('Activate') . '</a>';
                    } else {
                        return '<a class="btn small-btn btn-primary btn-sm" href="#" onclick="getPaymentMethod(`' . $payment_method->payment_gateway_id . '`); return false;">' . __('Deactivate') . '</a>';
                    }
                })
                ->rawColumns(['payment_gateway_logo', 'status', 'action'])
                ->make(true);
        }

        // Queries
        $settings = Setting::where('status', 1)->first();

        return view('admin.pages.payment-methods.payment-methods', compact('settings'));
    }

    // Add Payment Method
    public function addPaymentMethod()
    {
        $settings = Setting::where('status', 1)->first();
        return view('admin.pages.payment-methods.add-payment-method', compact('settings'));
    }

    // Save Payment Method
    public function savePaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_gateway_logo' => 'required|payment_gateway_logo|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'payment_gateway_name' => 'required',
            'client_id' => 'required',
            'secret_key' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        $payment_gateway_logo = 'img/payment-method/' . 'IMG-' . time() . '.' . $request->payment_gateway_logo->extension();

        $request->payment_gateway_logo->move(public_path('img/payment-method'), $payment_gateway_logo);

        $paymentMethod = new Gateway;
        $paymentMethod->payment_gateway_id = uniqid();
        $paymentMethod->payment_gateway_logo = $payment_gateway_logo;
        $paymentMethod->payment_gateway_name = $request->payment_gateway_name;
        $paymentMethod->client_id = $request->client_id;
        $paymentMethod->secret_key = $request->secret_key;
        $paymentMethod->save();

        return redirect()->route('admin.add.payment.method')->with('success', trans('New Payment Method Created Successfully!'));
    }

    // Edit Payment Method
    public function editPaymentMethod(Request $request, $id)
    {
        $gateway_id = $request->id;
        if ($gateway_id == null) {
            return view('errors.404');
        } else {
            $gateway_details = Gateway::where('payment_gateway_id', $gateway_id)->first();
            $settings = Setting::where('status', 1)->first();
            return view('admin.pages.payment-methods.edit-payment-gateway', compact('gateway_details', 'settings'));
        }
    }

    // Update Payment Method
    public function updatePaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_gateway_id' => 'required',
            'payment_gateway_name' => 'required',
            'client_id' => 'required',
            'secret_key' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        if ($request->is_status == null) {
            $is_status = 'disabled';
        } else {
            $is_status = 'enabled';
        }

        Gateway::where('payment_gateway_id', $request->payment_gateway_id)->update([
            'payment_gateway_name' => $request->payment_gateway_name,
            'client_id' => $request->client_id, 'secret_key' => $request->secret_key, 'is_status' => $is_status
        ]);

        return redirect()->route('admin.edit.payment.method', $request->payment_gateway_id)->with('success', trans('Updated!'));
    }

    // Delete Payment Method
    public function deletePaymentMethod(Request $request)
    {
        $payment_gateway_details = Gateway::where('payment_gateway_id', $request->query('id'))->first();
        if ($payment_gateway_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        Gateway::where('payment_gateway_id', $request->query('id'))->update(['status' => $status]);

        return redirect()->route('admin.payment.methods')->with('success', trans('Updated!'));
    }
}
