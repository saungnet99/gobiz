<?php

namespace App\Http\Controllers\User\Store\Create;

use App\User;
use App\Theme;
use App\Setting;
use App\Currency;
use Carbon\Carbon;
use App\BusinessCard;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateController extends Controller
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

    // Create Store
    public function CreateStore()
    {
        // Queries
        $themes = Theme::where('theme_description', 'WhatsApp Store')->where('status', 1)->orderBy('id', 'asc')->get();
        $settings = Setting::where('status', 1)->first();
        $stores = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'store')->where('card_status', 'activated')->count();

        // Get plan details
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $currencies = Currency::get();
        $plan_details = json_decode($plan->plan_details);

        // Check validity
        $validity = User::where('user_id', Auth::user()->user_id)->whereDate('plan_validity', '>=', Carbon::now())->count();

        // Get number of stores
        if ($plan_details->no_of_stores == 999) {
            $no_of_stores = 999999;
        } else {
            $no_of_stores = $plan_details->no_of_stores;
        }

        // Check number of stores
        if ($validity == 1) {
            if ($stores < $no_of_stores) {
                return view('user.pages.store.create-store', compact('themes', 'settings', 'plan_details', 'currencies'));
            } else {
                return redirect()->route('user.stores')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
            }
        } else {
            // Redirect
            return redirect()->route('user.stores')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
        }
    }

    // Save Store
    public function saveStore(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'theme_id' => 'required',
            'card_lang' => 'required',
            'banner' => 'required',
            'logo' => 'required',
            'title' => 'required',
            'currency' => 'required',
            'subtitle' => 'required',
            'country_code' => 'required',
            'whatsapp_no' => 'required',
            'whatsapp_msg' => 'required',
        ]);

        // Validate alert message
        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Unique card ID (personalized_link)
        $cardId = uniqid();

        if ($request->link) {
            $personalized_link = $request->link;
        } else {
            $personalized_link = $cardId;
        }

        // Queries
        $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'store')->where('card_status', 'activated')->count();
        $user_details = User::where('user_id', Auth::user()->user_id)->first();
        $plan_details = json_decode($user_details->plan_details, true);

        // Upload store logo
        $logo = $request->logo;
        $arrayBanners = explode(",", $request->banner);

        // Upload store banner
        $banner = [];
        for ($i = 0; $i < count($arrayBanners); $i++) {
            $banner[$i] = $arrayBanners[$i];
        }

        // Store details
        $store_details = [];

        $store_details['whatsapp_no'] = $request->country_code."".$request->whatsapp_no;
        $store_details['whatsapp_msg'] = $request->whatsapp_msg;
        $store_details['currency'] = $request->currency;

        // Unique Store URL
        $card_url = strtolower(preg_replace('/\s+/', '-', $personalized_link));

        // Get current store count
        $current_card = BusinessCard::where('card_url', $card_url)->count();

        // Get store count
        if ($plan_details['no_of_stores'] == 999) {
            $no_of_stores = 999999;
        } else {
            $no_of_stores = $plan_details['no_of_stores'];
        }

        // Check persionalize link
        if ($current_card == 0) {

            // Checking, If the user plan allowed card creation is less than created card.
            if ($cards < $no_of_stores) {
                try {

                    // Card ID
                    $card_id = $cardId;

                    // Save
                    $card = new BusinessCard();
                    $card->card_id = $card_id;
                    $card->user_id = Auth::user()->user_id;
                    $card->theme_id = $request->theme_id;
                    $card->card_lang = $request->card_lang;
                    $card->cover = json_encode($banner);
                    $card->profile = $logo;
                    $card->card_url = strtolower(preg_replace('/\s+/', '-', $personalized_link));
                    $card->card_type = 'store';
                    $card->title = $request->title;
                    $card->sub_title = $request->subtitle;
                    $card->description = json_encode($store_details);
                    $card->save();

                    return redirect()->route('user.products', $card_id)->with('success', trans('New WhatsApp Store Created Successfully!'));
                } catch (\Exception $th) {

                    // Alert (Personalized link was already registered)
                    return redirect()->route('user.create.store')->with('failed', trans('Sorry, the personalized link was already registered.'));
                }
            } else {

                // Alert (Maximum store creation limit is exceeded,)
                return redirect()->route('user.create.store')->with('failed', trans('Maximum store creation limit is exceeded, Please upgrade your plan to add more store(s).'));
            }
        } else {

            // Alert (Personalized link was already registered)
            return redirect()->route('user.create.store')->with('failed', trans('Sorry, the personalized link was already registered.'));
        }
    }

    public function storeCroppedImage(Request $request)
    {
        $croppedImage = $request->file('croppedImage');
        $imageName = Str::random(20) . '.' . $croppedImage->getClientOriginalExtension();
        $croppedImage->move(storage_path('app/public/store/images'), $imageName);
        $imageUrl = url('storage/store/images/' . $imageName);

        return response()->json(['success' => true, 'imageUrl' => $imageUrl]);
    }
}
