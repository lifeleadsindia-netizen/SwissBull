<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('ADMIN_LOGIN')) {
            Session()->flash('Accessmsg', 'Access Denied. Please Login Using your email and Password');

            return redirect('/hdgteyusjasget');
        }

        return $next($request);
    }
}
