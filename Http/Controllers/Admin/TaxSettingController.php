<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TaxSettingController extends Controller
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

    // Tax settings
    public function taxSetting()
    {
        // Queries
        $config = DB::table('config')->get();
        $settings = Setting::first();

        // Page view
        return view('admin.pages.tax.index', compact('config', 'settings'));
    }

    // Update tax setting
    public function updateTaxSetting(Request $request)
    {
        // Update
        DB::table('config')->where('config_key', 'invoice_prefix')->update([
            'config_value' => $request->invoice_prefix,
        ]);

        DB::table('config')->where('config_key', 'invoice_name')->update([
            'config_value' => $request->invoice_name,
        ]);

        DB::table('config')->where('config_key', 'invoice_email')->update([
            'config_value' => $request->invoice_email,
        ]);

        DB::table('config')->where('config_key', 'invoice_phone')->update([
            'config_value' => $request->invoice_phone,
        ]);

        DB::table('config')->where('config_key', 'invoice_address')->update([
            'config_value' => $request->invoice_address,
        ]);

        DB::table('config')->where('config_key', 'invoice_city')->update([
            'config_value' => $request->invoice_city,
        ]);

        DB::table('config')->where('config_key', 'invoice_state')->update([
            'config_value' => $request->invoice_state,
        ]);

        DB::table('config')->where('config_key', 'invoice_zipcode')->update([
            'config_value' => $request->invoice_zipcode,
        ]);

        DB::table('config')->where('config_key', 'invoice_country')->update([
            'config_value' => $request->invoice_country,
        ]);

        DB::table('config')->where('config_key', 'tax_name')->update([
            'config_value' => $request->tax_name,
        ]);

        DB::table('config')->where('config_key', 'tax_number')->update([
            'config_value' => $request->tax_number,
        ]);

        DB::table('config')->where('config_key', 'tax_value')->update([
            'config_value' => $request->tax_value,
        ]);

        DB::table('config')->where('config_key', 'invoice_footer')->update([
            'config_value' => $request->invoice_footer,
        ]);

        // Page redirect
        return redirect()->route('admin.tax.setting')->with('success', trans('Invoice Setting Updated Successfully!'));
    }

    // Update email template setting
    public function updateEmailSetting(Request $request)
    {
        // Update
        DB::table('config')->where('config_key', 'email_heading')->update([
            'config_value' => $request->email_heading,
        ]);

        DB::table('config')->where('config_key', 'email_footer')->update([
            'config_value' => $request->email_footer,
        ]);

        // Page redirect
        return redirect()->route('admin.tax.setting')->with('success', trans('Email Setting Updated Successfully!'));
    }
}
