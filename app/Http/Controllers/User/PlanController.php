<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Setting;
use App\Currency;
use Carbon\Carbon;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
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

    // Plans
    public function index()
    {
        // Queries
        $plans = DB::table('plans')->where('is_private', 0)->where('status', 1)->get();
        $config = DB::table('config')->get();
        $free_plan = Transaction::where('user_id', Auth::user()->id)->where('transaction_amount', '0')->count();
        $plan = User::where('user_id', Auth::user()->user_id)->first();
        $active_plan = json_decode($plan->plan_details);
        $settings = Setting::where('status', 1)->first();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();
        $remaining_days = 0;

        // Check active plan in user
        if (isset($active_plan)) {

            // Get plan validity into current date (remaining days)
            $plan_validity = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Auth::user()->plan_validity);
            // Set the time to 23:59:59
            $plan_validity->setTime(23, 59, 59);
            if ($plan_validity->diffInDays() < 10) {
                $plan_validity->addDays(1);
            }
            $current_date = Carbon::now();
            $remaining_days = $current_date->diffInDays($plan_validity, false);
        }

        return view('user.pages.plans.plans', compact('plans', 'settings', 'currency', 'active_plan', 'remaining_days', 'config', 'free_plan'));
    }
}
