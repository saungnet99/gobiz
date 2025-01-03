<?php

namespace App\Http\Controllers\User\Store\Edit;

use App\Plan;
use App\Theme;
use App\Setting;
use App\Currency;
use App\BusinessCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UpdateController extends Controller
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

    // Edit Store
    public function editStore(Request $request, $id)
    {
        // Queries
        $themes = Theme::where('theme_description', 'WhatsApp Store')->where('status', 1)->orderBy('id', 'asc')->get();
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.stores')->with('failed', trans('Store not found!'));
        } else {

            // Check card type is "STORE"
            if ($business_card->card_type == "store") {

                // Queries
                $settings = Setting::where('status', 1)->first();
                $currencies = Currency::get();
                $plan_details = Plan::where('plan_id', Auth::user()->plan_id)->first();
                $store_details = json_decode($business_card->description);

                return view('user.pages.edit-store.edit-store', compact('themes', 'business_card', 'settings', 'plan_details', 'store_details', 'currencies'));
            } else {
                return redirect()->route('user.edit.card', $id);
            }
        }
    }

    // Update store
    public function updateStore(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.stores')->with('failed', trans('Store not found!'));
        } else {

            // Check personalize links
            if ($request->link) {
                $personalized_link = $request->link;
            } else {
                $personalized_link = $id;
            }

            // Not upload store banner and logo
            if ($request->banner == null && $request->logo == null) {

                // Generate json for store details
                $store_details = [];

                $store_details['whatsapp_no'] = $request->country_code."".$request->whatsapp_no;
                $store_details['whatsapp_msg'] = $request->whatsapp_msg;
                $store_details['currency'] = $request->currency;

                // Update
                BusinessCard::where('card_id', $id)->update([
                    'theme_id' => $request->theme_id,
                    'card_lang' => $request->card_lang,
                    'card_url' => $personalized_link,
                    'title' => $request->title,
                    'sub_title' => $request->subtitle,
                    'description' => $store_details,
                ]);

                return redirect()->route('user.edit.products', $id)->with('success', trans('Store details updated'));
            } else if ($request->logo == null) {

                // Validate
                $validator = Validator::make($request->all(), [
                    'banner' => 'required',
                ]);

                // Validate alert
                if ($validator->fails()) {
                    return back()->with('failed', $validator->messages()->all()[0])->withInput();
                }

                // Generate json for store details
                $store_details = [];

                $store_details['whatsapp_no'] = $request->country_code."".$request->whatsapp_no;
                $store_details['whatsapp_msg'] = $request->whatsapp_msg;
                $store_details['currency'] = $request->currency;

                // Update banner images
                $arrayBanners = explode(",", $request->banner);
                $banner = [];
                for ($i = 0; $i < count($arrayBanners); $i++) {
                    $banner[$i] = $arrayBanners[$i];
                }

                // Update
                BusinessCard::where('card_id', $id)->update([
                    'cover' => json_encode($banner),
                    'theme_id' => $request->theme_id,
                    'card_lang' => $request->card_lang,
                    'card_url' => $personalized_link,
                    'title' => $request->title,
                    'sub_title' => $request->subtitle,
                    'description' => $store_details,
                ]);

                return redirect()->route('user.edit.products', $id)->with('success', trans('Store details updated'));
            } else if ($request->banner == null) {

                // Validate
                $validator = Validator::make($request->all(), [
                    'logo' => 'required',
                ]);

                // Validate alert
                if ($validator->fails()) {
                    return back()->with('failed', $validator->messages()->all()[0])->withInput();
                }

                // Generate json for store details
                $store_details = [];

                $store_details['whatsapp_no'] = $request->country_code."".$request->whatsapp_no;
                $store_details['whatsapp_msg'] = $request->whatsapp_msg;
                $store_details['currency'] = $request->currency;

                // Upload store logo image
                $logo = $request->logo;

                // Update
                BusinessCard::where('card_id', $id)->update([
                    'profile' => $logo,
                    'theme_id' => $request->theme_id,
                    'card_lang' => $request->card_lang,
                    'card_url' => $personalized_link,
                    'title' => $request->title,
                    'sub_title' => $request->subtitle,
                    'description' => $store_details,
                ]);

                return redirect()->route('user.edit.products', $id)->with('success', trans('Store details updated'));
            } else if ($request->banner != null && $request->logo != null) {
                // Validate
                $validator = Validator::make($request->all(), [
                    'banner' => 'required',
                    'logo' => 'required',
                ]);

                // Validate alert
                if ($validator->fails()) {
                    return back()->with('failed', $validator->messages()->all()[0])->withInput();
                }

                // Generate json for store details
                $store_details = [];

                $store_details['whatsapp_no'] = $request->country_code."".$request->whatsapp_no;
                $store_details['whatsapp_msg'] = $request->whatsapp_msg;
                $store_details['currency'] = $request->currency;

                // Upload store logo
                $logo = $request->logo;
                $arrayBanners = explode(",", $request->banner);

                // Upload store banner
                $banner = [];
                for ($i = 0; $i < count($arrayBanners); $i++) {
                    $banner[$i] = $arrayBanners[$i];
                }

                // Update
                BusinessCard::where('card_id', $id)->update([
                    'profile' => $logo,
                    'cover' => json_encode($banner),
                    'theme_id' => $request->theme_id,
                    'card_lang' => $request->card_lang,
                    'card_url' => $personalized_link,
                    'title' => $request->title,
                    'sub_title' => $request->subtitle,
                    'description' => $store_details,
                ]);

                return redirect()->route('user.edit.products', $id)->with('success', trans('Store details updated'));
            }
        }
    }
}
