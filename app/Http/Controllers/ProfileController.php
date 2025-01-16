<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(): Response
    {
        return Inertia::render('Profile/UpdateProfileInformationForm');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'lastname'   => 'required|string|max:255',
            'firstname'  => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'address'    => 'required|string|max:255',
        ]);

        $user = $request->user();

        $user->update([
            'lastname'   => Str::lower($request->lastname),
            'firstname'  => Str::lower($request->firstname),
            'middlename' => Str::lower($request->middlename),
            'address'    => Str::lower($request->address),
        ]);

        return Redirect::route('profile.index');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
