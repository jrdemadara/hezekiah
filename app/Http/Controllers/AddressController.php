<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AddressController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Address');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:12',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:255',
            'zipcode' => 'required|string|max:255',
            'landmark' => 'required|string|max:255',
            // 'coordinates' => 'required|string|max:255',
            'default' => 'required|boolean',
        ]);

        $user = $request->user();

        $user->addresses()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
            'street' => $request->street,
            'house_number' => $request->house_number,
            'zipcode' => $request->zipcode,
            'landmark' => $request->landmark,
            // 'coordinates' => $request->coordinates,
            'is_default' => $request->boolean('default'), // Convert to boolean
        ]);

        return back()->with([
            'status' => 'success',
        ]);
    }

    public function changeDefault(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'default' => 'required|boolean',
        ]);

        $user = Auth::id();

        try {
            // Begin transaction
            DB::transaction(function () use ($user, $request) {
                // Set the current default address to false
                Address::where('user_id', $user)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);

                // Set the selected address as default
                Address::where('user_id', $user)
                    ->where('id', $request->id)
                    ->update(['is_default' => true]);
            });

            return back()->with([
                'status' => 'success',
            ]);

        } catch (\Exception $e) {
            // Handle exception (e.g., log it)
            return back()->withErrors([
                'error' => 'Something went wrong, please try again.',
            ]);
        }

    }
}
