<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/5/18
 * Time: 上午9:14
 */

//$data = file_get_contents('./citycode.json');

//$data = json_decode($data);

$con = new mysqli('127.0.0.1' , 'root' , '123456' , 'zxshop');
if ($con->connect_error) {
    die('Could not connect: ' . $con->connect_error);
}

function get($url){
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

//获取省数据
$data = get("http://restapi.amap.com/v3/config/district?key=b51eb8d33320947de6ca9302530c99d1&s=rsv3&output=json");
$data = json_decode($data);

foreach ($data->districts as $country){
    if(empty($country->citycode)){
        $country->citycode = 0;
    }
    //保存国家数据
    $sql = "INSERT INTO `amap_city_code`(`adcode` , `name` , `center` , `level` , `city_code`) VALUES ("
        . $country->adcode . ",'" . $country->name . "','" . $country->center ."','" . $country->level ."','"  . $country->citycode.
        "');";
    $con->query($sql);

    //保存省数据
    foreach ($country->districts as $province){
        if(empty($province->citycode)){
            $province->citycode = 0;
        }
        $sql = "INSERT INTO `amap_city_code`(`adcode` , `name` , `center` , `level` , `city_code`) VALUES ("
            . $province->adcode . ",'" . $province->name . "','" . $province->center ."','" . $province->level ."','" . $province->citycode.
            "');";
        $con->query($sql);

        //获取市数据
        $cityData = get("http://restapi.amap.com/v3/config/district?key=b51eb8d33320947de6ca9302530c99d1&s=rsv3&output=json&level=" . $province->name);
        $cityData = json_decode($cityData);

        foreach ($cityData->districts as $cdis) {
            if (empty($cdis->citycode)) {
                $cdis->citycode = 0;
            }
            $sql = "INSERT INTO `amap_city_code`(`adcode` , `name` , `center` , `level` , `city_code`) VALUES ("
                . $cdis->adcode . ",'" . $cdis->name . "','" . $cdis->center ."','" . $cdis->level ."','"  . $cdis->citycode.
                "');";
            $con->query($sql);

            foreach ($cdis->districts as $city) {
                if (empty($city->citycode)) {
                    $city->citycode = 0;
                }
                $sql = "INSERT INTO `amap_city_code`(`adcode` , `name` , `center` , `level` , `city_code`) VALUES ("
                    . $city->adcode . ",'" . $city->name . "','" . $city->center . "','" . $city->level . "'," . ",'" . $city->citycode .
                    "');";
                $con->query($sql);

                //获取区县数据
                $countyData = get("http://restapi.amap.com/v3/config/district?key=b51eb8d33320947de6ca9302530c99d1&s=rsv3&output=json&level=" . $city->name);
                $countyData = json_decode($countyData);

                foreach ($countyData->districts as $counDis) {
                    if (empty($counDis->citycode)) {
                        $counDis->citycode = 0;
                    }
                    $sql = "INSERT INTO `amap_city_code`(`adcode` , `name` , `center` , `level` , `city_code`) VALUES ("
                        . $counDis->adcode . ",'" . $counDis->name . "','" . $counDis->center ."','" . $counDis->level ."','"  . $counDis->citycode.
                        "');";
                    $con->query($sql);
                    //保持区县数据
                    foreach ($counDis->districts as $county) {
                        if (empty($county->citycode)) {
                            $county->citycode = 0;
                        }
                        $sql = "INSERT INTO `amap_city_code`(`adcode` , `name` , `center` , `level` , `city_code`) VALUES ("
                            . $county->adcode . ",'" . $county->name . "','" . $county->center . "','" . $county->level . "','" . $county->citycode .
                            "');";
                        $con->query($sql);
                    }
                }
            }
        }
    }
}
