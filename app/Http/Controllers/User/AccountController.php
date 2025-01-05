<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
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

    // My account
    public function account()
    {
        // Queries
        $account_details = User::where('user_id', auth()->user()->user_id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.account.account', compact('account_details', 'settings'));
    }

    // Edit account
    public function editAccount()
    {
        // Queries
        $account_details = User::where('user_id', auth()->user()->user_id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.account.edit-account', compact('account_details', 'settings'));
    }

    // Update account
    public function updateAccount(Request $request)
    {
        // Check profile image
        if ($request->profile_picture == null) {
            // Validate
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required'
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // Update
            User::where('user_id', auth()->user()->user_id)->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            return redirect()->route('user.edit.account')->with('success', 'Profile Updated Successfully!');
        } else {
            // Validate
            $validator = Validator::make($request->all(), [
                'profile_picture' => 'required|mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            // get profile image
            $profile_picture = $request->profile_picture->getClientOriginalName();
            $UploadProfile = pathinfo($profile_picture, PATHINFO_FILENAME);
            $UploadExtension = pathinfo($profile_picture, PATHINFO_EXTENSION);

            // Upload image
            if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "gif" || $UploadExtension == "svg") {
                // Upload image
                $profile_picture = 'profile_images/' . 'IMG-' . uniqid() . '-' . time() . '.' . $request->profile_picture->extension();
                $request->profile_picture->move(public_path('profile_images'), $profile_picture);

                // Update
                User::where('user_id', auth()->user()->user_id)->update([
                    'profile_image' => $profile_picture
                ]);
            }

            return redirect()->route('user.edit.account')->with('success', 'Updated!');
        }
    }

    // Change password
    public function changePassword()
    {
        // Queries
        $account_details = User::where('user_id', auth()->user()->user_id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.account.change-password', compact('account_details', 'settings'));
    }

    // Update password
    public function updatePassword(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);

        // Check password and confirm password
        if ($request->new_password == $request->confirm_password) {
            // Update
            User::where('user_id', auth()->user()->user_id)->update([
                'password' => bcrypt($request->new_password)
            ]);

            if ($validator->fails()) {
                return back()->with('failed', $validator->messages()->all()[0])->withInput();
            }

            return redirect()->route('user.change.password')->with('success', trans('Updated!'));
        } else {
            return redirect()->route('user.change.password')->with('failed', trans('Confirm Password Mismatched.'));
        }
    }

    // Change theme
    public function changeTheme($id)
    {
        // Update Password
        User::where('id', auth()->user()->id)->update([
            'choosed_theme' => $id
        ]);

        return redirect()->route('user.dashboard');
    }
}
