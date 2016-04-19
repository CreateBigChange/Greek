<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * 获取随机值
     * @param int $len
     * @return string
     */
    public function getSalt($len = 8){
        $salt	= '';
        $str	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max	= strlen($str)-1;

        for($i=0 ; $i<$len ; $i++ ){
            $salt .= $str[rand(0,$max)];
        }

        return $salt;
    }

    /**
     * 获取加密串
     * @param $str
     * @param $salt
     * @return string
     */
    public function encrypt( $str  , $salt ){
        return sha1( md5($str . $salt) );
    }
}
