<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\Medias;
use App\Service;
use App\Setting;
use App\BusinessCard;
use App\BusinessField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
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

    // Services
    public function services()
    {
        // Queries
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);
        $media = Medias::where('user_id', Auth::user()->user_id)->orderBy('id', 'desc')->get();
        $settings = Setting::where('status', 1)->first();

        // Check whatsapp number exists89
        $whatsAppNumberExists = BusinessField::where('card_id', request()->segment(3))->where('type', 'wa')->exists();

        return view('user.pages.cards.services', compact('plan_details', 'settings', 'media', 'whatsAppNumberExists'));
    }

    // Save services
    public function saveServices(Request $request, $id)
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

            // Check dynamic fields
            if ($request->service_name != null) {

                // Check services limit
                if (count($request->service_name) <= $plan_details->no_of_services) {

                    // Check dynamic fields foreach
                    for ($i = 0; $i < count($request->service_name); $i++) {

                        // Save
                        $service = new Service();
                        $service->card_id = $id;
                        $service->service_name = $request->service_name[$i];
                        $service->service_image = $request->service_image[$i];
                        $service->service_description = $request->service_description[$i];
                        $service->enable_enquiry = isset($request->enquiry) ? $request->enquiry[$i] : "Disabled";
                        $service->save();
                    }
                    return redirect()->route('user.vproducts', $id)->with('success', trans('Service details are updated.'));
                } else {
                    return redirect()->route('user.services', $id)->with('failed', trans('You have reached the plan limit!'));
                }
            } else {
                //Skipping...
                return redirect()->route('user.vproducts', $id)->with('success', trans('Service details are updated.'));
            }
        }
    }
}
