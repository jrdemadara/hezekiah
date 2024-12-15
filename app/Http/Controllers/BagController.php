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

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();
        $productId = $request->input('product_id');
        $bagItem = $user->bags()->firstOrNew(['product_id' => $productId]);

        if ($bagItem->exists) {
            $bagItem->increment('quantity');
        } else {
            $bagItem->quantity = 1;
            $bagItem->save();
        }

        return back()->with([
            'status' => 'success',
        ]);

    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();
        $productId = $request->input('product_id');
        $bagItem = $user->bags()->where('product_id', $productId)->first();

        if ($bagItem->exists) {
            $bagItem->delete();
        }

        return back()->with([
            'status' => 'success',
        ]);

    }

    public function increment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();
        $productId = $request->input('product_id');

        $bagItem = $user->bags()->where('product_id', $productId)->first();

        if ($bagItem->exists) {
            $bagItem->increment('quantity');
        }

        return back()->with('status', 'success');
    }

    public function decrement(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();
        $productId = $request->input('product_id');

        $bagItem = $user->bags()->where('product_id', $productId)->first();

        if ($bagItem->exists) {
            $bagItem->decrement('quantity');
        }

        return back()->with('status', 'success');
    }
}
