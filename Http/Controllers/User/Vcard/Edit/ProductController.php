<?php

namespace App\Http\Controllers\User\Vcard\Edit;

use App\User;
use App\Medias;
use App\Setting;
use App\Currency;
use App\BusinessCard;
use App\VcardProduct;
use App\BusinessField;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
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

    // Vcard Products
    public function vProducts(Request $request, $id)
    {
        // Queries
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            if ($request->ajax()) {
                $products = VcardProduct::where('card_id', $id)->orderBy('id', 'desc')->get();
                return DataTables::of($products)
                    ->addIndexColumn()
                    ->addColumn('product_image', function ($product) {
                        return __(asset($product->product_image));
                    })
                    ->addColumn('product_name', function ($product) {
                        return __($product->product_name);
                    })
                    ->addColumn('product_subtitle', function ($product) {
                        return __($product->product_subtitle);
                    })
                    ->addColumn('product_status', function ($product) {
                        if ($product->product_status == "outstock") {
                            return '<span class="badge bg-red text-white text-white">' . __('Out of Stock') . '</span>';
                        } else {
                            return '<span class="badge bg-green text-white text-white">' . __('In Stock') . '</span>';
                        }
                    })
                    ->addColumn('actions', function ($product) {
                        return '<div class="d-flex">
                            <button type="button" class="btn btn-success btn-icon m-1" onclick="editProduct(' . $product->id . ')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>
                            </button>
                            <button type="button" class="btn btn-danger btn-icon m-1" onclick="deleteProduct(' . $product->id . ')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12.07c0 .518 .387 .929 .9 .99l.1 .01h10c.552 0 1 -.448 1 -1l1 -12.07"/><path d="M9 7l1 -3h4l1 3"/></svg>
                            </button>
                        </div>';
                    })
                    ->rawColumns(['product_status', 'actions'])
                    ->make(true);
            }

            // Queries
            $currencies = Currency::get();
            $settings = Setting::where('status', 1)->first();

            // Check whatsapp number exists
            $whatsAppNumberExists = BusinessField::where('card_id', $id)->where('type', 'wa')->exists();

            return view('user.pages.edit-cards.edit-products', compact('plan_details', 'business_card', 'currencies', 'settings', 'whatsAppNumberExists'));
        }
    }

    // Save new service
    public function saveVProduct(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'product_badge' => 'required|string', // Add appropriate validation rules
            'product_currency' => 'required|string', // Add appropriate validation rules
            'product_image' => 'required|string', // Add appropriate validation rules
            'product_name' => 'required|string', // Add appropriate validation rules
            'product_description' => 'required|string', // Add appropriate validation rules
            'product_regular_price' => 'required', // Add appropriate validation rules
            'product_sales_price' => 'required', // Add appropriate validation rules
            'product_status' => 'required'
        ]);

        // Queries
        $plan = User::where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Already created count
        $countedProducts = VcardProduct::where('card_id', $request->card_id)->count();

        // Check product limit
        if ($countedProducts < $plan_details->no_of_vcard_products) {
            try {
                // Create a new product with the provided data
                $product = new VcardProduct();
                $product->card_id = $request->card_id;
                $product->product_id = uniqid();
                $product->badge = $request->product_badge;
                $product->currency = $request->product_currency;
                $product->product_image = $request->product_image;
                $product->product_name = $request->product_name;
                $product->product_subtitle = $request->product_description;
                $product->regular_price = $request->product_regular_price;
                $product->sales_price = $request->product_sales_price;
                $product->product_status = $request->product_status;

                // Save the new product
                $product->save();

                // Return success response
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // Return error response if any error occurs
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['success' => false, 'error' => trans('You have reached the plan limit!')], 500);
        }
    }

    // Update product
    public function updateVProduct(Request $request)
    {
        // Validate the request data as per your requirements
        $product = VcardProduct::find($request->input('product_id'));

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found.'
            ], 404);
        }

        // Update product data
        $product->update([
            'badge' => $request->input('product_badge'),
            'currency' => $request->input('product_currency'),
            'product_image' => $request->input('product_image'),
            'product_name' => $request->input('product_name'),
            'product_subtitle' => $request->input('product_description'),
            'regular_price' => $request->input('product_regular_price'),
            'sales_price' => $request->input('product_sales_price'),
            'product_status' => $request->input('product_status')
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('Product updated successfully.')
        ]);
    }

    // Get single product ajax call
    public function getVProducts($id)
    {
        try {
            // Retrieve the product from the database
            $product = VcardProduct::findOrFail($id);

            // Return a JSON response with the product data
            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            // Handle errors (e.g., product not found)
            return response()->json([
                'success' => false,
                'message' => trans('Product not found')
            ], 404);
        }
    }

    // Delete product
    public function deleteVProduct($id)
    {
        // Queries
        $product = VcardProduct::find($id);

        if ($product) {
            $product->delete();
            return response()->json(['message' => trans('Product deleted successfully')], 200);
        } else {
            return response()->json(['message' => trans('Product not found')], 404);
        }
    }
}
