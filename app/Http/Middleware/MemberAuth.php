<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('MEMBER_ID')) {
            session()->flash('accessMsg', 'Assess Denied. Please login using your email and password');

            return redirect('/member');
        }

        return $next($request);
    }
}
