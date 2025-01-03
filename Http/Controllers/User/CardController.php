<?php

namespace App\Http\Controllers\User;

use App\Plan;
use App\User;
use App\Theme;
use App\Gallery;
use App\Payment;
use App\Service;
use App\Setting;
use App\Category;
use Carbon\Carbon;
use App\ContactForm;
use App\BusinessCard;
use App\BusinessHour;
use App\StoreProduct;
use App\VcardProduct;
use App\BusinessField;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
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

    // All user cards
    public function index(Request $request)
    {
        // Queries
        $active_plan = Plan::where('plan_id', Auth::user()->plan_id)->first();
        $plan = User::where('user_id', Auth::user()->user_id)->first();
        $active_plan = json_decode($plan->plan_details);

        if ($active_plan != null) {
            if ($request->ajax()) {
                $businessCards = DB::table('business_cards')
                    ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                    ->select('users.user_id', 'users.plan_validity', 'business_cards.*')
                    ->where('business_cards.user_id', Auth::user()->user_id)
                    ->where('business_cards.card_type', 'vcard')
                    ->where('business_cards.status', 1)
                    ->where('business_cards.card_status', '!=', 'deleted')
                    ->orderBy('business_cards.id', 'desc')
                    ->get();

                return DataTables::of($businessCards)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($card) {
                        return Carbon::parse($card->created_at)->format('M jS, Y g:i A');
                    })
                    ->editColumn('title', function ($card) {
                        return '<div class="d-flex py-1 align-items-center">
                                    <span class="avatar me-2" style="background-image: url(' . asset($card->profile) . ')"></span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium"><a href="' . route('user.edit.card', $card->card_id) . '" class="text-reset">' . $card->title . '</a></div>
                                        <div class="text-secondary">' . $card->sub_title . '</div>
                                    </div>
                                </div>';
                    })
                    ->editColumn('card_status', function ($card) {
                        return $card->card_status == 'inactive'
                            ? '<span class="badge bg-red text-white text-white">' . __('Disabled') . '</span>'
                            : '<span class="badge bg-green text-white text-white">' . __('Enabled') . '</span>';
                    })
                    ->addColumn('action', function ($card) {
                        $config = DB::table('config')->get();

                        // Preview
                        $preview = '<a class="btn btn-primary btn-icon" href="' . route('user.view.preview', $card->card_id) . '" target="_blank" title="' . trans('Preview') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </a>';

                        // Live
                        $live = $config[46]->config_value == '1'
                            ? '<a class="btn btn-primary btn-icon" href="' . route('subdomain.profile', $card->card_url) . '" target="_blank" title="' . trans('Live') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-world-www"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 7a9 9 0 0 0 -7.5 -4a8.991 8.991 0 0 0 -7.484 4" /><path d="M11.5 3a16.989 16.989 0 0 0 -1.826 4" /><path d="M12.5 3a16.989 16.989 0 0 1 1.828 4" /><path d="M19.5 17a9 9 0 0 1 -7.5 4a8.991 8.991 0 0 1 -7.484 -4" /><path d="M11.5 21a16.989 16.989 0 0 1 -1.826 -4" /><path d="M12.5 21a16.989 16.989 0 0 0 1.828 -4" /><path d="M2 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M17 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M9.5 10l1 4l1.5 -4l1.5 4l1 -4" /></svg>
                                    </a>'
                            : '<a class="btn btn-primary btn-icon" href="' . route('profile', $card->card_url) . '" target="_blank" title="' . trans('Live') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-world-www"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 7a9 9 0 0 0 -7.5 -4a8.991 8.991 0 0 0 -7.484 4" /><path d="M11.5 3a16.989 16.989 0 0 0 -1.826 4" /><path d="M12.5 3a16.989 16.989 0 0 1 1.828 4" /><path d="M19.5 17a9 9 0 0 1 -7.5 4a8.991 8.991 0 0 1 -7.484 -4" /><path d="M11.5 21a16.989 16.989 0 0 1 -1.826 -4" /><path d="M12.5 21a16.989 16.989 0 0 0 1.828 -4" /><path d="M2 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M17 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M9.5 10l1 4l1.5 -4l1.5 4l1 -4" /></svg>
                                    </a>';

                        // QR
                        $qr = $config[46]->config_value == '1'
                            ? '<a class="btn btn-primary btn-icon open-qr" onclick="updateQr(`' . route('subdomain.profile', $card->card_url) . '`)" title="' . trans('QR Code') . '">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 17l0 .01" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 7l0 .01" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M17 7l0 .01" /><path d="M14 14l3 0" /><path d="M20 14l0 .01" /><path d="M14 14l0 3" /><path d="M14 20l3 0" /><path d="M17 17l3 0" /><path d="M20 17l0 3" /></svg>
                                </a>'
                            : '<a class="btn btn-primary btn-icon open-qr" onclick="updateQr(`' . route('profile', $card->card_url) . '`)" title="' . trans('QR Code') . '">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 17l0 .01" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 7l0 .01" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M17 7l0 .01" /><path d="M14 14l3 0" /><path d="M20 14l0 .01" /><path d="M14 14l0 3" /><path d="M14 20l3 0" /><path d="M17 17l3 0" /><path d="M20 17l0 3" /></svg>
                                </a>';

                        // Analytics
                        $analytics = '<a class="btn btn-primary btn-icon" href="' . route('user.visitors', $card->card_url) . '" title="' . trans('Visitors') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20h14" /></svg>
                                    </a>';

                        // Enquiries
                        $enquiries =  '<a class="btn btn-primary btn-icon" href="' . route('user.enquiries', $card->card_id) . '" title="' . trans('Enquiries') . '">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-mail"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                        </a>';

                        // Appointments
                        $appointments =  '<a class="btn btn-primary btn-icon" href="' . route('user.appointments', $card->card_id) . '" title="' . trans('Appointments') . '">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-clock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.5 21h-4.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h10" /><path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M18 16.5v1.5l.5 .5" /></svg>
                                        </a>';

                        // Edit
                        $edit = '<a class="btn btn-primary btn-icon" href="' . route('user.edit.card', $card->card_id) . '" title="' . trans('Edit') . '">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                            </a>';

                        // Duplicate
                        $duplicate = '<a class="btn btn-primary btn-icon" onclick="duplicateCard(`' . $card->card_id . '`, `vcard`); return false;" title="' . trans('Duplicate') . '">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-copy-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path stroke="none" d="M0 0h24v24H0z" /><path d="M7 9.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" /><path d="M4.012 16.737a2 2 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" /><path d="M11 14h6" /><path d="M14 11v6" /></svg>
                            </a>';

                        // More actions
                        $actionBtn = '';

                        // Disable / Enable card
                        $actionBtn .= $card->card_status == 'activated'
                            ? '<a class="open-model dropdown-item" data-id="' . $card->card_id . '" href="#openModel">' . __('Disable') . '</a>'
                            : '<a class="open-model dropdown-item" data-id="' . $card->card_id . '" href="#openModel">' . __('Enable') . '</a>';

                        // Delete
                        $actionBtn .= '<a class="dropdown-item" onclick="deleteCard(`' . $card->card_id . '`, `delete`); return false;">' . __('Delete') . '</a>';

                        return '
                            <div class="btn-list flex-nowrap">
                                ' . $preview . '
                                ' . $live . '
                                ' . $edit . '
                                ' . $duplicate . '
                                ' . $qr . '
                                ' . $analytics . '
                                ' . $enquiries . '
                                ' . $appointments . '
                                <div class="dropdown">
                                    <button class="btn btn-icon small-btn align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                    </button>
                                    <div class="actions actions dropdown-menu dropdown-menu-end" style="">' . $actionBtn . '</div>
                                </div>
                            </div>';
                    })
                    ->rawColumns(['title', 'card_status', 'action'])
                    ->make(true);
            }

            $config = DB::table('config')->get();
            $settings = Setting::where('status', 1)->first();

            return view('user.pages.cards.cards', compact('settings', 'config'));
        } else {
            return redirect()->route('user.plans');
        }
    }

    // Choose a card type
    public function chooseCardType()
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();

        // Active plan details in user
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check validity
        $validity = User::where('user_id', Auth::user()->user_id)->whereDate('plan_validity', '>=', Carbon::now())->count();

        // Check unlimited cards
        if ($plan_details->no_of_vcards == 999) {
            $no_cards = 999999;
        } else {
            $no_cards = $plan_details->no_of_vcards;
        }

        // Check vcard creation limit
        if ($validity == 1) {
            if ($cards < $no_cards) {
                return view('user.pages.cards.choose-a-card', compact('settings', 'plan_details'));
            } else {
                return redirect()->route('user.cards')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
            }
        } else {
            // Redirect
            return redirect()->route('user.cards')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
        }
    }

    // Skip business hours
    public function skipAndSave()
    {
        // Redirect
        return redirect()->route('user.cards')->with('success', trans('Your virtual business card is updated!'));
    }

    // Card Status Page
    public function cardStatus(Request $request, $id)
    {
        // Queries
        $businessCard = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($businessCard == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {

            // Queries
            $business_card = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_id', $id)->first();

            // Check business card
            if ($business_card == null) {
                return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
            } else {
                // Check active cards
                if ($business_card->card_status == 'inactive') {

                    // Queries
                    $plan = User::where('user_id', Auth::user()->user_id)->first();
                    $active_plan = json_decode($plan->plan_details);

                    // vCard
                    if ($business_card->card_type == "vcard") {
                        // vCard
                        $no_of_services = Service::where('card_id', $id)->count();
                        $no_of_vcard_products = VcardProduct::where('card_id', $id)->count();
                        $no_of_links = BusinessField::where('card_id', $id)->count();
                        $no_of_payments = Payment::where('card_id', $id)->count();
                        $no_of_galleries = Gallery::where('card_id', $id)->count();
                        $business_hours = BusinessHour::where('card_id', $id)->count();
                        $contact_form = ContactForm::where('card_id', $id)->count();

                        // Check vcard / store limitation
                        if ($no_of_services <= $active_plan->no_of_services && $no_of_vcard_products <= $active_plan->no_of_vcard_products && $no_of_galleries <= $active_plan->no_of_galleries && $no_of_links <= $active_plan->no_of_links && $no_of_payments <= $active_plan->no_of_payments) {

                            // Queries (vCards)
                            $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();

                            // Get plan details in user
                            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
                            $plan_details = json_decode($plan->plan_details);

                            // Number of vcards limitation
                            if ($cards < $plan_details->no_of_vcards) {

                                // Update (vCard)
                                BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_id', $id)->update([
                                    'card_status' => 'activated',
                                ]);

                                return redirect()->route('user.cards')->with('success', trans('Your vcard activated.'));
                            } else {

                                return redirect()->route('user.cards')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
                            }
                        } else {
                            // Queries
                            $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();

                            // Get plan details in user
                            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
                            $plan_details = json_decode($plan->plan_details);

                            // Number of vcards limitation
                            if ($cards < $plan_details->no_of_vcards) {
                                return redirect()->route('user.edit.card', $id)->with('failed', 'You have downgraded your plan. Please re-configure this vcard as per your current plan features.');
                            } else {
                                return redirect()->route('user.cards')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
                            }
                        }
                    }

                    // Store
                    if ($business_card->card_type == "store") {
                        // Store
                        $no_of_categories = Category::where('user_id', auth::user()->user_id)->count();
                        $no_of_store_products = StoreProduct::where('card_id', $id)->count();

                        // Check vcard / store limitation
                        if ($no_of_categories <= $active_plan->no_of_categories && $no_of_store_products <= $active_plan->no_of_store_products) {

                            // Queries (Stores)
                            $stores = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'store')->where('card_status', 'activated')->count();

                            // Get plan details in user
                            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
                            $plan_details = json_decode($plan->plan_details);

                            // Number of stores limitation
                            if ($stores < $plan_details->no_of_stores) {

                                // Update (Stores)
                                BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'store')->where('card_id', $id)->update([
                                    'card_status' => 'activated',
                                ]);

                                return redirect()->route('user.stores')->with('success', trans('Your store link was activated.'));
                            } else {
                                return redirect()->route('user.stores')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
                            }
                        } else {

                            // Queries (Stores)
                            $stores = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'store')->where('card_status', 'activated')->count();

                            // Get plan details in user
                            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
                            $plan_details = json_decode($plan->plan_details);

                            // Number of stores limitation
                            if ($stores < $plan_details->no_of_stores) {
                                return redirect()->route('user.edit.store', $id)->with('failed', 'You have downgraded your plan. Please re-configure this vcard as per your current plan features.');
                            } else {
                                return redirect()->route('user.stores')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
                            }
                        }
                    }
                } else {
                    // Update
                    BusinessCard::where('user_id', Auth::user()->user_id)->where('card_id', $id)->update([
                        'card_status' => 'inactive',
                    ]);

                    return redirect()->back()->with('success', trans('Deactivated'));
                }
            }
        }
    }

    // Delete card
    public function deleteCard(Request $request)
    {
        // Delete
        BusinessCard::where('user_id', Auth::user()->user_id)->where('card_id', $request->query('id'))->update([
            'card_status' => 'deleted',
        ]);

        return redirect()->route('user.cards')->with('success', trans('Card Deleted!'));
    }

    // Search by theme
    public function searchTheme(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type');

        $cards = Theme::where('theme_name', 'LIKE', '%' . $query . '%')->where('theme_description', $type)->where('status', 1)->get();

        return response()->json($cards);
    }
}
