<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $Roles): Response
    {
        $userRoles = auth()->user()->role;
        $allowedRoles = explode('|', $Roles);

        if (in_array($userRoles, $allowedRoles)) {
            return $next($request);
        }

        return response("<script>alert('Anda tidak memiliki akses'); history.back();</script>");
    }
}