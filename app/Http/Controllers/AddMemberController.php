<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AddMemberController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('AddMember');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'phone' => 'required|integer|unique:users,phone',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:255',
        ]);

        // Get the current authenticated user
        $user = $request->user();

        // Check the code
        $code = Code::where('code', Str::lower($request->code))
            ->where('product_id', 4)
            ->where('is_used', false)
            ->first();

        if ($code) {
            $tempPass = trim($request->lastname) . substr(trim($request->phone), -4);

            // Try to create or update the member
            $member = User::updateOrCreate(
                [
                    'code' => Str::lower($this->generateRandomString()), // Random code
                ],
                [
                    'lastname' => Str::lower($request->lastname),
                    'firstname' => Str::lower($request->firstname),
                    'middlename' => Str::lower($request->middlename),
                    'phone' => $request->phone,
                    'address' => Str::lower($request->address),
                    'referred_by' => $user->id,
                    'password' => Hash::make(Str::lower($tempPass)),
                ]
            );

            // Check if the member was newly created or updated
            if ($member->wasRecentlyCreated) {
                // Update the code to mark it as used
                $code->update(['is_used' => true]);

                $this->distributeReferralPoints($member, [
                    1 => 500,
                    2 => 150,
                    3 => 100,
                    4 => 50,
                    5 => 30,
                    6 => 20,
                ]);

                return back()->with([
                    'success' => 'Member is created',
                ]);
            } else {
                return back()->withErrors([
                    'message' => 'Failed to created member. Please try again!',
                ]);
            }

        } else {
            // Code not found or already used
            return back()->withErrors([
                'message' => 'Code not found or already used.',
            ]);
        }
    }

    public function distributeReferralPoints(User $newUser, array $levelPoints)
    {
        $level = 1;
        $currentUserId = $newUser->referred_by;

        while ($currentUserId && $level <= count($levelPoints)) {
            $referrer = User::find($currentUserId);

            if ($referrer) {
                $this->awardPoints($referrer, $levelPoints[$level], $newUser);
                $currentUserId = $referrer->referred_by;
            } else {
                break;
            }

            $level++;
        }
    }

    protected function awardPoints(User $user, int $points, User $newUser)
    {
        $user->increment('points', $points);
        $user->transactions()->create([
            'points_earned' => $points,
            'description' => "Referral bonus: {$points} points earned from downline user ID: {$newUser->id}",
        ]);
    }

    private function generateRandomString()
    {
        // Define your allowed characters (both letters and numbers)
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        // Shuffle and pick random characters
        return substr(str_shuffle(str_repeat($characters, 8)), 0, 8);
    }
}
