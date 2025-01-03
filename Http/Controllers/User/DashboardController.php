<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Setting;
use App\Visitor;
use Carbon\Carbon;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
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
    public function index(Request $request)
    {
        // Check
        if (Auth::user()->status == 1) {
            // Queries
            $plan = User::where('user_id', Auth::user()->user_id)->first();
            $active_plan = json_decode($plan->plan_details);
            $settings = Setting::where('status', 1)->first();
            $business_card = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', '!=', 'deleted')->count();
            $storesCount = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'store')->where('card_status', '!=', 'deleted')->count();
            $remaining_days = 0;

            // Check active plan in user
            if ($active_plan != null) {

                // Check active plan
                if (isset($active_plan)) {
                    // Add more days in validity
                    $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Auth::user()->plan_validity);
                    // Set the time to 23:59:59
                    $plan_validity->setTime(23, 59, 59);
                    if ($plan_validity->diffInDays() < 10) {
                        $plan_validity->addDays(1);
                    }
                    $current_date = Carbon::now();
                    $remaining_days = $current_date->diffInDays($plan_validity, false);
                }

                // Month wise date
                $monthCards = [];
                for ($month = 1; $month <= 12; $month++) {
                    $startDate = Carbon::create(date('Y'), $month);
                    $endDate = $startDate->copy()->endOfMonth();
                    $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->where('card_status', '!=', 'deleted')->count();
                    $monthCards[$month] = $cards;
                }
                $monthCards = implode(',', $monthCards);

                // Overview chart
                $vcards = [];
                $stores = [];
                for ($_month = 1; $_month <= 12; $_month++) {
                    $startDate = Carbon::create(date('Y'), $_month);
                    $endDate = $startDate->copy()->endOfMonth();
                    $vcard = BusinessCard::where('user_id', Auth::user()->user_id)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->where('card_type', 'vcard')->where('card_status', '!=', 'deleted')->where('status', 1)->count();
                    $store = BusinessCard::where('user_id', Auth::user()->user_id)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->where('card_type', 'store')->where('card_status', '!=', 'deleted')->where('status', 1)->count();
                    $vcards[$_month] = $vcard;
                    $stores[$_month] = $store;
                }

                // vCard and store counts
                $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('status', 1)->where('card_status', '!=', 'deleted')->get();

                $totalvCards = 0;
                $totalStores = 0;
                $cardId = [];
                for ($i = 0; $i < count($cards); $i++) {
                    if ($cards[$i]->card_type == 'vcard') {
                        $totalvCards += 1;
                    } else {
                        $totalStores += 1;
                    }
                    $cardId[$i] = $cards[$i]->card_url;
                }

                $vcards = implode(',', $vcards);
                $stores = implode(',', $stores);

                // Top 5 Platforms
                $platforms = Visitor::select('visitors.platform', DB::raw('count(*) as total'))->groupBy('visitors.platform')->whereIn('card_id', $cardId)->where('visitors.status', "1")->get();

                $_platforms = collect($platforms)->sortBy('total')->reverse()->toArray();
                $_platforms = array_values($_platforms);

                $highestPlatforms = [];

                if (count($_platforms) > 0) {
                    for ($j = 0; $j < count($_platforms); $j++) {
                        if ($j < 5) {
                            if (isset($_platforms[$j])) {
                                $highestPlatforms['platform'][] = $_platforms[$j]['platform'];
                                $highestPlatforms['count'][] = $_platforms[$j]['total'];
                            }
                        }
                    }
                } else {
                    $highestPlatforms['platform'][] = '';
                    $highestPlatforms['count'][] = 100;
                }

                // Top 5 Devices
                $devices = Visitor::select('visitors.device', DB::raw('count(*) as total'))->groupBy('visitors.device')->whereIn('card_id', $cardId)->where('visitors.status', "1")->get();

                $_devices = collect($devices)->sortBy('total')->reverse()->toArray();
                $_devices = array_values($_devices);

                $highestDevices = [];

                if (count($_devices) > 0) {
                    for ($m = 0; $m < count($_devices); $m++) {
                        if ($m < 5) {
                            if (isset($_devices[$m])) {
                                $highestDevices['device'][] = $_devices[$m]['device'];
                                $highestDevices['count'][] = $_devices[$m]['total'];
                            }
                        }
                    }
                } else {
                    $highestDevices['device'][] = '';
                    $highestDevices['count'][] = 100;
                }

                // Top 10 vCards & Stores
                $cards = Visitor::select('visitors.card_id', DB::raw('count(*) as total'))->groupBy('visitors.card_id')->whereIn('card_id', $cardId)->where('visitors.status', "1")->get();

                $_cards = collect($cards)->sortBy('total')->reverse()->toArray();
                $_cards = array_values($_cards);

                $highestCards = [];
                for ($k = 0; $k < count($_cards); $k++) {
                    if ($k < 10) {
                        $highestCards[$k]['card'] = $_cards[$k]['card_id'];
                        $highestCards[$k]['count'] = $_cards[$k]['total'];
                    }
                }

                // Current week vcards visitors
                $currentWeekVisitors = [];
                for ($l = 0; $l < 7; $l++) {
                    if ($l == 0) {
                        $currentWeekVisitors['vcard'][0] = Visitor::whereDate('created_at', Carbon::now()->startOfWeek())->whereIn('visitors.card_id', $cardId)->where('visitors.type', "vcard")->where('visitors.status', "1")->count();
                        $currentWeekVisitors['store'][0] = Visitor::whereDate('created_at', Carbon::now()->startOfWeek())->whereIn('visitors.card_id', $cardId)->where('visitors.type', "store")->where('visitors.status', "1")->count();
                    } else {
                        $currentWeekVisitors['vcard'][$l] = Visitor::whereDate('created_at', Carbon::now()->startOfWeek()->addDay($l))->whereIn('visitors.card_id', $cardId)->where('visitors.type', "vcard")->where('visitors.status', "1")->count();
                        $currentWeekVisitors['store'][$l] = Visitor::whereDate('created_at', Carbon::now()->startOfWeek()->addDay($l))->whereIn('visitors.card_id', $cardId)->where('visitors.type', "store")->where('visitors.status', "1")->count();
                    }
                }

                return view('user.dashboard', compact('settings', 'active_plan', 'remaining_days', 'business_card', 'storesCount', 'monthCards', 'vcards', 'stores', 'totalvCards', 'totalStores', 'highestPlatforms', 'highestCards', 'currentWeekVisitors', 'highestDevices'));
            } else {
                return redirect()->route('user.plans');
            }
        } else {
            Session::flush();

            // Assume $errorMessage holds your error message
            $errorMessage = "User not found!";

            // Flash the error message to the session
            Session::flash('error', $errorMessage);

            return redirect()->back();
        }
    }
}
