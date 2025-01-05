<?php

namespace App\Http\Controllers\Admin;

use App\Coupon;
use App\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CouponsController extends Controller
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

    // Get all coupons
    public function indexCoupons(Request $request)
    {
        // Queries
        $coupons = Coupon::where('status', '!=', 2)->orderBy('id', 'desc')->get();
        $settings = Setting::where('status', 1)->first();
        $config = DB::table('config')->get();

        if ($request->ajax()) {
            return DataTables::of($coupons)
                ->addIndexColumn()
                ->addColumn('coupon_code', function ($coupon) {
                    return '<span class="text-uppercase">' . $coupon->coupon_code . '</span>';
                })
                ->addColumn('coupon_amount', function ($coupon) {
                    // Get config
                    $data = DB::table('config')->get();

                    if ($coupon->coupon_type == 'fixed') {
                        return $data[1]->config_value . ' ' . $coupon->coupon_amount;
                    } else {
                        return $coupon->coupon_amount . '%';
                    }
                })
                ->addColumn('validity', function ($coupon) {
                    return date('Y-m-d', strtotime($coupon->coupon_expired_on));
                })
                ->addColumn('status', function ($coupon) {
                    if ($coupon->status == 0) {
                        return '<span class="badge bg-red text-white text-white">' . __('Disabled') . '</span>';
                    } else {
                        return '<span class="badge bg-green text-white text-white">' . __('Active') . '</span>';
                    }
                })
                ->addColumn('action', function ($coupon) {
                    $actions = '<span class="dropdown">
                                    <button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button>
                                    <div class="actions dropdown-menu dropdown-menu-end" style="">
                                        <a class="dropdown-item" href="' . route('admin.edit.coupon', $coupon->coupon_id) . '">' . __('Edit') . '</a>';
                    if ($coupon->status == 0) {
                        $actions .= '<a class="dropdown-item" href="#" onclick="getCoupon(`' . $coupon->coupon_id . '`); return false;">' . __('Activate') . '</a>';
                    } else {
                        $actions .= '<a class="dropdown-item" href="#" onclick="getCoupon(`' . $coupon->coupon_id . '`); return false;">' . __('Deactivate') . '</a>';
                    }
                    $actions .= '<a class="dropdown-item" href="#" onclick="deleteCoupon(`' . $coupon->coupon_id . '`); return false;">' . __('Delete') . '</a>';
                    $actions .= '</div>
                                </span>';
                    return $actions;
                })
                ->rawColumns(['coupon_code', 'coupon_amount', 'validity', 'status', 'action'])
                ->make(true);
        }

        return view('admin.pages.coupons.index', compact('coupons', 'settings', 'config'));
    }

    // Create a new coupon
    public function createCoupon(Request $request)
    {
        // Queries
        $settings = Setting::where('status', 1)->first();
        $config = DB::table('config')->get();

        return view('admin.pages.coupons.create', compact('settings', 'config'));
    }

    // Save a new coupon
    public function storeCoupon(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'discount' => 'required',
            'validity' => 'required',
            'user_limit' => 'required',
            'total_limit' => 'required',
        ]);

        // Validate message
        if ($validated == false) {
            return redirect()->route('admin.coupons')->with('failed', trans('Please fill out all fields.'));
        }

        // Coupon code already exists
        if (Coupon::where('coupon_code', $request->code)->where('status', '!=', 2)->first()) {
            return redirect()->route('admin.coupons')->with('failed', trans('Coupon code already exists.'));
        }

        // Save
        $coupon = new Coupon;
        $coupon->coupon_id = uniqid();
        $coupon->coupon_code = Str::upper($request->code);
        $coupon->coupon_desc = $request->description;
        $coupon->coupon_type = $request->type;
        $coupon->coupon_amount = $request->discount;
        $coupon->coupon_expired_on = $request->validity . " 23:59:59";
        $coupon->coupon_user_usage_limit = $request->user_limit;
        $coupon->coupon_total_usage_limit = $request->total_limit;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('success', trans('Coupon created successfully.'));
    }

    // Edit a coupon
    public function editCoupon(Request $request, $id)
    {
        // First we need to find the coupon
        $couponDetails = Coupon::where('coupon_id', $id)->first();

        // Check coupon exists
        if ($couponDetails == null) {
            return redirect()->route('admin.coupons')->with('failed', trans('Coupon not found!'));
        }

        // Queries
        $settings = Setting::where('status', 1)->first();
        $config = DB::table('config')->get();

        return view('admin.pages.coupons.edit', compact('couponDetails', 'config', 'settings'));
    }

    // Update a coupon
    public function updateCoupon(Request $request, $id)
    {
        // Validate
        $validated = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'discount' => 'required',
            'validity' => 'required',
            'user_limit' => 'required',
            'total_limit' => 'required',
        ]);

        // Validate message
        if ($validated == false) {
            return redirect()->route('admin.edit.coupon', $id)->with('failed', trans('Please fill out all fields.'));
        }

        // Coupon code already exists
        $couponDetails = Coupon::where('coupon_id', $id)->where('status', '!=', 2)->first();
        if ($couponDetails->coupon_code != $request->code) {
            return redirect()->route('admin.edit.coupon', $id)->with('failed', trans('Coupon code already exists.'));
        }

        // Update
        $coupon = Coupon::where('coupon_id', $id)->first();
        $coupon->coupon_code = Str::upper($request->code);
        $coupon->coupon_desc = $request->description;
        $coupon->coupon_type = $request->type;
        $coupon->coupon_amount = $request->discount;
        $coupon->coupon_expired_on = $request->validity . " 23:59:59";
        $coupon->coupon_user_usage_limit = $request->user_limit;
        $coupon->coupon_total_usage_limit = $request->total_limit;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('success', trans('Coupon updated successfully.'));
    }

    // Update coupon status
    public function updateCouponStatus(Request $request)
    {
        // Update
        $coupon = Coupon::where('coupon_id', $request->query('id'))->first();
        
        // Check status
        if ($coupon->status == 1) {
            $coupon->status = 0;
        } else {
            $coupon->status = 1;
        }
        $coupon->save();

        return redirect()->route('admin.coupons')->with('success', trans('Coupon status updated successfully.'));
    }

    // Delete coupon
    public function deleteCoupon(Request $request)
    {
        // Update
        $coupon = Coupon::where('coupon_id', $request->query('id'))->first();
        $coupon->status = 2;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('success', trans('Coupon deleted successfully.'));
    }
}
