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

$selectInfo = "SELECT * FROM store_infos";

$result = $mysqli->query($selectInfo);

//while ($row = $result->fetch_object()){
//
//    $filename	= time() + mt_rand(1000 , 9999);
//    $imgName = $row->business_license;
//
//    $path = $row->business_license;
//    if(file_exists('./storeImg/business/'.$imgName.'.jpg')){
//        $exten = "jpg";
//        $path = './storeImg/business/'.$imgName.'.jpg';
//    }elseif(file_exists('./storeImg/business/'.$imgName.'.jpeg')){
//        $exten = "jpeg";
//        $path = './storeImg/business/'.$imgName.'.jpeg';
//    }elseif(file_exists('./storeImg/business/'.$imgName.'.JPG')){
//        $exten = "jpg";
//        $path = './storeImg/business/'.$imgName.'.JPG';
//    }elseif(file_exists('./storeImg/business/'.$imgName.'.PNG')){
//        $exten = "png";
//        $path = './storeImg/business/'.$imgName.'.PNG';
//    }elseif(file_exists('./storeImg/business/'.$imgName.'.png')){
//        $exten = "png";
//        $path = './storeImg/business/'.$imgName.'.png';
//    }
//
//    if(file_exists($path)) {
//
//        // 上传到七牛后保存的文件名
//        $key = $filename . '.' . $exten;
//
//        // 初始化 UploadManager 对象并进行文件的上传
//        $uploadMgr = new UploadManager();
//
//        // 调用 UploadManager 的 putFile 方法进行文件的上传
//        list($ret, $err) = $uploadMgr->putFile($token, $key, $path);
//
//        if ($err !== null) {
//            echo "上传七牛失败,商品名称:" . $row->id;
//        } else {
//            $sql = "UPDATE store_infos SET `business_license`='" . "http://7xt4zt.com2.z0.glb.clouddn.com/" . $ret['key'] . "' WHERE `id` = " . $row->id;
//            if ($mysqli->query($sql)) {
//                echo "成功更新一条,商品名称:" . $row->id;
//            } else {
//                echo "更新失败,商品名称:" . $row->id;
//            }
//
//        }
//    }
//}
//
//
//echo "更新身份证";

while ($row = $result->fetch_object()){

    $filename	= time() + mt_rand(1000 , 9999);
    $imgName = $row->id_card_img;

    $path = $row->id_card_img;
    if(file_exists('./storeImg/idcard/'.$imgName.'.jpg')){
        $exten = "jpg";
        $path = './storeImg/idcard/'.$imgName.'.jpg';
    }elseif(file_exists('./storeImg/idcard/'.$imgName.'.jpeg')){
        $exten = "jpeg";
        $path = './storeImg/idcard/'.$imgName.'.jpeg';
    }elseif(file_exists('./storeImg/idcard/'.$imgName.'.JPG')){
        $exten = "jpg";
        $path = './storeImg/idcard/'.$imgName.'.JPG';
    }elseif(file_exists('./storeImg/idcard/'.$imgName.'.PNG')){
        $exten = "png";
        $path = './storeImg/idcard/'.$imgName.'.PNG';
    }elseif(file_exists('./storeImg/idcard/'.$imgName.'.png')){
        $exten = "png";
        $path = './storeImg/idcard/'.$imgName.'.png';
    }

    if(file_exists($path)) {

        // 上传到七牛后保存的文件名
        $key = $filename . '.' . $exten;

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $path);

        if ($err !== null) {
            echo "上传七牛失败,商品名称:" . $row->id;
        } else {
            $sql = "UPDATE store_infos SET `id_card_img`='" . "http://7xt4zt.com2.z0.glb.clouddn.com/" . $ret['key'] . "' WHERE `id` = " . $row->id;
            if ($mysqli->query($sql)) {
                echo "成功更新一条,商品名称:" . $row->id;
            } else {
                echo "更新失败,商品名称:" . $row->id;
            }

        }
    }
}


$mysqli->close();

?>