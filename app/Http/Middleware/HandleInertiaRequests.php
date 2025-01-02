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
        $indirectDownlines = 0;
        $packageCounts = [];

        // If the user is authenticated, calculate the downline count
        if ($user) {
            $indirectCount = DB::select('
                WITH RECURSIVE referral_hierarchy AS (
                    -- Start from the current user
                    SELECT id, referred_by, 0 AS level
                    FROM users
                    WHERE id = ?

                    UNION ALL

                    -- Recursively find referrals
                    SELECT u.id, u.referred_by, rh.level + 1 AS level
                    FROM users u
                    INNER JOIN referral_hierarchy rh ON rh.id = u.referred_by
                )
                SELECT COUNT(*) AS indirect_downline_count
                FROM referral_hierarchy
                WHERE level > 1; -- Exclude direct referrals
            ', [$user->id]);

            $indirectDownlines = $indirectCount[0]->indirect_downline_count ?? 0;

            // Fetch product counts by level
            $levelCounts = DB::select('
                        WITH RECURSIVE referral_hierarchy AS (
                            -- Start from the current user
                            SELECT id, referred_by, 0 AS level
                            FROM users
                            WHERE id = ?

                            UNION ALL

                            -- Recursively find referrals
                            SELECT u.id, u.referred_by, rh.level + 1 AS level
                            FROM users u
                            INNER JOIN referral_hierarchy rh ON rh.id = u.referred_by
                        )
                        SELECT rh.level, COUNT(c.id) AS product_count
                        FROM referral_hierarchy rh
                        INNER JOIN codes c ON c.orderer = rh.id
                        WHERE c.product_id = 3
                        AND c.is_used = true
                        GROUP BY rh.level
                        HAVING rh.level BETWEEN 1 AND 10;
                    ', [$user->id]);

            // Map the results to the desired structure
            foreach ($levelCounts as $row) {
                $packageCounts['level_' . $row->level] = $row->product_count;
            }

            // Ensure all levels from 1 to 10 are included (even if count is 0)
            for ($i = 1; $i <= 10; $i++) {
                if (!isset($packageCounts['level_' . $i])) {
                    $packageCounts['level_' . $i] = 0;
                }
            }

// Get the current year (for filtering data within this year)
            $currentYear = date('Y'); // Current year in YYYY format

// Run the query to get the referral trend data (direct and indirect)
            $data = DB::select('
    WITH RECURSIVE referral_hierarchy AS (
        -- Start from the current user
        SELECT id, referred_by, created_at
        FROM users
        WHERE id = ?

        UNION ALL

        -- Recursively find referrals (direct and indirect)
        SELECT u.id, u.referred_by, u.created_at
        FROM users u
        INNER JOIN referral_hierarchy rh ON rh.id = u.referred_by
    )
    SELECT
        COUNT(rh.id) AS referral_count,
        MONTH(rh.created_at) AS month
    FROM referral_hierarchy rh
    WHERE YEAR(rh.created_at) = ? -- Filter by current year
      AND rh.created_at <= NOW() -- Ensure the date is in the past (up to today)
    GROUP BY MONTH(rh.created_at)
    ORDER BY month;
', [$user->id, $currentYear]);

            $formattedData = [];
            foreach ($data as $row) {
                // Ensure the month is valid (1-12) before proceeding
                if ($row->month >= 1 && $row->month <= 12) {
                    // Get the last day of the month based on the current year and month
                    $date = new \DateTime("{$currentYear}-{$row->month}-01");

                    // Modify to get the last day of the month
                    $date->modify('last day of this month');

                    // Add the formatted data
                    $formattedData[] = [
                        'date' => $date->format('Y-m-d'), // Format the date as 'YYYY-MM-DD' (last day of the month)
                        'close' => (float) $row->referral_count, // Referral count (use the count as "close")
                    ];
                }
            }

        }

        return [
             ...parent::share($request),
            'auth' => [
                'user' => $user,
                'referrals' => $user ? $user->referrals()->count() : 0,
                'indirect' => $indirectDownlines,
                'cashouts' => $user ? $user->cashouts()->count() : 0,
                'package_bonus' => $packageCounts,
                'referral_trend' => $formattedData,
            ],
        ];

    }
}
