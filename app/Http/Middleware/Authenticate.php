<?php

namespace App\Http\Middleware;

use Facades\App\Helpers\Authentication;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Authentication::loggedIn()) {
            return $next($request);
        } else {
            return redirect('/login');
        }

        return response('Unauthorized.', 401);
    }
}
