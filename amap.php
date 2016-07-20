<?php

class Curl{

	public function getCurlData($url, $post='', $autoFollow=0){
		$ch = curl_init();
		$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/20100101 Firefox/17.0 FirePHP/0.7.1';
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		// 2. 设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:192.168.2.11', 'CLIENT-IP:192.168.2.11'));  //构造IP
		curl_setopt($ch, CURLOPT_REFERER, "http://www.jisxu.com/");   //构造来路
		if($autoFollow){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  //启动跳转链接
			curl_setopt($ch, CURLOPT_AUTOREFERER, true);  //多级自动跳转
			$res = curl_getinfo($ch , CURLINFO_EFFECTIVE_URL);
			return $res;
		}
		//
		if($post!=''){
			curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		// 3. 执行并获取HTML文档内容
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}


$curl = new Curl();
//$url = "http://yuntuapi.amap.com/datamanage/table/create";
//
////$url =  "http://restapi.amap.com/v3/place/text?&keywords=北京大学&city=beijing&output=xml&offset=100&page=1&key=c8149a3da34eaa5a1de69a34e1a42412&extensions=all";
//$data = array(
//	"key"	=> "c8149a3da34eaa5a1de69a34e1a42412",
//	"name"	=> "jisxuyunamap"
//);


$data = array(
	"key" 		=> "c8149a3da34eaa5a1de69a34e1a42412",
	"tableid"	=> "5739ae52305a2a1894e73386",
	"loctype"	=> "1",
);

$address = array(
	"_name"		=> "测试一",
	"_location" => "104.394729,31.125698",
	"coordtype"	=> 1,
	"_address"	=> "北京市朝阳区融科望京中心",
	"store_id"	=> 1
);

$data['data'] = json_encode($address);

$url = "http://yuntuapi.amap.com/datamanage/data/create";


var_dump($curl->getCurlData($url , $data));