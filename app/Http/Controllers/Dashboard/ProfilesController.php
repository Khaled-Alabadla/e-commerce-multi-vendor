<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfilesController extends Controller
{
    public function edit()
    {

        $user = Auth::user();
        $countries = Countries::getNames();
        $locales = Languages::getNames();

        return view('dashboard.profiles.edit', compact('user', 'countries', 'locales'));
    }

    public function update(Request $request)
    {

        $user = $request->user();

        $request->merge([
            'user_id' => $user->id,
        ]);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required|string',
        ]);

        $user->profile->fill($request->all())->save();

        return redirect()->route('dashboard.profile.edit')->with('message', 'Profile updated successfully')->with('type', 'success');
    }
}
