<?php

function amap()
{
    $config = [

        "key" => "c8149a3da34eaa5a1de69a34e1a42412",
        "tableid" => "573984757bbf1905ead016e3",
        "url" => "http://yuntuapi.amap.com/datamanage/data/create",
        "geo_url" => "http://restapi.amap.com/v3/geocode/geo",
        "regeo_url" => "http://restapi.amap.com/v3/geocode/regeo",
    ];
    /*
    * 在高德地图上标注店铺
    */
    $param = array(
        "key" => $config['key'],
        "tableid" => $config['tableid'],
        "loctype" => "1",
    );

    $mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'jsx' , '*pzsJqbd^6rvTeuz' , 'jsx');
    //$mysqli = new mysqli('127.0.0.1' , 'root' , '123456' , 'zxshop');
    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    $selectStore = "SELECT * FROM store_infos;";

    $store    = $mysqli->query($selectStore);

    while ($storeData = $store->fetch_array()){

        if($storeData['amap_id'] == 0) {
            $amapAddress = array(
                "_name" => $storeData['name'],
                "_location" => $storeData['location'],
                "coordtype" => 2,
                "province" => $storeData['province'],
                "city" => $storeData['city'],
                "county" => $storeData['county'],
                "_address" => $storeData['address'],
                "store_id" => $storeData['id']
            );

            $param['data'] = json_encode($amapAddress);

            $url = $config['url'];

            $amap = json_decode(post($url, $param));

            if (!empty($amap) && $amap->status == '1') {
                $sign['is_sign'] = 1;
                $sign['amap_id'] = $amap->_id;

                $updateStore = "UPDATE store_infos SET `is_sign` = 1 , `amap_id` = " . $amap->_id . " WHERE id = " . $storeData['id'];

                $mysqli->query($updateStore);
            }
        }
    }

    $mysqli->close();


}

function post($url, $post=''){
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

amap();

?>