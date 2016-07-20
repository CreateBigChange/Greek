<?php

namespace App\Http\Middleware;

use Closure;
use Session , Config , Cookie;
use App\Libs\Message;
use Illuminate\Http\Response;
use App\Libs\BLogger;

class CheckVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($_SERVER['HTTP_APPVERSION']){}

        return $next($request);
    }
}
