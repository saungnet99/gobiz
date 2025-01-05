<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoogleSettingController extends Controller
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

    // Update Google Setting
    public function index(Request $request)
    {
        // Set new values using putenv (google login)
        $this->updateEnvFile('GOOGLE_ENABLE', $request->google_auth_enable);
        $this->updateEnvFile('GOOGLE_CLIENT_ID', '"' . str_replace('"', "'", $request->google_client_id) . '"');
        $this->updateEnvFile('GOOGLE_CLIENT_SECRET', '"' . str_replace('"', "'", $request->google_client_secret) . '"');
        $this->updateEnvFile('GOOGLE_REDIRECT', '"' . str_replace('"', "'", $request->google_redirect) . '"');

        // Set new values using putenv (google recaptcha)
        $this->updateEnvFile('RECAPTCHA_ENABLE', $request->recaptcha_enable);
        $this->updateEnvFile('RECAPTCHA_SITE_KEY', '"' . str_replace('"', "'", $request->recaptcha_site_key) . '"');
        $this->updateEnvFile('RECAPTCHA_SECRET_KEY', '"' . str_replace('"', "'", $request->recaptcha_secret_key) . '"');

        Setting::where('id', '1')->update([
            'google_analytics_id' => $request->google_analytics_id, 'google_adsense_code' => $request->google_adsense_code
        ]);

        // Page redirect
        return redirect()->route('admin.settings')->with('success', trans('Google Settings Updated Successfully!'));
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
