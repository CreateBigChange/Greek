<?php

header ( "Content-type:text/html;charset=utf-8" );
require '../vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;



// 构建鉴权对象
$auth = new Auth("_mg2zyrq_pBlWx5pMe4OCk2F7vf1usEKC1Usaivn", "CxJOBYAA7-drI2uNMBlg-51l7NaZfLzOmhIQ0t1X");


// 生成上传 Token
$token = $auth->uploadToken("jisxu-store-app-test");

//
//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}




$mysqli->query("set names utf8");

$selectConfig = "SELECT * FROM store_configs";

$result = $mysqli->query($selectConfig);

while ($row = $result->fetch_object()){

    $filename	= time() + mt_rand(1000 , 9999);
    $imgName = $row->store_logo;

    $path = $row->store_logo;
    if(file_exists('./storeImg/logo/'.$imgName.'.jpg')){
        $exten = "jpg";
        $path = './storeImg/logo/'.$imgName.'.jpg';
    }elseif(file_exists('./storeImg/logo/'.$imgName.'.jpeg')){
        $exten = "jpeg";
        $path = './storeImg/logo/'.$imgName.'.jpeg';
    }elseif(file_exists('./storeImg/logo/'.$imgName.'.JPG')){
        $exten = "jpg";
        $path = './storeImg/logo/'.$imgName.'.JPG';
    }elseif(file_exists('./storeImg/logo/'.$imgName.'.PNG')){
        $exten = "png";
        $path = './storeImg/logo/'.$imgName.'.PNG';
    }elseif(file_exists('./storeImg/logo/'.$imgName.'.png')){
        $exten = "png";
        $path = './storeImg/logo/'.$imgName.'.png';
    }

    if(file_exists($path)) {

        // 上传到七牛后保存的文件名
        $key = $filename . '.' . $exten;

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $path);

        if ($err !== null) {
            echo "上传七牛失败,商品名称:" . $row->store_id;
        } else {
            $sql = "UPDATE store_configs SET `store_logo`='" . "http://7xt4zt.com2.z0.glb.clouddn.com/" . $ret['key'] . "' WHERE `store_id` = " . $row->store_id;
            if ($mysqli->query($sql)) {
                echo "成功更新一条,商品名称:" . $row->store_id;
            } else {
                echo "更新失败,商品名称:" . $row->store_id;
            }

        }
    }
}


$mysqli->close();

?>