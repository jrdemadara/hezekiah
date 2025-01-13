<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CashoutController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Cashout');
    }

    public function save(Request $request)
    {
        $request->validate([
            'gross_amount' => 'required|numeric|min:500',
        ]);

        $user = $request->user();
        try {
            DB::transaction(function () use ($request, $user) {
                $amountToDeduct = $request->gross_amount;

                // Deduct referral points first
                $referralDeduct = min($user->referral_points, $amountToDeduct);
                $user->referral_points -= $referralDeduct;
                $amountToDeduct -= $referralDeduct;

                // Initialize orderDeduct with a default value
                $orderDeduct = 0;

                // If there's still a remaining amount, deduct from order points
                if ($amountToDeduct > 0) {
                    $orderDeduct = min($user->order_points, $amountToDeduct);
                    $user->order_points -= $orderDeduct;
                }

                // Save the changes to the user points
                $user->save();

                // Calculate the fee and net amount
                $fee = ($request->gross_amount * 10) / 100;
                $netAmount = $request->gross_amount - $fee;

                // Create the cashout record
                $user->cashouts()->create([
                    'gross_amount' => $request->gross_amount,
                    'transaction_fee' => $fee,
                    'net_amount' => $netAmount,
                    'referral_deduction' => $referralDeduct,
                    'order_deduction' => $orderDeduct,
                    'status' => 'pending',
                    'pooled' => false,
                ]);
            });

            // Return success response if transaction is successful
            return back()->with([
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            // If any exception occurs, return with an error message
            return back()->with([
                'status' => 'error',
                'message' => 'An error occurred during the cashout process. Please try again.',
            ]);
        }

    }

    public function history(): Response
    {
        return Inertia::render('CashoutHistory');
    }

}
