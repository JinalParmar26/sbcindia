<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminPanelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has admin panel access permission
        if (!$user->can('access_admin_panel')) {
            // Log for debugging
            \Log::info('Access denied for user: ' . $user->email . ' to route: ' . $request->path());
            
            // Redirect with error message
            return redirect('/')->with('error', 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
