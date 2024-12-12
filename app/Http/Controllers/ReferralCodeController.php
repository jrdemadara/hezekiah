<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ReferralCodeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('ReferralCode', [
            'status' => session('status'),
        ]);
    }

    public function checkReferralCode(Request $request)
    {
        $request->validate([
            'code' => 'string|string|max:8',
        ]);

        $user = $request->user();

        if ($user->referred_by) {
            return back()->withErrors([
                'status' => 403,
            ]);

        }
        $referrer = User::where('code', Str::lower($request->code))
            ->select('id', 'firstname', 'lastname')
            ->first();

        if (!$referrer) {
            return back()->withErrors([
                'status' => 404,
            ]);
        }

        $request->user()->update([
            'referred_by' => $referrer->id,
        ]);

        return back()->with([
            'status' => 'success',
        ]);
    }
}
