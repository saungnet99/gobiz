<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Carbon\Carbon;
use App\BlogCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
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

    // Blogs Category
    public function index(Request $request)
    {
        // Queries
        $settings = Setting::first();
        $config = DB::table('config')->get();

        if ($request->ajax()) {
            $blogsCategories = BlogCategory::where('status', '!=', 2)->orderBy('created_at', 'desc')->get();

            return DataTables::of($blogsCategories)
                ->addIndexColumn()
                ->editColumn('created_at', function ($category) {
                    return Carbon::parse($category->created_at)->format('d-m-Y h:i A');
                })
                ->editColumn('blog_category_title', function ($category) {
                    return __($category->blog_category_title);
                })
                ->editColumn('status', function ($category) {
                    return $category->status == 0
                        ? '<span class="badge bg-red text-white text-white">' . __('Unpublished') . '</span>'
                        : '<span class="badge bg-green text-white text-white">' . __('Published') . '</span>';
                })
                ->addColumn('action', function ($category) {
                    $editUrl = route('admin.edit.blog.category', $category->blog_category_id);
                    $actionBtn = '<a class="dropdown-item" href="' . $editUrl . '">' . __('Edit') . '</a>';

                    if ($category->status == 0) {
                        $actionBtn .= '<a class="dropdown-item" href="#" onclick="getBlogCategory(\'' . $category->blog_category_id . '\', \'publish\'); return false;">' . __('Publish') . '</a>';
                    } else {
                        $actionBtn .= '<a class="dropdown-item" href="#" onclick="getBlogCategory(\'' . $category->blog_category_id . '\', \'unpublish\'); return false;">' . __('Unpublish') . '</a>';
                    }

                    $actionBtn .= '<a class="dropdown-item" href="#" onclick="getBlogCategory(\'' . $category->blog_category_id . '\', \'delete\'); return false;">' . __('Delete') . '</a>';

                    return '<span class="dropdown"><button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button><div class="actions dropdown-menu dropdown-menu-end" style="">' . $actionBtn . '</div></span>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.pages.blogs.categories.index', compact('settings', 'config'));
    }

    // Add Blog Category
    public function createBlogCategory()
    {
        // Queries
        $settings = Setting::first();

        // View
        return view('admin.pages.blogs.categories.create', compact('settings'));
    }

    // Create Blog Category
    public function publishBlogCategory(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|min:3',
            'category_slug' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Save Blog Category
        $blogCategory = new BlogCategory();
        $blogCategory->published_by = Auth::user()->id;
        $blogCategory->blog_category_id = uniqid();
        $blogCategory->blog_category_title = ucfirst($request->category_name);
        $blogCategory->blog_category_slug = $request->category_slug;
        $blogCategory->save();

        // Redirect
        return redirect()->route('admin.create.blog.category')->with('success', trans('Category created successfully!'));
    }

    // Edit Blog Category
    public function editBlogCategory($id)
    {
        // Get page details
        $blogCategoryDetails = BlogCategory::where('blog_category_id', $id)->where('status', 1)->first();

        if ($blogCategoryDetails) {
            // Queries
            $settings = Setting::first();

            // View
            return view('admin.pages.blogs.categories.edit', compact('blogCategoryDetails', 'settings'));
        } else {
            return redirect()->route('admin.blog.categories')->with('failed', trans('Blog category not found!'));
        }
    }

    // Update Blog Category
    public function updateBlogCategory(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|min:3',
            'category_slug' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Blog category id
        $blogCategoryId = $request->segment(3);

        // Update query
        BlogCategory::where('blog_category_id', $blogCategoryId)->update(['blog_category_title' => ucfirst($request->category_name), 'blog_category_slug' => $request->category_slug]);

        // Redirect
        return redirect()->route('admin.edit.blog.category', $blogCategoryId)->with('success', trans('Category details update successfully!'));
    }

    // Actions
    public function actionBlogCategory(Request $request)
    {
        // Check status
        switch ($request->query('mode')) {
            case 'unpublish':
                $status = 0;
                break;

            case 'delete':
                $status = 2;
                break;

            default:
                $status = 1;
                break;
        }

        // Update status
        BlogCategory::where('blog_category_id', $request->query('id'))->update(['status' => $status]);

        // Redirect
        return redirect()->route('admin.blog.categories')->with('success', trans('Status updated successfully!'));
    }
}
