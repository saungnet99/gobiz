<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ContactFormController extends Controller
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

    // Enquiry form
    public function contactForm()
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.cards.contact', compact('settings'));
    }

    // Update contact form
    public function saveContactForm(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {

            // Check contact form is "enabled"
            if ($request->contact_form == "on") {
                $receiveEmail = null;
            } else {
                $receiveEmail = $request->receive_email;
            }
            // Update Enquiy Email
            BusinessCard::where('card_id', $id)->update([
                'enquiry_email' => $receiveEmail,
            ]);

            if ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                // Check contact form is "ENABLED"
                return redirect()->route('user.advanced.setting', $id)->with('success', trans('Contact form updated.'));
            } else {
                return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
            }
        }
    }
}
