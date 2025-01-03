<?php

namespace App\Http\Controllers\User\Vcard\Edit;

use App\Medias;
use App\Setting;
use App\Testimonial;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
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

    // Testimonials
    public function editTestimonials(Request $request, $id)
    {
        // Queries
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {

            // Queries
            $testimonials = Testimonial::where('card_id', $id)->get();
            $media = Medias::where('user_id', Auth::user()->user_id)->orderBy('id', 'desc')->get();
            $settings = Setting::where('status', 1)->first();

            return view('user.pages.edit-cards.edit-testimonial', compact('plan_details', 'testimonials', 'media', 'settings'));
        }
    }

    // Update Testimonials
    public function updateTestimonial(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {

            // Get plan details
            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check reviewer name
            if ($request->reviewer_name != null) {

                // Check Testimonials limit
                if (count($request->reviewer_name) <= $plan_details->no_testimonials) {

                    // Delete Previous Testimonials
                    Testimonial::where('card_id', $id)->delete();

                    // Check dynamic fields foreach
                    for ($i = 0; $i < count($request->reviewer_name); $i++) {

                        // Save
                        $testimonial = new Testimonial();
                        $testimonial->card_id = $id;
                        $testimonial->reviewer_name = $request->reviewer_name[$i];
                        $testimonial->reviewer_image = $request->reviewer_image[$i];
                        $testimonial->review_subtext = $request->review_subtext[$i];
                        $testimonial->review = $request->review[$i];
                        $testimonial->save();
                    }

                    // Check business hours is "ENABLED"
                    if ($plan_details->business_hours == 1) {
                        return redirect()->route('user.edit.business.hours', $id)->with('success', trans('Testimonials are updated.'));
                    } elseif ($plan_details->contact_form == 1) {
                        // Check contact form is "ENABLED"
                        return redirect()->route('user.edit.contact.form', $id)->with('success', trans('Testimonials are updated.'));
                    } elseif ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                        // Check contact form is "ENABLED"
                        return redirect()->route('user.edit.advanced.setting', $id)->with('success', trans('Testimonials are updated.'));
                    } else {
                        return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
                    }
                } else {
                    return redirect()->route('user.edit.testimonials', $id)->with('failed', trans('You have reached the plan limit!'));
                }
            } else {
                // Delete Previous Testimonials
                Testimonial::where('card_id', $id)->delete();

                // Check business hours is "ENABLED"
                if ($plan_details->business_hours == 1) {
                    return redirect()->route('user.edit.business.hours', $id)->with('success', trans('Testimonials are updated.'));
                } elseif ($plan_details->contact_form == 1) {
                    // Check contact form is "ENABLED"
                    return redirect()->route('user.edit.contact.form', $id)->with('success', trans('Testimonials are updated.'));
                } elseif ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                    // Check contact form is "ENABLED"
                    return redirect()->route('user.edit.advanced.setting', $id)->with('success', trans('Testimonials are updated.'));
                } else {
                    return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
                }
            }
        }
    }
}
