<?php

namespace App\Http\Controllers\User;

use App\Setting;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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

    // All categories
    public function categories(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::where('user_id', Auth::user()->user_id)->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->editColumn('created_at', function ($category) {
                    return Carbon::parse($category->created_at)->format('d-m-Y h:i A');
                })
                ->editColumn('category_name', function ($category) {
                    return '<strong>' . $category->category_name . '</strong>';
                })
                ->editColumn('status', function ($category) {
                    return $category->status == 0
                        ? '<span class="badge bg-red text-white text-white">' . __('Disabled') . '</span>'
                        : '<span class="badge bg-green text-white text-white">' . __('Enabled') . '</span>';
                })
                ->addColumn('action', function ($category) {
                    $actionBtn = '<a class="dropdown-item" href="' . route('user.edit.category', $category->category_id) . '">' . __('Edit') . '</a>';
                    $actionBtn .= $category->status == 1
                        ? '<a class="dropdown-item" onclick="updateStatus(`' . $category->category_id . '`); return false;">' . __('Disable') . '</a>'
                        : '<a class="dropdown-item" onclick="updateStatus(`' . $category->category_id . '`); return false;">' . __('Enable') . '</a>';
                    $actionBtn .= '<a class="dropdown-item" onclick="deleteCategory(`' . $category->category_id . '`, `delete`); return false;">' . __('Delete') . '</a>';

                    return '<span class="dropdown"><button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button><div class="actions dropdown-menu dropdown-menu-end" style="">' . $actionBtn . '</div></span>';
                })
                ->rawColumns(['category_name', 'status', 'action'])
                ->make(true);
        }

        $config = DB::table('config')->get();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.categories.index', compact('settings', 'config'));
    }

    // Create category
    public function createCategory()
    {
        // Queries
        $config = DB::table('config')->get();
        $settings = Setting::where('status', 1)->first();

        // Get plan details
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Queries
        $categories = Category::where('user_id', Auth::user()->user_id)->count();

        // Chech vcard creation limit
        if ($categories <= $plan_details->no_of_categories) {
            return view('user.pages.categories.create', compact('settings', 'config'));
        } else {
            return redirect()->route('user.categories')->with('failed', trans('You have reached the plan limit!'));
        }
    }

    // Save category
    public function saveCategory(Request $request)
    {
        // Get plan details
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Queries
        $categories = Category::where('user_id', Auth::user()->user_id)->count();

        // Check categories limit
        if ($categories < $plan_details->no_of_categories) {

            // Validity
            $validator = Validator::make($request->all(), [
                'thumbnail' => 'required|mimes:jpeg,png,jpg|max:' . env("SIZE_LIMIT") . '',
                'category_name' => 'required',
            ]);

            // Validate alert
            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // get thumbnail image
            $thumbnail = $request->thumbnail->getClientOriginalName();
            $UploadThumbnail = pathinfo($thumbnail, PATHINFO_FILENAME);
            $UploadExtension = pathinfo($thumbnail, PATHINFO_EXTENSION);

            // Upload image
            if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "gif" || $UploadExtension == "svg") {
                // Upload image
                $thumbnail = 'images/categories/' . 'IMG-' . uniqid() . '-' . time() . '.' . $request->thumbnail->extension();
                $request->thumbnail->move(public_path('images/categories'), $thumbnail);
            }

            // Save
            $category = new Category;
            $category->user_id = Auth::user()->user_id;
            $category->category_id = uniqid();
            $category->thumbnail = $thumbnail;
            $category->category_name = ucfirst($request->category_name);
            $category->save();

            return redirect()->route('user.create.category')->with('success', trans('New Category Created!'));
        } else {
            return redirect()->route('user.categories')->with('failed', trans('You have reached the plan limit!'));
        }
    }

    // Edit category
    public function editCategory(Request $request, $id)
    {
        // Parameters
        $category_id = $request->id;

        // Queries
        $category_details = Category::where('category_id', $category_id)->first();
        $settings = Setting::where('status', 1)->first();

        if ($category_details == null) {
            return redirect()->route('user.categories')->with('failed', trans('Category not found!'));
        } else {
            return view('user.pages.categories.edit', compact('category_details', 'settings'));
        }
    }

    // Update category
    public function updateCategory(Request $request)
    {
        // Validity
        if (!isset($request->thumbnail)) {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required',
                'category_name' => 'required',
            ]);

            // Validate alert
            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // Update query
            Category::where('category_id', $request->category_id)->update([
                'category_name' => ucfirst($request->category_name), 'updated_at' => now(),
            ]);

            return redirect()->route('user.edit.category', $request->category_id)->with('success', trans('Category details updated!'));
        } else {
            // Validity
            $validator = Validator::make($request->all(), [
                'category_id' => 'required',
                'thumbnail' => 'required|mimes:jpeg,png,jpg|max:' . env("SIZE_LIMIT") . '',
            ]);

            // Validate alert
            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // get thumbnail image
            $thumbnail = $request->thumbnail->getClientOriginalName();
            $UploadThumbnail = pathinfo($thumbnail, PATHINFO_FILENAME);
            $UploadExtension = pathinfo($thumbnail, PATHINFO_EXTENSION);

            // Upload image
            if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "gif" || $UploadExtension == "svg") {
                // Upload image
                $thumbnail = 'images/categories/' . 'IMG-' . uniqid() . '-' . time() . '.' . $request->thumbnail->extension();
                $request->thumbnail->move(public_path('images/categories'), $thumbnail);
            }

            // Update query
            Category::where('category_id', $request->category_id)->update([
                'thumbnail' => $thumbnail, 'category_name' => ucfirst($request->category_name), 'updated_at' => now(),
            ]);

            return redirect()->route('user.edit.category', $request->category_id)->with('success', trans('Category details updated!'));
        }
    }

    // Status category
    public function statusCategory(Request $request)
    {
        // Queries
        $category_details = Category::where('category_id', $request->query('id'))->first();

        // Get status
        if ($category_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Update query
        Category::where('category_id', $request->query('id'))->update(['status' => $status]);

        return redirect()->route('user.categories')->with('success', trans('Updated!'));
    }

    // Delete category
    public function deleteCategory(Request $request)
    {
        // Delete
        Category::where('category_id', $request->query('id'))->delete();

        return redirect()->route('user.categories')->with('success', trans('Removed!'));
    }
}
