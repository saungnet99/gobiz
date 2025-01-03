<?php

namespace App\Http\Controllers\User\Store\Create;

use App\Medias;
use App\Setting;
use App\Category;
use App\BusinessCard;
use App\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
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

    // Products
    public function products()
    {
        // Get plan details
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Queries
        $categories = Category::where('user_id', Auth::user()->user_id)->where('status', 1)->get();
        $media = Medias::where('user_id', Auth::user()->user_id)->orderBy('id', 'desc')->get();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.store.products', compact('plan_details', 'media', 'settings', 'categories'));
    }

    // Save Products
    public function saveProducts(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.stores')->with('failed', trans('Store not found!'));
        } else {

            // Get plan details
            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check product image
            if ($request->badge != null) {

                // Check products limit
                if (count($request->badge) <= $plan_details->no_of_store_products) {

                    // Delete previous products
                    StoreProduct::where('card_id', $id)->delete();

                    // Check dynamic fields
                    for ($i = 0; $i < count($request->badge); $i++) {

                        // Save
                        $service = new StoreProduct();
                        $service->card_id = $id;
                        $service->product_id = uniqid();
                        $service->category_id = $request->categories[$i];
                        $service->badge = $request->badge[$i];
                        $service->product_image = $request->product_image[$i];
                        $service->product_name = $request->product_name[$i];
                        $service->product_subtitle = $request->product_subtitle[$i];
                        $service->regular_price = $request->regular_price[$i];
                        $service->sales_price = $request->sales_price[$i];
                        $service->product_status = $request->product_status[$i];
                        $service->save();
                    }

                    return redirect()->route('user.stores')->with('success', trans('Your WhatsApp store link is ready!'));
                } else {
                    return redirect()->route('user.products', $id)->with('failed', trans('You have reached the plan limit!'));
                }
            } else {
                return redirect()->route('user.products', $id)->with('failed', trans('You must add atleast one product.'));
            }
        }
    }
}
