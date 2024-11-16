<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if the user already exists
            $user = User::where('email', $googleUser->user['email'])->first();

            if (!$user) {
                // Create a new user if not exists
                $user = User::create([
                    'code' => Str::lower($this->generateRandomString()),
                    'lastname' => Str::lower($googleUser->user['family_name']),
                    'firstname' => Str::lower($googleUser->user['given_name']),
                    'email' => $googleUser->user['email'],
                    'email_verified_at' => Carbon::now(),
                    'password' => null,
                    'auth_provider' => 'google',
                ]);
            }

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));

        } catch (\Exception $e) {

            return back()->with([
                'status' => 'Unable to login using Google',
            ]);

        }
    }

    private function generateRandomString()
    {
        // Define your allowed characters (both letters and numbers)
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        // Shuffle and pick random characters
        return substr(str_shuffle(str_repeat($characters, 6)), 0, 6);
    }
}
