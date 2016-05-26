<?php

namespace App\Http\Middleware;

use Closure;
use Session , Config , Cookie;
use App\Libs\Message;
use Illuminate\Http\Response;

class CheckLogin
{
    /**∑
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $sessionkey = cookie::get(config::get('session.store_app_login_cookie'));

        global $userInfo;
        $userInfo = session::get($sessionkey);

        if(!isset($userInfo->id)){
            return response()->json(Message::setResponseInfo('RELOGIN'));
        }



        return $next($request);
    }
}
