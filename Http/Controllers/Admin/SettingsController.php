<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use App\Currency;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
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

    // Setting
    public function settings()
    {
        $timezonelist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $currencies = Currency::get();
        $settings = Setting::first();
        $config = DB::table('config')->get();

        $email_configuration = [
            'driver' => env('MAIL_MAILER', 'smtp'),
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'address' => env('MAIL_FROM_ADDRESS'),
            'name' => env('MAIL_FROM_NAME', $settings->site_name),
        ];

        $google_configuration = [
            'GOOGLE_ENABLE' => env('GOOGLE_ENABLE', 'off'),
            'GOOGLE_CLIENT_ID' => env('GOOGLE_CLIENT_ID', ''),
            'GOOGLE_CLIENT_SECRET' => env('GOOGLE_CLIENT_SECRET', ''),
            'GOOGLE_REDIRECT' => env('GOOGLE_REDIRECT', '')
        ];

        $image_limit = [
            'SIZE_LIMIT' => env('SIZE_LIMIT', '')
        ];

        $recaptcha_configuration = [
            'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE', 'off'),
            'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY', ''),
            'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', '')
        ];

        $settings['email_configuration'] = $email_configuration;
        $settings['google_configuration'] = $google_configuration;
        $settings['recaptcha_configuration'] = $recaptcha_configuration;
        $settings['image_limit'] = $image_limit;

        return view('admin.pages.settings.index', compact('settings', 'timezonelist', 'currencies', 'config'));
    }

    // Update General Setting
    public function changeGeneralSettings(Request $request)
    {
        Setting::where('id', '1')->update([
            'tawk_chat_bot_key' => $request->tawk_chat_bot_key
        ]);

        // Check show website
        DB::table('config')->where('config_key', 'show_website')->update([
            'config_value' => $request->show_website,
        ]);

        // Check timezone
        DB::table('config')->where('config_key', 'timezone')->update([
            'config_value' => $request->timezone,
        ]);

        // Set new values using putenv
        $this->updateEnvFile('TIMEZONE', $request->timezone);

        // Check currency
        DB::table('config')->where('config_key', 'currency')->update([
            'config_value' => $request->currency,
        ]);

        // Check plan term
        DB::table('config')->where('config_key', 'term')->update([
            'config_value' => $request->term,
        ]);

        DB::table('config')->where('config_key', 'share_content')->update([
            'config_value' => $request->share_content,
        ]);

        DB::table('config')->where('config_key', 'tiny_api_key')->update([
            'config_value' => $request->tiny_api_key,
        ]);

        // WhatsApp chatbot
        DB::table('config')->where('config_key', 'show_whatsapp_chatbot')->update([
            'config_value' => $request->show_whatsapp_chatbot,
        ]);

        DB::table('config')->where('config_key', 'whatsapp_chatbot_mobile_number')->update([
            'config_value' => $request->whatsapp_chatbot_mobile_number,
        ]);

        DB::table('config')->where('config_key', 'whatsapp_chatbot_message')->update([
            'config_value' => $request->whatsapp_chatbot_message,
        ]);

        // Check cookie consent
        $this->updateEnvFile('COOKIE_CONSENT_ENABLED', $request->cookie);
        $this->updateEnvFile('SIZE_LIMIT', $request->image_limit);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('General Settings Updated Successfully!'));
    }

    // Update Custom CSS & Scripts
    public function updateCustomScript(Request $request)
    {
        // Queries
        Setting::where('id', '1')->update([
            'custom_css' => $request->header, 'custom_scripts' => $request->footer
        ]);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Custom Scripts Updated!'));
    }

    // Clear cache
    public function clear()
    {
        // Laravel cache commend
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Application Cache Cleared Successfully!'));
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
