<?php

namespace App\Http\Controllers\User\Vcard\Edit;

use App\Setting;
use App\BusinessCard;
use Illuminate\Support\Str;
use App\CardAppointmentTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
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

    // Edit Appointment
    public function editAppointment(Request $request, $id)
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
            // Get appointment timings
            $appointmentSlots = CardAppointmentTime::where('card_id', $id)->get();

            // Check appointmentSlots is empty
            if ($appointmentSlots) {
                // Initialize the time slots array
                $time_slots = [
                    'monday' => [],
                    'tuesday' => [],
                    'wednesday' => [],
                    'thursday' => [],
                    'friday' => [],
                    'saturday' => [],
                    'sunday' => [],
                ];

                // Iterate through the appointment slots and categorize them by day
                foreach ($appointmentSlots as $slot) {
                    // Assuming your `CardAppointmentTime` model has a `day` attribute and a `time` attribute
                    $day = strtolower($slot->day); // Convert to lowercase to match array keys
                    $time = $slot->time_slots; // Assuming this contains the time range string like "16:00 - 17:00"

                    // Check if the day exists in the time_slots array
                    if (array_key_exists($day, $time_slots)) {
                        $time_slots[$day][] = $time; // Add the time to the appropriate day
                    }
                }

                $time_slots = json_encode($time_slots); // Convert the array to JSON

                return view('user.pages.edit-cards.edit-appointment', compact('appointmentSlots', 'time_slots', 'plan_details', 'business_card', 'settings'));
            }
        }
    }

    // Update Appointment
    public function updateAppointment(Request $request, $id)
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

            // Redirect based on plan details
            if ($plan_details->contact_form == 1) {
                return redirect()->route('user.edit.contact.form', $id)->with('success', trans('Appointment details are updated.'));
            } elseif ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                return redirect()->route('user.edit.advanced.setting', $id)->with('success', trans('Appointment details are updated.'));
            } else {
                return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
            }
        }
    }
}
