<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Order');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:32',
        ]);

        // Get the current authenticated user
        $user = $request->user();

        // Check the code
        $code = Code::where('code', Str::lower($request->code))
            ->where('product_id', '!=', 4)
            ->where('is_used', false)
            ->first();

        if (!$code) {
            return back()->withErrors(['message' => 'Code does not exist!']);
        }

        $product = $code->product;

        if (!$product) {
            return back()->withErrors(['message' => 'Product does not exist!']);
        }

        // Update the code and assign the orderer in one call
        $code->update([
            'is_used' => true,
            'orderer' => $user->id,
        ]);

        $this->distributeOrderPoints($user, $product);

    }

    public function distributeOrderPoints(User $orderer, Product $product)
    {
        // Define points based on the product name
        $levelPoints = [
            'immuno' => [
                'level_1_5' => 30, // Points for levels 1-5
                'level_6_10' => 10, // Points for levels 6-10
            ],
            'prebio' => [
                'level_1_5' => 10, // Points for levels 1-5
                'level_6_10' => 5, // Points for levels 6-10
            ],
        ];

        // Get the appropriate points configuration based on the product name
        $productPoints = $levelPoints[strtolower($product->name)] ?? null;

        if (!$productPoints) {
            // Handle case where product name is not defined in the levelPoints array
            return back()->withErrors([
                'message' => 'ERR701: Something went wrong. Please contact the developer.',
            ]);

        }

        $level = 1; // Start at level 1
        $currentUserId = $orderer->referred_by;

        // Traverse up to 10 levels
        while ($currentUserId && $level <= 10) {
            $referrer = User::find($currentUserId);

            if ($referrer) {
                // Determine points based on the level
                $points = $level <= 5 ? $productPoints['level_1_5'] : $productPoints['level_6_10'];

                // Award points
                $this->awardPoints($referrer, $points, $orderer, $product);

                // Move to the next referrer
                $currentUserId = $referrer->referred_by;
            } else {
                break; // Stop if no referrer is found
            }

            $level++;
        }
    }

    protected function awardPoints(User $referrer, int $points, User $orderer, Product $product)
    {
        // Increment the referrer's points
        $referrer->increment('points', $points);

        // Log the transaction for accountability
        $referrer->transactions()->create([
            'points_earned' => $points,
            'description' => "Earned {$points} points from order by user:{$orderer->id} for product:{$product->id}",
        ]);
    }
}
