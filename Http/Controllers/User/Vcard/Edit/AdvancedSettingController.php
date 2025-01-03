<?php

namespace App\Http\Controllers\User\Vcard\Edit;

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

    // Edit Advanced settings
    public function editAdvancedSetting(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();
        $settings = Setting::where('status', 1)->first();

        // Check business card
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            return view('user.pages.edit-cards.edit-advanced-settings', compact('plan_details', 'business_card', 'settings'));
        }
    }

    // Update Advanced settings
    public function updateAdvancedSetting(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            // Set password
            $password = $request->password;
            if ($request->password_protected == "on") {
                $password = null;
            }

            // Update
            BusinessCard::where('card_id', $id)->update([
                'password' => $password,
                'custom_css' => $request->custom_css,
                'custom_js' => $request->custom_js,
            ]);

            return redirect()->route('user.cards')->with('success', trans('Your virtual business card is updated!'));
        }
    }
}
