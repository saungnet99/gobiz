<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdvancedSettingController extends Controller
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

    // Advanced settings
    public function advancedSetting(Request $request, $id)
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        return view('user.pages.cards.advanced-settings', compact('plan_details', 'settings'));
    }

    // Save Advanced settings
    public function saveAdvancedSetting(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            // Update
            BusinessCard::where('card_id', $id)->update([
                'password' => $request->password,
                'custom_css' => $request->custom_css,
                'custom_js' => $request->custom_js,
            ]);

            return redirect()->route('user.cards')->with('success', trans('Your virtual business card is updated!'));
        }
    }
}
