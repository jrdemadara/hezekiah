<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();

        // Check if the user is authenticated and has a 'referred_by' attribute
        if (!$user || is_null($user->referred_by)) {
            return response()->json([
                'errors' => [
                    'referred_by' => ['You must be referred to access this page.'],
                ],
                'status' => 'not-allowed',
            ], 403); // HTTP 403 Forbidden
        }

        return $next($request);
    }
}
