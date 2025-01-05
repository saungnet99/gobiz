<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentSettingController extends Controller
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

    // Update Payments Setting
    public function index(Request $request)
    {
        // Paypal mode
        DB::table('config')->where('config_key', 'paypal_mode')->update([
            'config_value' => $request->paypal_mode,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'paypal_client_id')->update([
            'config_value' => $request->paypal_client_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'paypal_secret')->update([
            'config_value' => $request->paypal_secret,
            'updated_at' => now(),
        ]);

        // Razorpay
        DB::table('config')->where('config_key', 'razorpay_key')->update([
            'config_value' => $request->razorpay_client_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'razorpay_secret')->update([
            'config_value' => $request->razorpay_secret,
            'updated_at' => now(),
        ]);

        // Phonepe
        DB::table('config')->where('config_key', 'merchantId')->update([
            'config_value' => $request->merchantId,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'saltKey')->update([
            'config_value' => $request->saltKey,
            'updated_at' => now(),
        ]);

        // Stripe
        DB::table('config')->where('config_key', 'stripe_publishable_key')->update([
            'config_value' => $request->stripe_publishable_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'stripe_secret')->update([
            'config_value' => $request->stripe_secret,
            'updated_at' => now(),
        ]);

        // Paystack
        DB::table('config')->where('config_key', 'paystack_public_key')->update([
            'config_value' => $request->paystack_public_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'paystack_secret_key')->update([
            'config_value' => $request->paystack_secret,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'merchant_email')->update([
            'config_value' => $request->merchant_email,
            'updated_at' => now(),
        ]);

        // Mollie
        DB::table('config')->where('config_key', 'mollie_key')->update([
            'config_value' => $request->mollie_key,
            'updated_at' => now(),
        ]);

        // Mercadopago
        DB::table('config')->where('config_key', 'mercado_pago_public_key')->update([
            'config_value' => $request->mercado_pago_public_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'mercado_pago_access_token')->update([
            'config_value' => $request->mercado_pago_access_token,
            'updated_at' => now(),
        ]);

        // Toyyibpay
        DB::table('config')->where('config_key', 'toyyibpay_api_key')->update([
            'config_value' => $request->toyyibpay_api_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'toyyibpay_category_code')->update([
            'config_value' => $request->toyyibpay_category_code,
            'updated_at' => now(),
        ]);

        // Flutterwave
        DB::table('config')->where('config_key', 'flw_public_key')->update([
            'config_value' => $request->flw_public_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'flw_secret_key')->update([
            'config_value' => $request->flw_secret_key,
            'updated_at' => now(),
        ]);

        DB::table('config')->where('config_key', 'flw_encryption_key')->update([
            'config_value' => $request->flw_encryption_key,
            'updated_at' => now(),
        ]);

        // Bank transfer
        DB::table('config')->where('config_key', 'bank_transfer')->update([
            'config_value' => $request->bank_transfer,
            'updated_at' => now(),
        ]);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Payment Settings Updated Successfully!'));
    }
}
