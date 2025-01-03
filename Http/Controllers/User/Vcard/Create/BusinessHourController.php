<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\Setting;
use App\BusinessCard;
use App\BusinessHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BusinessHourController extends Controller
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

    // Business Hours
    public function businessHours()
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.cards.business-hours', compact('settings'));
    }

    // Save business hours
    public function saveBusinessHours(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Get plan details
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {

            // Delete saved business hours
            BusinessHour::where('card_id', $id)->delete();

            if ($request->always_open == "on") {
                $always_open = "Opening";
            } else {
                $always_open = "Closed";
            }

            if ($request->is_display == "on") {
                $is_display = 0;
            } else {
                $is_display = 1;
            }

            if ($is_display == 0) {
                $monday = "-";
                $tuesday = "-";
                $wednesday = "-";
                $thursday = "-";
                $friday = "-";
                $saturday = "-";
                $sunday = "-";
                $always_open = "Closed";
            } else {
                // Check closed timing
                if ($request->monday_closed == "on") {
                    $monday = "Closed";
                } else {
                    $monday = $request->monday_open . "-" . $request->monday_closing;
                }

                if ($request->tuesday_closed == "on") {
                    $tuesday = "Closed";
                } else {
                    $tuesday = $request->tuesday_open . "-" . $request->tuesday_closing;
                }

                if ($request->wednesday_closed == "on") {
                    $wednesday = "Closed";
                } else {
                    $wednesday = $request->wednesday_open . "-" . $request->wednesday_closing;
                }

                if ($request->thursday_closed == "on") {
                    $thursday = "Closed";
                } else {
                    $thursday = $request->thursday_open . "-" . $request->thursday_closing;
                }

                if ($request->friday_closed == "on") {
                    $friday = "Closed";
                } else {
                    $friday = $request->friday_open . "-" . $request->friday_closing;
                }

                if ($request->saturday_closed == "on") {
                    $saturday = "Closed";
                } else {
                    $saturday = $request->saturday_open . "-" . $request->saturday_closing;
                }

                if ($request->sunday_closed == "on") {
                    $sunday = "Closed";
                } else {
                    $sunday = $request->sunday_open . "-" . $request->sunday_closing;
                }
            }

            // Save
            $businessHours = new BusinessHour();
            $businessHours->card_id = $id;
            $businessHours->Monday = $monday;
            $businessHours->Tuesday = $tuesday;
            $businessHours->Wednesday = $wednesday;
            $businessHours->Thursday = $thursday;
            $businessHours->Friday = $friday;
            $businessHours->Saturday = $saturday;
            $businessHours->Sunday = $sunday;
            $businessHours->is_always_open = $always_open;
            $businessHours->is_display = $is_display;
            $businessHours->save();

            // Check contact form is "ENABLED"
            if ($plan_details->appointment == 1) {
                return redirect()->route('user.appointment', $id)->with('success', trans('Business hours are updated.'));
            } elseif ($plan_details->contact_form == 1) {
                return redirect()->route('user.contact.form', $id)->with('success', trans('Business hours are updated.'));
            } elseif ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                // Check contact form is "ENABLED"
                return redirect()->route('user.advanced.setting', $id)->with('success', trans('Business hours are updated.'));
            } else {
                return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
            }
        }
    }
}
