<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.pages.account.account', compact('account_details', 'settings'));
    }

    // Edit account
    public function editAccount() 
    {
        // Queries
        $account_details = User::where('user_id', auth()->user()->user_id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();

        return view('admin.pages.account.edit-account', compact('account_details', 'settings'));
    }

    // Update account
    public function updateAccount(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Check profile image
        if (isset($request->profile_picture)) {
            // Image validatation
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
                $profile_picture = 'profile_images/' . 'IMG-' . $request->profile_picture->getClientOriginalName() . '-' . time() . '.' . $request->profile_picture->extension();
                $request->profile_picture->move(public_path('profile_images'), $profile_picture);

                // Update user profile image
                User::where('id', auth()->user()->id)->update([
                    'profile_image' => $profile_picture
                ]);
            }

            return redirect()->route('admin.edit.account')->with('success', trans('Profile Updated Successfully!'));
        } else {
            // Update user profile data
            User::where('id', auth()->user()->id)->update([
                'name' => $request->name
            ]);

            // Get register user data
            $registerUserData = User::where('id', auth()->user()->id)->first();

            if ($request->email != $registerUserData->email) {
                // Check already register count
                $alreadyRegister = User::where('email', $request->email)->count();

                // Check already register
                if ($alreadyRegister <= 0) {
                    // Update user profile data
                    User::where('id', auth()->user()->id)->update([
                        'email' => $request->email
                    ]);
                    return redirect()->route('admin.edit.account')->with('success', trans('Profile Updated Successfully!'));
                } else {
                    return redirect()->route('admin.edit.account')->with('failed', trans('This email address already registered.'));
                }
            }

            return redirect()->route('admin.edit.account')->with('success', trans('Profile Updated Successfully!'));
        }
    }

    // Change password
    public function changePassword()
    {
        $account_details = User::where('user_id', auth()->user()->user_id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();
        return view('admin.pages.account.change-password', compact('account_details', 'settings'));
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        if ($request->new_password == $request->confirm_password) {
            User::where('user_id', auth()->user()->user_id)->update([
                'password' => bcrypt($request->new_password)
            ]);
            
            return redirect()->route('admin.change.password')->with('success', trans('Profile Password Changed Successfully!'));
        } else {
            return redirect()->route('admin.change.password')->with('failed', trans('Confirm Password Mismatched.'));;
        }
    }

    // Change theme
    public function changeTheme($id)
    {
        // Update Password
        User::where('id', auth()->user()->id)->update([
            'choosed_theme' => $id
        ]);

        return redirect()->route('admin.dashboard');
    }
}
