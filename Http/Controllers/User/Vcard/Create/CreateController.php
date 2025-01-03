<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\User;
use App\Theme;
use App\Setting;
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

    // Create Card
    public function CreateCard()
    {
        // Queries
        $themes = Theme::where('theme_description', 'vCard')->where('status', 1)->orderBy('id', 'asc')->get();
        $settings = Setting::where('status', 1)->first();
        $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();

        // Active plan details in user
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        $config = DB::table('config')->get();

        // Check unlimited cards
        if ($plan_details->no_of_vcards == 999) {
            $no_cards = 999999;
        } else {
            $no_cards = $plan_details->no_of_vcards;
        }

        // Chech vcard creation limit
        if ($cards < $no_cards) {
            return view('user.pages.cards.create-card', compact('themes', 'settings', 'plan_details', 'config'));
        } else {
            return redirect()->route('user.cards')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
        }
    }

    // Save card
    public function saveBusinessCard(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'theme_id' => 'required',
            'card_lang' => 'required',
            'logo' => 'required',
            'title' => 'required',
            'cover_type' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
        ]);

        // Validate alert
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
        $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();
        $user_details = User::where('user_id', Auth::user()->user_id)->first();
        $plan_details = json_decode($user_details->plan_details, true);

        // Card URL
        $card_url = strtolower(preg_replace('/\s+/', '-', $personalized_link));
        $current_card = BusinessCard::where('card_url', $card_url)->count();

        // Ger purchased plan details
        if ($plan_details['no_of_vcards'] == 999) {
            $no_cards = 999999;
        } else {
            $no_cards = $plan_details['no_of_vcards'];
        }

        // Check card URL is available
        if ($current_card == 0) {
            // Checking, If the user plan allowed card creation is less than created card.
            if ($cards < $no_cards) {
                try {

                    // Check banner image
                    $cover = null;
                    if ($request->has('cover')) {
                        $cover = $request->cover;
                    }

                    //Cover Type - Validation
                    $cover_type = "none"; // Default Value
                    if (in_array($request->cover_type, ["youtube", "youtube-ap", "vimeo", "vimeo-ap", "photo"], TRUE)) {
                        $cover_type = $request->cover_type;
                        // Cover URL no need to update for photo type.
                        if ($request->cover_type != "photo") {
                            $cover = $request->cover_url;
                        }
                    }

                    // Save
                    $card = new BusinessCard();
                    $card->card_id = $cardId;
                    $card->user_id = Auth::user()->user_id;
                    $card->type = $request->type;
                    $card->theme_id = $request->theme_id;
                    $card->card_lang = $request->card_lang;
                    $card->cover_type = $cover_type;
                    $card->cover = $cover;
                    $card->profile = $request->logo;
                    $card->card_url = $card_url;
                    $card->card_type = 'vcard';
                    $card->title = $request->title;
                    $card->sub_title = $request->subtitle;
                    $card->description = $request->description;
                    $card->save();

                    return redirect()->route('user.social.links', $cardId)->with('success', trans('New Business Card Created Successfully!'));
                } catch (\Exception $th) {
                    return redirect()->route('user.create.card')->with('failed', trans('Sorry, the personalized link was already registered.'));
                }
            } else {
                return redirect()->route('user.create.card')->with('failed', trans('Maximum card creation limit is exceeded, Please upgrade your plan to add more card(s).'));
            }
        } else {
            return redirect()->route('user.create.card')->with('failed', trans('Sorry, the personalized link was already registered.'));
        }
    }

    // Check unique card / store link
    public function checkLink(Request $request)
    {
        // Requested link
        $link = $request->link;
        $is_present = DB::table('business_cards')->where('card_url', $link)->count();
        $resp = [];
        $resp['status'] = 'failed';

        // Check
        if ($is_present == 0) {
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }

        return response()->json($resp);
    }

    // Cropping image
    public function vcardCroppedImage(Request $request)
    {
        $croppedImage = $request->file('croppedImage');

        // Generate a random unique name for the image
        $imageName = Str::random(20) . '.' . $croppedImage->extension();

        // Save cropped image to desired location
        $croppedImage->storeAs('public/profile-images', $imageName);

        // You can also save the path to the cropped image in the database if needed

        return response()->json(['success' => true, 'imageUrl' => "storage/profile-images/" . $imageName]);
    }
}
