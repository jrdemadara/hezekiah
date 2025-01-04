<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class NetworkController extends Controller
{
    public function index(): Response
    {

        $user = Auth::user();

        $allDownline = DB::select('
    WITH RECURSIVE referral_hierarchy AS (
        -- Start from the specified user
        SELECT u.id, u.referred_by
        FROM users u
        WHERE u.id = ?

        UNION ALL

        -- Recursively find referrals
        SELECT u.id, u.referred_by
        FROM users u
        INNER JOIN referral_hierarchy rh ON rh.id = u.referred_by
    )
    SELECT rh.id, rh.referred_by,
           CONCAT(u.lastname, " ", u.firstname) AS full_name
    FROM referral_hierarchy rh
    INNER JOIN users u ON rh.id = u.id
    WHERE rh.id != ?;
', [$user->id, $user->id]);

        // Build the tree structure
        $treeData = $this->buildTree($allDownline, $user->id);

        $result = json_encode([
            'name' => "me",
            'children' => $treeData,
        ]);

        return Inertia::render('Network', [
            'data' => $result,
        ]);
    }

    public function buildTree(array $elements, $parentId = null)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element->referred_by == $parentId) {
                // Recursively build the children
                $children = $this->buildTree($elements, $element->id);
                $element->children = $children;
                $element->name = $element->full_name;
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
