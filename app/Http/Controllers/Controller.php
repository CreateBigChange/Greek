<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Config;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * 获取随机值
     * @param int $len
     * @return string
     */
    public function getSalt($len = 8 , $num = 0){
        $salt	= '';
        if($num == 0) {
            $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        }else{
            $str = "0123456789";
        }
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

    /**
     * 获取分页数据
     * @param $page
     * @param $length
     * @param $totalNum
     * @return Object
     */
    public function getPageData($page , $length , $totalNum){
        $pagedata               = new \stdClass;
        $pagedata->page         = $page;
        $pagedata->length       = $length;
        $pagedata->offset       = ($pagedata->page - 1) * $pagedata->length;
        $pagedata->totalPage    = ceil($totalNum/$length);
        $pagedata->totalNum     = $totalNum;
        $pagedata->isEndPage    = $page < $pagedata->totalPage ? 0 : 1;

        return $pagedata;
    }


}
