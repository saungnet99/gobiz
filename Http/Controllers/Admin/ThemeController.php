<?php

namespace App\Http\Controllers\Admin;

use App\Theme;
use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
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

    // All Themes
    public function themes()
    {
        // Queries
        $themes = Theme::orderBy('id', 'asc')->paginate(8);

        for ($i = 0; $i < count($themes); $i++) {
            $themes[$i]->count = BusinessCard::where('theme_id', $themes[$i]->theme_id)->count();
        }

        $settings = Setting::where('status', 1)->first();

        return view('admin.pages.themes.index', compact('themes', 'settings'));
    }

    // Active Themes
    public function activeThemes()
    {
        // Queries
        $themes = Theme::where('status', '1')->orderBy('id', 'asc')->paginate(8);

        for ($i = 0; $i < count($themes); $i++) {
            $themes[$i]->count = BusinessCard::where('theme_id', $themes[$i]->theme_id)->count();
        }

        $settings = Setting::where('status', 1)->first();

        return view('admin.pages.themes.active-themes', compact('themes', 'settings'));
    }

    // Disabled Themes
    public function disabledThemes()
    {
        // Queries
        $themes = Theme::where('status', '0')->orderBy('id', 'asc')->paginate(8);

        for ($i = 0; $i < count($themes); $i++) {
            $themes[$i]->count = BusinessCard::where('theme_id', $themes[$i]->theme_id)->count();
        }

        $settings = Setting::where('status', 1)->first();

        return view('admin.pages.themes.disabled-themes', compact('themes', 'settings'));
    }

    // Edit theme
    public function editTheme(Request $request, $id)
    {
        // Queries
        $theme_details = Theme::where('theme_id', $id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();

        return view('admin.pages.themes.edit', compact('theme_details', 'settings'));
    }

    // Update theme
    public function updateTheme(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'theme_name' => 'required|min:3'
        ]);

        // Check theme thumbnail
        if (isset($request->theme_thumbnail)) {
            // Image validatation
            $validator = Validator::make($request->all(), [
                'theme_thumbnail' => 'required|mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // get theme thumbnail
            $theme_thumbnail = $request->theme_thumbnail->getClientOriginalName();
            $UploadExtension = pathinfo($theme_thumbnail, PATHINFO_EXTENSION);

            // Upload image
            if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "gif" || $UploadExtension == "svg") {
                // Upload image
                $fileName = time() . '.' . $request->theme_thumbnail->extension();

                $theme_thumbnail = 'img/vCards/' . $fileName;
                $request->theme_thumbnail->move(public_path('img/vCards'), $theme_thumbnail);

                // Update theme thumbnail
                Theme::where('theme_id', $request->theme_id)->update([
                    'theme_thumbnail' => $fileName, 'theme_name' => $request->theme_name
                ]);
            }

            return redirect()->route('admin.edit.theme', $request->theme_id)->with('success', trans('Theme details updated!'));
        } else {
            // Update theme name
            Theme::where('theme_id', $request->theme_id)->update([
                'theme_name' => $request->theme_name
            ]);

            return redirect()->route('admin.edit.theme', $request->theme_id)->with('success', trans('Theme details updated!'));
        }
    }

    // Update status
    public function updateThemeStatus(Request $request)
    {
        // Parameters
        if ($request->query('status') == 'enabled') {
            $status = '1';
        } else {
            $status = '0';
        }

        Theme::where('theme_id', $request->query('id'))->update(['status' => $status]);

        return redirect()->back()->with('success', trans('Updated!'));
    }

    // Search theme
    public function searchTheme(Request $request)
    {
        // Parameters
        $page = $request->query('view-page');
        $search = $request->query('query');

        // Queries
        $settings = Setting::where('status', 1)->first();

        switch ($page) {
            case 'active-themes':
                // Queries
                $themes = Theme::where('theme_name', 'like', '%' . $search . '%')->where('status', '1')->paginate(8);

                for ($i = 0; $i < count($themes); $i++) {
                    $themes[$i]->count = BusinessCard::where('theme_id', $themes[$i]->theme_id)->count();
                }
                break;

            case 'disabled-themes':
                // Queries
                $themes = Theme::where('theme_name', 'like', '%' . $search . '%')->where('status', '0')->paginate(8);

                for ($i = 0; $i < count($themes); $i++) {
                    $themes[$i]->count = BusinessCard::where('theme_id', $themes[$i]->theme_id)->count();
                }
                break;

            default:
                // Queries
                $themes = Theme::where('theme_name', 'like', '%' . $search . '%')->paginate(8);

                for ($i = 0; $i < count($themes); $i++) {
                    $themes[$i]->count = BusinessCard::where('theme_id', $themes[$i]->theme_id)->count();
                }
                break;
        }

        return view('admin.pages.themes.' . $page, compact('themes', 'settings'));
    }
}
