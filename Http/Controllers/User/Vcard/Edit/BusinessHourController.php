<?php

namespace App\Http\Controllers\User\Vcard\Edit;

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
    public function businessHours(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();
        $plan = DB::table('users')
            ->where('user_id', Auth::user()->user_id)
            ->where('status', 1)
            ->first();
        $plan_details = json_decode($plan->plan_details);
        $settings = Setting::where('status', 1)->first();

        // Check business card
        if (!$business_card) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        }

        // Default business hours
        $default_hours = [
            'monday' => ['open' => "09:00", 'close' => "18:30", 'status' => "Opening"],
            'tuesday' => ['open' => "09:00", 'close' => "18:30", 'status' => "Opening"],
            'wednesday' => ['open' => "09:00", 'close' => "18:30", 'status' => "Opening"],
            'thursday' => ['open' => "09:00", 'close' => "18:30", 'status' => "Opening"],
            'friday' => ['open' => "09:00", 'close' => "18:30", 'status' => "Opening"],
            'saturday' => ['open' => "09:00", 'close' => "18:30", 'status' => "Opening"],
            'sunday' => ['open' => "09:00", 'close' => "18:30", 'status' => "Opening"]
        ];

        // Fetch business hours
        $businessHour = BusinessHour::where('card_id', $id)->first();

        foreach ($default_hours as $day => $hours) {
            // Check if $businessHour exists and $day is set
            if ($businessHour && isset($businessHour->$day)) {
                if ($businessHour->$day != 'Closed') {
                    list($hours['open'], $hours['close']) = explode("-", $businessHour->$day);
                } else {
                    $hours['status'] = "Closed";
                }
            }

            // Assign values to business_hrs
            $business_hrs["{$day}_open"] = $hours['open'];
            $business_hrs["{$day}_close"] = $hours['close'];
            $business_hrs["{$day}_status"] = $hours['status'];
        }

        if ($businessHour !== null) {
            $business_hrs['alwaysOpen'] = $businessHour->is_always_open != "Closed" ? "Opening" : "Closed";
            $business_hrs['isDisplay'] = $businessHour->is_display != "1" ? 0 : 1;
        } else{
            $business_hrs['alwaysOpen'] = "Closed";
            $business_hrs['isDisplay'] = 1;
        }

        return view('user.pages.edit-cards.edit-business-hours', compact('plan_details', 'businessHour', 'business_hrs', 'settings'));
    }

    // Update business hours
    public function updateBusinessHours(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        }

        // Delete previous business hours
        BusinessHour::where('card_id', $id)->delete();

        // Days of the week
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        // Initialize business hours array
        $businessHoursData = [
            'card_id' => $id,
            'is_always_open' => $request->always_open == "on" ? "Opening" : "Closed",
            'is_display' => $request->is_display == "on" ? 0 : 1,
        ];

        // Check closed timing for each day
        foreach ($days as $day) {
            if ($request->input($day . '_closed') == "on") {
                $businessHoursData[$day] = "Closed";
            } else {
                $businessHoursData[$day] = $request->input($day . '_open') . "-" . $request->input($day . '_closing');
            }
        }

        // Save business hours
        BusinessHour::create($businessHoursData);

        // Queries (vCards and Stores)
        $userId = Auth::user()->user_id;
        $activeCards = BusinessCard::where('user_id', $userId)
            ->where('card_type', 'vcard')
            ->where('card_status', 'activated')
            ->count();

        $activeStores = BusinessCard::where('user_id', $userId)
            ->where('card_type', 'store')
            ->where('card_status', 'activated')
            ->count();

        // Check business card
        $plan = DB::table('users')->where('user_id', $userId)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Number of cards
        if ($activeCards < $plan_details->no_of_vcards) {
            // Update card status
            BusinessCard::where('user_id', $userId)
                ->where('card_type', 'vcard')
                ->where('card_id', $id)
                ->update(['card_status' => 'activated']);

            // Number of stores limitation
            if ($activeStores < $plan_details->no_of_stores) {
                // Update (Stores)
                BusinessCard::where('user_id', $userId)
                    ->where('card_type', 'store')
                    ->where('card_id', $id)
                    ->update(['card_status' => 'activated']);

                // Redirect based on plan details
                if ($plan_details->appointment == 1) {
                    return redirect()->route('user.edit.appointment', $id)->with('success', trans('Business hours are updated.'));
                } elseif ($plan_details->contact_form == 1) {
                    return redirect()->route('user.edit.contact.form', $id)->with('success', trans('Business hours are updated.'));
                } elseif ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                    return redirect()->route('user.edit.advanced.setting', $id)->with('success', trans('Business hours are updated.'));
                } else {
                    return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
                }
            } else {
                return redirect()->route('user.cards')->with('failed', trans('Unable to activate. Please check your plan details.'));
            }
        }

        // Redirect based on plan details
        if ($plan_details->appointment == 1) {
            return redirect()->route('user.edit.appointment', $id)->with('success', trans('Business hours are updated.'));
        } elseif ($plan_details->contact_form == 1) {
            return redirect()->route('user.edit.contact.form', $id)->with('success', trans('Business hours are updated.'));
        } elseif ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
            return redirect()->route('user.edit.advanced.setting', $id)->with('success', trans('Business hours are updated.'));
        } else {
            return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
        }
    }
}
