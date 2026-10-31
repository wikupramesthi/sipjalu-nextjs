<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request) : RedirectResponse
    {
        // $validated = $request->validateWithBag('updatePassword', [
        //     'current_password' => ['required', 'current_password'],
        //     'password' => ['required', Password::defaults(), 'confirmed'],
        // ]);

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'confirmed'],
        ], [
            'current_password.required' => 'The current password is required.',
            'current_password.current_password' => 'The current password is incorrect.',
            'password.required' => 'Please enter a new password.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least :min characters long.',
            'password.symbols' => 'The new password must contain at least one symbol.',
        ]);

        $user = $request->user();
        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        // Cabut semua sesi & cookie ingat-di-perangkat lain.
        Auth::logoutOtherDevices($validated['current_password']);
        DB::table('sessions')->where('user_id', $user->getAuthIdentifier())->where('id', '!=', session()->getId())->delete();

        return back()->with('success', 'Password has been successfully updated!');
    }
}
