<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'referral_code' => 'required|string|max:8',
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => 'required|string|max:32',
            'bag_items' => 'nullable|array',
        ]);

        $referrer = User::where('code', $request->referral_code)->pluck('id')->first();

        if (!$referrer) {
            if (!$referrer) {
                return back()->with([
                    'status' => 'not found',
                ]);

            }
        }

        $user = User::create([
            'code' => Str::lower($this->generateRandomString()),
            'lastname' => Str::lower($request->lastname),
            'firstname' => Str::lower($request->firstname),
            'email' => Str::lower($request->email),
            'password' => Hash::make($request->password),
            'referred_by' => $referrer,
        ]);

        // Save the bag items to the database
        if (!empty($request->bag_items)) {
            foreach ($request->bag_items as $item) {
                Bag::create([
                    'user_id' => $user->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    private function generateRandomString()
    {
        // Define your allowed characters (both letters and numbers)
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        // Shuffle and pick random characters
        return substr(str_shuffle(str_repeat($characters, 8)), 0, 8);
    }
}
