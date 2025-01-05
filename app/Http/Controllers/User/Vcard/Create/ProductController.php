<?php

namespace App\Http\Controllers\User\Vcard\Create;

use App\Medias;
use App\Setting;
use App\Currency;
use App\BusinessCard;
use App\VcardProduct;
use App\BusinessField;
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
    public function vProducts()
    {
        // Queries
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);
        $media = Medias::where('user_id', Auth::user()->user_id)->orderBy('id', 'desc')->get();
        $currencies = Currency::get();
        $settings = Setting::where('status', 1)->first();

        // Check whatsapp number exists89
        $whatsAppNumberExists = BusinessField::where('card_id', request()->segment(3))->where('type', 'wa')->exists();

        return view('user.pages.cards.products', compact('plan_details', 'settings', 'media', 'currencies', 'whatsAppNumberExists'));
    }

    // Save vCard Products
    public function saveVProducts(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {

            // Get plan details
            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check icon
            if ($request->badge != null) {

                // Check products (vcard) limit
                if (count($request->badge) <= $plan_details->no_of_vcard_products) {
                    // Delete previous products
                    VcardProduct::where('card_id', $id)->delete();

                    // Check dynamic fields foreach
                    for ($i = 0; $i < count($request->badge); $i++) {

                        // Save
                        $product = new VcardProduct();
                        $product->card_id = $id;
                        $product->product_id = uniqid();
                        $product->badge = $request->badge[$i];
                        $product->currency = $request->currency[$i];
                        $product->product_image = $request->product_image[$i];
                        $product->product_name = $request->product_name[$i];
                        $product->product_subtitle = $request->product_subtitle[$i];
                        $product->regular_price = $request->regular_price[$i];
                        $product->sales_price = $request->sales_price[$i];
                        $product->product_status = isset($request->product_status) ? $request->product_status[$i] : "outstock";
                        $product->save();
                    }

                    return redirect()->route('user.galleries', $id)->with('success', trans('Products are added.'));
                } else {
                    return redirect()->route('user.vproducts', $id)->with('failed', trans('You have reached the plan limit!'));
                }
            } else {
                return redirect()->route('user.galleries', $id)->with('success', trans('Products are updated.'));
            }
        }
    }
}
