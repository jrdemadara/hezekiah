<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class EWalletController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('EWallet');
    }

    public function store(Request $request)
    {
        $request->validate([
            'wallet'         => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
        ]);

        $user = $request->user();

        // Check if the user already has an e-wallet
        $ewallet = $user->ewallet()->first();

        if ($ewallet) {
            // Update the existing e-wallet
            $ewallet->update([
                'wallet'         => $request->wallet,
                'account_number' => $request->account_number,
                'account_name'   => Str::lower($request->account_name),
            ]);
        } else {
            // Create a new e-wallet if it doesn't exist
            $user->ewallet()->create([
                'wallet'         => $request->wallet,
                'account_number' => $request->account_number,
                'account_name'   => $request->account_name,
            ]);
        }

        return back()->with([
            'status' => 'success',
        ]);
    }
}
