<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Options;

class LMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		return $next($request);
    }
}
