<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SubdomainSettingController extends Controller
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

    // Update enable subdomain Setting
    public function index(Request $request)
    {
        // Enable subdomain feature in vcard and store
        DB::table('config')->where('config_key', 'enable_subdomain')->update([
            'config_value' => $request->enable_subdomain,
        ]);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Subdomain enabled Updated Successfully!'));
    }
}
