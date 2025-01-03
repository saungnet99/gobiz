<?php

namespace App\Http\Controllers\User;

use App\Setting;
use App\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
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

    // Enquiries
    public function index(Request $request, $id)
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        // Get plan details
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Enquiries
        $businessEnquries = ContactForm::where('card_id', $id)->limit($plan_details->no_of_enquires)->orderBy('id', 'desc')->get();

        return view('user.pages.cards.enquiries', compact('businessEnquries', 'settings', 'plan_details'));
    }
}
