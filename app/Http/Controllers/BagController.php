<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BagController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Bag', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function sync(Request $request)
    {
        $user = Auth::user();

        $bagItems = $request->input('bagItems');

        foreach ($bagItems as $item) {
            $bag = Bag::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $item['product_id']],
                ['quantity' => $item['quantity']]
            );
        }

        return response()->json(['message' => 'Bag items synced successfully!']);
    }
}
