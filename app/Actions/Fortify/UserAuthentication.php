<?php

namespace App\Actions\Fortify;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class UserAuthentication
{

    public function user_authenticate()
    {
        $request = request();

        $username = $request->post(config('fortify.username'));

        $password = $request->post('password');

        $user = User::where('email', $username)
            ->orWhere('phone_number', $username)
            ->first();

        if ($user && Hash::check($password, $user->password)) {

            return $user;
        }

        return false;
    }
    public function admin_authenticate()
    {
        $request = request();

        $username = $request->post(config('fortify.username'));

        $password = $request->post('password');

        $admin = Admin::where('email', $username)
            ->orWhere('phone_number', $username)
            ->first();

        if ($admin && Hash::check($password, $admin->password)) {

            return $admin;
        }

        return false;
    }
}
