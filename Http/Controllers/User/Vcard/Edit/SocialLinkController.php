<?php

namespace App\Http\Controllers\User\Vcard\Edit;

use App\Setting;
use App\BusinessCard;
use App\BusinessField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SocialLinkController extends Controller
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

    // Social Links
    public function socialLinks(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            // Queries
            $features = BusinessField::where('card_id', $id)->orderBy('id', 'asc')->get();
            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details);
            $settings = Setting::where('status', 1)->first();

            return view('user.pages.edit-cards.edit-social-links', compact('plan_details', 'features', 'settings'));
        }
    }

    // Update social links
    public function updateSocialLinks(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            // Check icon
            if ($request->icon) {
                // Delete previous links
                BusinessField::where('card_id', $id)->delete();

                // Get plan details
                $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
                $plan_details = json_decode($plan->plan_details);

                // Check social links limit
                if (count($request->icon) <= $plan_details->no_of_links) {

                    // Check dynamic fields foreach
                    for ($i = 0; $i < count($request->icon); $i++) {

                        // Check dynamic fields
                        if (isset($request->type[$i]) && isset($request->icon[$i]) && isset($request->label[$i]) && isset($request->value[$i])) {

                            $customContent = $request->value[$i];

                            // Youtube
                            if ($request->type[$i] == 'youtube') {
                                $customContent = str_replace('https://www.youtube.com/watch?v=', '', $request->value[$i]);
                            }

                            // Google Map
                            if ($request->type[$i] == 'map') {
                                if (substr($request->value[$i], 0, 3) == 'pb=') {
                                    $customContent = $request->value[$i];
                                } else {
                                    $customContent = str_replace('<iframe src="', '', $request->value[$i]);
                                    $customContent = substr($customContent, 0, strpos($customContent, '" '));
                                    $customContent = str_replace('https://www.google.com/maps/embed?', '', $customContent);
                                }
                            }

                            // Save
                            $field = new BusinessField();
                            $field->card_id = $id;
                            $field->type = $request->type[$i];
                            $field->icon = $request->icon[$i];
                            $field->label = $request->label[$i];
                            $field->content = $customContent;
                            $field->position = $i + 1;
                            $field->save();
                        } else {
                            return redirect()->route('user.edit.social.links', $id)->with('failed', trans('Please add at least one bio link.'));
                        }
                    }

                    // Check type
                    if ($business_card->type == "personal") {
                        return redirect()->route('user.cards')->with('success', trans('Bio links are updated.'));
                    } else {
                        return redirect()->route('user.edit.payment.links', $id)->with('success', trans('Bio links are updated.'));
                    }
                } else {
                    return redirect()->route('user.edit.social.links', $id)->with('failed', trans('The maximum limit was exceeded'));
                }
            } else {
                return redirect()->route('user.edit.social.links', $id)->with('failed', trans('Please add at least one bio link.'));
            }
        }
    }
}
