<?php

namespace App\Classes;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser
{
    public function create($request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // List of permissions to check
        $fields = [
            'themes', 'plans', 'customers', 'payment_methods', 'transactions',
            'pages', 'blogs', 'users', 'general_settings', 'translations',
            'sitemap', 'invoice_tax', 'software_update'
        ];

        // Initialize an empty array for permissions
        $permissions = [];

        // Loop through each field and set the corresponding value in the permissions array
        foreach ($fields as $field) {
            $permissions[$field] = ($request->$field == "off") ? 0 : 1;
        }

        // Convert the permissions array to a JSON string
        $permissionsJson = json_encode($permissions);

        // Save
        $user = new User;
        $user->user_id = uniqid();
        $user->role_id = $request->role;
        $user->name = ucfirst($request->name);
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);
        $user->permissions = $permissionsJson;
        $user->save();

        return true;
    }
}
