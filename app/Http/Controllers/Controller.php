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

    /**
     * @param $url
     * @return mixed
     * curl get
     */
    public function curlGet($url){
        $ch = curl_init();
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1';
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:192.168.2.11', 'CLIENT-IP:192.168.2.11'));  //构造IP
        curl_setopt($ch, CURLOPT_REFERER, "http://www.jisxu.com/");   //构造来路

        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    /**
     * @return bool
     * 获取真实IP
     */

    public function getRealIp(){
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

}
