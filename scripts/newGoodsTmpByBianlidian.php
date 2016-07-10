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

$selectBrand = "SELECT * FROM zxshop.goods where img not like 'http://7xt4zt.com%'";

$result = $mysqli->query($selectBrand);

$store = array();

while ($row = $result->fetch_object()){

    $path = "./newGoodsTmpShuiGuoImg/" . $row->img;
    $exten = "jpeg";

    if(file_exists($path.".jpg")){
        $path .= ".jpg";
        $exten = "jpg";
    }elseif(file_exists($path.".JPG")){
        $exten = "jpg";
        $path .= ".jpg";
    }elseif(file_exists($path.".jpeg")){
        $exten = "jpeg";
        $path .= ".jpeg";
    }elseif(file_exists($path.".JPEG")){
        $exten = "jpeg";
        $path .= ".JPEG";
    }

    $filename	= time() + mt_rand(1000 , 9999);

    // 上传到七牛后保存的文件名
    $key = $filename.'.'.$exten;

    if(file_exists($path)) {

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $path);

        if ($err !== null) {
            echo "上传七牛失败,商品名称:" . $row->name;
        } else {
            $sql = "UPDATE goods SET `img`='" . "http://7xt4zt.com2.z0.glb.clouddn.com/" . $ret['key'] . "' WHERE `id` = " . $row->id;
            if ($mysqli->query($sql)) {
                echo "成功更新一条,商品名称:" . $row->name;
            } else {
                echo "更新失败,商品名称:" . $row->name;
            }

        }
    }

}

$mysqli->close();

?>