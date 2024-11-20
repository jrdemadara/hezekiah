<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        // Initialize downline count to 0 in case the user is not authenticated
        $downlines = 0;

        // If the user is authenticated, calculate the downline count
        if ($user) {
            $allDownline = DB::select('
            WITH RECURSIVE referral_hierarchy AS (
                SELECT id, referred_by FROM users WHERE id = ?
                UNION ALL
                SELECT u.id, u.referred_by
                FROM users u
                INNER JOIN referral_hierarchy rh ON rh.id = u.referred_by
            )
            SELECT COUNT(*) AS downline_count
            FROM referral_hierarchy
            WHERE id != ?;
        ', [$user->id, $user->id]);

            $downlines = $allDownline[0]->downline_count ?? 0;
        }

        return [
             ...parent::share($request),
            'auth' => [
                'user' => $user,
                'referrals' => $user ? $user->referrals()->count() : 0,
                'downlines' => $downlines,
                'bags' => $user ? $user->bags()->count() : 0,
            ],
        ];

    }
}
