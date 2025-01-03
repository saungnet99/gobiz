<?php

namespace App\Http\Controllers\User\Vcard\Edit;

use App\Payment;
use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentLinkController extends Controller
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
    
    // Payment links
    public function paymentLinks(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            // Queries
            $payments = Payment::where('card_id', $id)->orderBy('position', 'asc')->get();
            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details);
            $settings = Setting::where('status', 1)->first();

            return view('user.pages.edit-cards.edit-payment-links', compact('payments', 'plan_details', 'settings'));
        }
    }

    // Update payments links
    public function updatePaymentLinks(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {

            // Check icon
            if ($request->icon != null) {

                // Delete previous payments links
                Payment::where('card_id', $id)->delete();

                // Get plan details
                $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
                $plan_details = json_decode($plan->plan_details);

                // Check payment links limit
                if (count($request->icon) <= $plan_details->no_of_payments) {

                    // Check dynamic fields foreach
                    for ($i = 0; $i < count($request->icon); $i++) {

                        // Check dynamic fields
                        if (isset($request->icon[$i]) && isset($request->label[$i]) && isset($request->value[$i])) {

                            // Save
                            $payment = new Payment();
                            $payment->card_id = $id;
                            $payment->type = $request->type[$i];
                            $payment->icon = $request->icon[$i];
                            $payment->label = $request->label[$i];
                            $payment->content = $request->value[$i];
                            $payment->position = $i + 1;
                            $payment->save();
                        } else {
                            Payment::where('card_id', $id)->delete();
                            return redirect()->route('user.edit.payment.links', $id)->with('failed', trans('Please fill out all required fields.'));
                        }
                    }
                    return redirect()->route('user.edit.services', $id)->with('success', trans('Payment links are updated.'));
                } else {
                    return redirect()->route('user.edit.payment.links', $id)->with('failed', trans('Maximum links limit exceeded.'));
                }
            } else {
                Payment::where('card_id', $id)->delete();
                return redirect()->route('user.edit.services', $id)->with('success', trans('Payment links are updated.'));
            }
        }
    }
}
