<?php

namespace App\Http\Controllers\Admin;

use App\Page;
use App\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
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

    //  Pages
    public function index(Request $request)
    {
        // Queries
        $settings = Setting::first();
        $config = DB::table('config')->get();

        // Static pages
        if ($request->ajax()) {
            $pages = DB::table('pages')
                ->whereIn('page_name', ['home', 'about', 'contact', 'faq', 'pricing', 'privacy', 'footer', 'refund', 'terms'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('page_name');

            return DataTables::of($pages)
                ->addIndexColumn()
                ->editColumn('page_name', function ($page) {
                    return match ($page->first()->page_name) {
                        'home' => __('Home'),
                        'about' => __('About Us'),
                        'contact' => __('Contact Us'),
                        'privacy' => __('Privacy Policy'),
                        'refund' => __('Refund Policy'),
                        'terms' => __('Terms & Conditions'),
                        default => ucfirst($page->first()->page_name),
                    };
                })
                ->editColumn('url', function ($page) {
                    $baseUrl = env('APP_URL');
                    return match ($page->first()->page_name) {
                        'home' => '<a href="' . $baseUrl . '" target="_blank">/</a>',
                        'about' => '<a href="' . $baseUrl . '/about-us" target="_blank">/about-us</a>',
                        'contact' => '<a href="' . $baseUrl . '/contact-us" target="_blank">/contact-us</a>',
                        'privacy' => '<a href="' . $baseUrl . '/privacy-policy" target="_blank">/privacy-policy</a>',
                        'refund' => '<a href="' . $baseUrl . '/refund-policy" target="_blank">/refund-policy</a>',
                        'terms' => '<a href="' . $baseUrl . '/terms-and-conditions" target="_blank">/terms-and-conditions</a>',
                        default => '<a href="' . $baseUrl . '/' . $page->first()->page_name . '" target="_blank">/' . $page->first()->page_name . '</a>',
                    };
                })
                ->editColumn('status', function ($page) {
                    return $page->first()->status == 'active'
                        ? '<span class="badge bg-green text-white">' . __('Enabled') . '</span>'
                        : '<span class="badge bg-red text-white">' . __('Disabled') . '</span>';
                })
                ->addColumn('action', function ($page) {
                    $editUrl = route('admin.edit.page', $page->first()->page_name);
                    $actionBtn = '<a class="dropdown-item" href="' . $editUrl . '">' . __('Edit') . '</a>';

                    if ($page->first()->status == 'inactive') {
                        $actionBtn .= '<a class="dropdown-item" href="#" onclick="getDisablePage(\'' . $page->first()->page_name . '\'); return false;">' . __('Enable') . '</a>';
                    } else if (!in_array($page->first()->page_name, ['home', 'footer'])) {
                        $actionBtn .= '<a class="dropdown-item" href="#" onclick="getDisablePage(\'' . $page->first()->page_name . '\'); return false;">' . __('Disable') . '</a>';
                    }

                    return '<span class="dropdown"><button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button><div class="actions dropdown-menu dropdown-menu-end" style="">' . $actionBtn . '</div></span>';
                })
                ->rawColumns(['url', 'status', 'action'])
                ->make(true);
        }

        return view('admin.pages.pages.index', compact('settings', 'config'));
    }

    public function customPagesIndex(Request $request)
    {
        // Queries
        $settings = Setting::first();
        $config = DB::table('config')->get();

        if ($request->ajax()) {
            $custom_pages = DB::table('pages')
                ->where('page_name', 'Custom Page')
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($custom_pages)
                ->addIndexColumn()
                ->editColumn('section_name', function ($page) {
                    return ucwords($page->section_name);
                })
                ->editColumn('url', function ($page) {
                    return '<a href="' . env('APP_URL') . '/p/' . $page->section_title . '" target="_blank">/' . $page->section_title . '</a>';
                })
                ->editColumn('status', function ($page) {
                    return $page->status == 'active'
                        ? '<span class="badge bg-green text-white">' . __('Enabled') . '</span>'
                        : '<span class="badge bg-red text-white">' . __('Disabled') . '</span>';
                })
                ->addColumn('action', function ($page) {
                    $editUrl = route('admin.edit.custom.page', $page->id);
                    $actionBtn = '<a class="dropdown-item" href="' . $editUrl . '">' . __('Edit') . '</a>';

                    if ($page->status == 'inactive') {
                        $actionBtn .= '<a class="dropdown-item" href="#" onclick="getPage(\'' . $page->id . '\'); return false;">' . __('Enable') . '</a>';
                    } else {
                        $actionBtn .= '<a class="dropdown-item" href="#" onclick="getPage(\'' . $page->id . '\'); return false;">' . __('Disable') . '</a>';
                    }

                    $actionBtn .= '<a class="dropdown-item" href="#" onclick="deletePage(\'' . $page->id . '\', \'delete\'); return false;">' . __('Delete') . '</a>';

                    return '<span class="dropdown"><button class="btn small-btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">' . __('Actions') . '</button><div class="actions dropdown-menu dropdown-menu-end" style="">' . $actionBtn . '</div></span>';
                })
                ->rawColumns(['url', 'status', 'action'])
                ->make(true);
        }

        return view('admin.pages.pages.index', compact('settings', 'config'));
    }

    // Add page
    public function addPage()
    {
        // Queries
        $config = DB::table('config')->get();
        $settings = Setting::first();

        // View
        return view('admin.pages.pages.add', compact('settings', 'config'));
    }

    // Save page
    public function savePage(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'page_name' => 'required',
            'slug' => 'required',
            'body' => 'required',
            'title' => 'required',
            'description' => 'required',
            'keywords' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Update page
        $page = new Page();
        $page->page_name = "Custom Page";
        $page->section_name = ucfirst($request->page_name);
        $page->section_title = $request->slug;
        $page->section_content = Purifier::clean($request->body);
        $page->title = ucfirst($request->title);
        $page->description = ucfirst($request->description);
        $page->keywords = $request->keywords;
        $page->save();

        return redirect()->back()->with('success', trans('Page Saved Successfully!'));
    }

    // Edit custom page
    public function editCustomPage($id)
    {
        // Get page details
        $page = DB::table('pages')->where('id', $id)->where('page_name', 'Custom Page')->first();

        if ($page) {
            // Queries
            $settings = Setting::first();
            $config = DB::table('config')->get();

            // View
            return view('admin.pages.pages.custom-edit', compact('page', 'settings', 'config'));
        } else {
            return redirect()->route('admin.pages')->with('failed', trans('Page not found!'));
        }
    }

    // Edit page
    public function editPage($id)
    {
        // Get page details
        $sections = DB::table('pages')->where('page_name', $id)->get();

        if (count($sections) > 0) {
            // Queries
            $settings = Setting::first();
            $config = DB::table('config')->get();

            // View
            return view('admin.pages.pages.edit', compact('sections', 'settings', 'config'));
        } else {
            return redirect()->route('admin.pages')->with('failed', trans('Page not found!'));
        }
    }

    // Update page
    public function updatePage(Request $request, $id)
    {
        // Update page
        $sections = DB::table('pages')->where('page_name', $id)->get();
        for ($i = 0; $i < count($sections); $i++) {
            $safe_section_content = $request->input('section' . $i);
            DB::table('pages')->where('page_name', $id)->where('id', $sections[$i]->id)->update(['section_content' => $safe_section_content]);
            DB::table('pages')->where('page_name', $id)->where('id', $sections[$i]->id)->update(['description' => $request->description, 'keywords' => $request->keywords]);
        }

        // SEO
        DB::table('pages')->where('page_name', $id)->update(['title' => $request->title]);
        DB::table('pages')->where('page_name', $id)->update(['keywords' => $request->keywords]);
        DB::table('pages')->where('page_name', $id)->update(['description' => $request->description]);

        // Page redirect
        return redirect()->route('admin.pages')->with('success', trans('Website Content Updated Successfully!'));
    }

    // Update custom page
    public function updateCustomPage(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'page_name' => 'required',
            'slug' => 'required',
            'body' => 'required',
            'title' => 'required',
            'description' => 'required',
            'keywords' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Update page
        DB::table('pages')->where('id', $request->page_id)->update(['section_name' => $request->page_name, 'section_title' => $request->slug, 'section_content' => Purifier::clean($request->body), 'title' => $request->title, 'description' => $request->description, 'keywords' => $request->keywords]);

        return redirect()->route('admin.pages')->with('success', trans('Page Updated Successfully!'));
    }

    // Status Page
    public function statusPage(Request $request)
    {
        // Get plan details
        $page_details = DB::table('pages')->where('id', $request->query('id'))->first();

        // Check status
        if ($page_details->status == 'inactive') {
            $status = 'active';
        } else {
            $status = 'inactive';
        }

        // Update status
        DB::table('pages')->where('id', $request->query('id'))->update(['status' => $status]);

        return redirect()->route('admin.pages')->with('success', trans('Page Status Updated Successfully!'));
    }

    // Disable Page
    public function disablePage(Request $request)
    {
        // Get plan details
        $page_details = DB::table('pages')->where('page_name', $request->query('id'))->first();

        // Check status
        if ($page_details->status == 'inactive') {
            $status = 'active';
        } else {
            $status = 'inactive';
        }

        // Update status
        DB::table('pages')->where('page_name', $request->query('id'))->update(['status' => $status]);

        return redirect()->route('admin.pages')->with('success', trans('Page Status Updated Successfully!'));
    }

    // Delete Page
    public function deletePage(Request $request)
    {
        // Update status
        DB::table('pages')->where('id', $request->query('id'))->delete();

        return redirect()->route('admin.pages')->with('success', trans('Page Deleted Successfully!'));
    }
}
