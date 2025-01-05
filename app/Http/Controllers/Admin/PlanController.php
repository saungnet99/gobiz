<?php

namespace App\Http\Controllers\Admin;

use App\Plan;
use App\Setting;
use App\Classes\SavePlan;
use App\Classes\UpdatePlan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

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

    // All Plans
    public function plans(Request $request)
    {
        // Queries
        $currencies = Setting::where('status', 1)->get();
        $settings = Setting::where('status', 1)->first();
        $config = DB::table('config')->get();
        $plans = Plan::where('status', '!=', 2)->get();

        if ($request->ajax()) {
            return DataTables::of($plans)
                ->addIndexColumn()
                ->addColumn('plan_type', function ($plan) {
                    return '<span class="badge bg-primary text-white">' . ($plan->plan_type ? $plan->plan_type : "-") . '</span>';
                })
                ->addColumn('plan_name', function ($plan) {
                    return __($plan->plan_name);
                })
                ->addColumn('plan_price', function ($plan) use ($currencies) {
                    if ($plan->plan_price == 0) {
                        return __('Free');
                    } else {
                        return $currencies[0]->currency . $plan->plan_price;
                    }
                })
                ->addColumn('validity', function ($plan) {
                    if ($plan->validity == '9999') {
                        return __('Forever');
                    } elseif ($plan->validity == '31') {
                        return __('Monthly');
                    } elseif ($plan->validity == '366') {
                        return __('Yearly');
                    } else {
                        return $plan->validity . ' ' . __('Days');
                    }
                })
                ->addColumn('status', function ($plan) {
                    if ($plan->status == 0) {
                        return '<span class="badge bg-red text-white text-white">' . __('Discontinued') . '</span>';
                    } else {
                        return '<span class="badge bg-green text-white text-white">' . __('Active') . '</span>';
                    }
                })
                ->addColumn('action', function ($plan) {
                    $actions = '<span class="dropdown">
                                    <button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button>
                                    <div class="actions dropdown-menu dropdown-menu-end" style="">
                                        <a class="dropdown-item" href="' . route('admin.edit.plan', $plan->plan_id) . '">' . __('Edit') . '</a>';
                    if ($plan->status == 0) {
                        $actions .= '<a class="dropdown-item" href="#" onclick="getPlan(`' . $plan->plan_id . '`); return false;">' . __('Activate') . '</a>';
                    } else {
                        $actions .= '<a class="dropdown-item" href="#" onclick="getPlan(`' . $plan->plan_id . '`); return false;">' . __('Deactivate') . '</a>';
                    }
                    $actions .= '<a class="dropdown-item" href="#" onclick="deletePlan(`' . $plan->plan_id . '`); return false;">' . __('Delete') . '</a>';
                    $actions .= '</div>
                                </span>';
                    return $actions;
                })                
                ->rawColumns(['plan_type', 'status', 'action'])
                ->make(true);
        }

        return view('admin.pages.plans.plans', compact('settings', 'config'));
    }

    // Add Plan
    public function addPlan()
    {
        $config = DB::table('config')->get();
        $settings = Setting::where('status', 1)->first();
        return view('admin.pages.plans.add-plan', compact('settings', 'config'));
    }

    // Save Plan
    public function savePlan(Request $request)
    {
        // Save
        $plan = new SavePlan;
        $plan->create($request);

        // Check result
        if ($plan->result != 0) {
            return redirect()->route('admin.add.plan')->with('success', trans('New Plan Created Successfully!'));
        } else {
            return redirect()->route('admin.add.plan')->with('failed', trans('There is an error in the add.'));
        }
    }

    // Edit Plan
    public function editPlan(Request $request, $id)
    {
        $plan_id = $request->id;
        $plan_details = Plan::where('plan_id', $plan_id)->first();
        $settings = Setting::where('status', 1)->first();
        if ($plan_details == null) {
            return redirect()->route('admin.plans')->with('failed', trans('Plan not found!'));
        } else {
            return view('admin.pages.plans.edit-plan', compact('plan_details', 'settings'));
        }
    }

    // Update Plan
    public function updatePlan(Request $request)
    {
        // Update
        $updatePlan = new UpdatePlan;
        $updatePlan->create($request);
 
        // Check result
        if ($updatePlan->result != 0) {
            return redirect()->route('admin.edit.plan', $request->plan_id)->with('success', trans('Plan Details Updated Successfully!'));
        } else {
            return redirect()->route('admin.edit.plan', $request->plan_id)->with('failed', trans('There is an error in the update.'));
        }
    }

    // Status Plan
    public function statusPlan(Request $request)
    {
        // Queries
        $plan_details = Plan::where('plan_id', $request->query('id'))->first();

        if ($plan_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        Plan::where('plan_id', $request->query('id'))->update(['status' => $status]);

        return redirect()->route('admin.plans')->with('success', trans('Plan Status Updated Successfully!'));
    }

    // Delete Plan
    public function deletePlan(Request $request)
    {
        // Queries
        Plan::where('plan_id', $request->query('id'))->update(['status' => 2]);

        return redirect()->route('admin.plans')->with('success', trans('Plan Status Updated Successfully!'));
    }
}
