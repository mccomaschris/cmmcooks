<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedEmails = explode(',', config('app.allowed_admin_emails'));

        if ($request->user() && in_array($request->user()->email, $allowedEmails, true)) {
            return $next($request);
        }

        return response('Unauthorized.', 401);
    }
}
