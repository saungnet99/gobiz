<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WebsiteSettingController extends Controller
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

    // Update Website Setting
    public function index(Request $request)
    {
        Setting::where('id', '1')->update([
            'site_name' => $request->site_name
        ]);

        // App name
        $appName = str_replace('"', "", $request->app_name);
        $appName = str_replace("'", "", $appName);

        // Set new values using putenv
        $this->updateEnvFile('APP_NAME', '"' . $appName . '"');

        DB::table('config')->where('config_key', 'site_name')->update([
            'config_value' => $request->site_name
        ]);

        DB::table('config')->where('config_key', 'app_theme')->update([
            'config_value' => $request->app_theme,
        ]);

        // Check website logo
        if (isset($request->site_logo)) {
            $validator = Validator::make($request->all(), [
                'site_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            $site_logo = '/images/web/elements/' . uniqid() . '.' . $request->site_logo->extension();
            $request->site_logo->move(public_path('images/web/elements'), $site_logo);

            // Update details
            Setting::where('id', '1')->update([
                'google_analytics_id' => $request->google_analytics_id,
                'site_name' => $request->site_name, 'site_logo' => $site_logo,
                'tawk_chat_bot_key' => $request->tawk_chat_bot_key,
            ]);
        }

        // Check favicon
        if (isset($request->favi_icon)) {
            $validator = Validator::make($request->all(), [
                'favi_icon' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            $favi_icon = '/images/web/elements/' . uniqid() . '.' . $request->favi_icon->extension();
            $request->favi_icon->move(public_path('images/web/elements'), $favi_icon);

            // Update details
            Setting::where('id', '1')->update([
                'google_analytics_id' => $request->google_analytics_id,
                'site_name' => $request->site_name, 'favicon' => $favi_icon,
                'tawk_chat_bot_key' => $request->tawk_chat_bot_key,
            ]);
        }

        // Check primary image for website banner
        if (isset($request->primary_image)) {
            $validator = Validator::make($request->all(), [
                'primary_image' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            $primary_image = '/images/web/elements/' . uniqid() . '.' . $request->primary_image->extension();
            $request->primary_image->move(public_path('/images/web/elements'), $primary_image);

            // Update image
            DB::table('config')->where('config_key', 'primary_image')->update([
                'config_value' => $primary_image,
            ]);
        }

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Website Settings Updated Successfully!'));
    }

    public function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');

        // Check if the .env file exists
        if (file_exists($envPath)) {

            // Read the .env file
            $contentArray = file($envPath);

            // Loop through each line to find the key and update its value
            foreach ($contentArray as &$line) {

                // Split the line by '=' to get key and value
                $parts = explode('=', $line, 2);

                // Check if the key matches and update its value
                if (isset($parts[0]) && $parts[0] === $key) {
                    $line = $key . '=' . $value . PHP_EOL;
                }
            }

            // Implode the array back to a string and write it to the .env file
            $newContent = implode('', $contentArray);
            file_put_contents($envPath, $newContent);

            // Reload the environment variables
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}
