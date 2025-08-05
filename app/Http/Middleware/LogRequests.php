<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log incoming request
        Log::info('Incoming request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'user_id' => auth()->id() ?? 'guest',
            'session_id' => session()->getId(),
            'parameters' => $request->except(['password', 'password_confirmation']),
            'headers' => $request->headers->all()
        ]);

        $response = $next($request);

        // Log response
        Log::info('Response sent', [
            'status_code' => $response->getStatusCode(),
            'content_type' => $response->headers->get('content-type'),
            'response_size' => strlen($response->getContent())
        ]);

        return $response;
    }
}
