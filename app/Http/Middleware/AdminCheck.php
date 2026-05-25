<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $role = strtolower($user->role);

            if (in_array($role, ['admin', 'superadmin'])) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Access Denied: You do not have admin privileges.',
            'debug_role_detected' => $user ? $user->role : 'no_user' 
        ], 403);
    }
}
