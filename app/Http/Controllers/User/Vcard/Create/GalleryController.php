<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\Medias;
use App\Gallery;
use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
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

    // Galleries
    public function galleries()
    {
        // Queries
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);
        $media = Medias::where('user_id', Auth::user()->user_id)->orderBy('id', 'desc')->get();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.cards.galleries', compact('plan_details', 'media', 'settings'));
    }

    // Save Gallery Images
    public function saveGalleries(Request $request, $id)
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

            // Check gallery caption
            if ($request->caption != null) {

                // Check gallery limit
                if (count($request->caption) <= $plan_details->no_of_galleries) {

                    // Delete previous gallery images
                    Gallery::where('card_id', $id)->delete();

                    // Check dynamic fields foreach
                    for ($i = 0; $i < count($request->caption); $i++) {

                        // Save
                        $gallery = new Gallery();
                        $gallery->card_id = $id;
                        $gallery->caption = $request->caption[$i];
                        $gallery->gallery_image = $request->gallery_image[$i];
                        $gallery->save();
                    }

                    return redirect()->route('user.testimonials', $id)->with('success', trans('Gallery images are updated.'));
                } else {

                    return redirect()->route('user.galleries', $id)->with('failed', trans('You have reached the plan limit!'));
                }
            } else {
                return redirect()->route('user.testimonials', $id)->with('success', trans('Gallery images are updated.'));
            }
        }
    }
}
