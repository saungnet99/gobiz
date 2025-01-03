<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\Setting;
use App\BusinessCard;
use App\CardAppointmentTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
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

    // Appointment
    public function Appointment(Request $request, $id)
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
            return view('user.pages.cards.appointment', compact('plan_details', 'business_card', 'settings'));
        }
    }

    // Save appointment
    public function saveAppointment(Request $request, $id)
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

            // Save appointment in $request->time_slots
            if (isset($request->time_slots)) {
                // Delete saved appointment
                CardAppointmentTime::where('card_id', $id)->delete();

                foreach ($request->time_slots as $slotIndex => $slot) {
                    // Save
                    $saveAppointment = new CardAppointmentTime();
                    $saveAppointment->card_appointment_time_id = uniqid();
                    $saveAppointment->card_id = $business_card->card_id; // Adjust based on your logic.
                    $saveAppointment->day = strtolower($slotIndex);
                    $saveAppointment->slot_duration = $request->slot_duration;
                    $saveAppointment->time_slots = json_encode($slot);
                    $saveAppointment->price = $request->price;
                    $saveAppointment->save();
                }
            }

            // Page redirect
            if ($plan_details->contact_form == 1) {
                // Contact form is "ENABLED"
                return redirect()->route('user.contact.form', $id)->with('success', trans('Appointment details are updated.'));
            } elseif ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                // Check contact form is "ENABLED"
                return redirect()->route('user.advanced.setting', $id)->with('success', trans('Appointment details updated.'));
            } else {
                // Redirect to cards
                return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
            }
        }
    }
}
