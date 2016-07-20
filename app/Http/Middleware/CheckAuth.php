<?php

namespace App\Http\Middleware;

use Closure;
use Session , Config , Cookie;
use Symfony\Component\Routing\Route;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request , Closure $next)
    {
		$sessionkey = cookie::get(config::get('session.web_login_cookie'));
		global $userInfo;
		$userInfo = session::get($sessionkey);
		
		if(!isset($userInfo->id)){
            return redirect('alpha/login');
		}
		
		$permissions = $userInfo->permissions;

		$isHaveAuth = false;
		if(in_array('/' . $request->route()->getUri() , $permissions)){
			$isHaveAuth	= true;
		}
//		foreach($permissions as $p){
//			if(empty($p)){
//				continue;
//			}
//
//			dd($permissions);
//			dd($request->route()->getUri());
//			$isHaveAuth = true;
//			$pArr	= explode('/' , $p);
//			$urlArr = explode('/' , $_SERVER['REDIRECT_URL']);
//
//			for($i = 0 ; $i < count($pArr) ; $i++){
//
//				if( $pArr[$i] != '' && !preg_match('/\{*\}/' , $pArr[$i]) && isset($urlArr[$i]) && $pArr[$i] != $urlArr[$i] ){
//					$isHaveAuth = false;
//					//if($pArr[$i] != '' && $pArr[$i] != 'param' && isset($urlArr[$i]) && $pArr[$i] != $urlArr[$i]){
//				}
//			}
//
//			if($isHaveAuth){
//				break;
//			}
//
//		}

		if(!$isHaveAuth){
			return response('access denied' , 403);
		}

        return $next($request);
    }
}
