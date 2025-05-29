<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserApproval
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only block staff/marketing if approval_required is not "no"
        if ($user && $user->approval_required == 'yes') {
            return response()->json([
                'message' => 'Your account requires admin approval before accessing this resource.'
            ], 403);
        }

        return $next($request);
    }
}
