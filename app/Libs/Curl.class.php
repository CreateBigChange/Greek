<?php
namespace App\Libs;

class Curl
{
    public function post($url, $post=''){
        $ch = curl_init();
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1';
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:192.168.2.11', 'CLIENT-IP:192.168.2.11'));  //构造IP
        curl_setopt($ch, CURLOPT_REFERER, "http://www.jisxu.com/");   //构造来路

        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function get($url){
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
}