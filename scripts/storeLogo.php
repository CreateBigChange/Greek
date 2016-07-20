<?php
require '../vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;


// 构建鉴权对象
$auth = new Auth("_mg2zyrq_pBlWx5pMe4OCk2F7vf1usEKC1Usaivn", "CxJOBYAA7-drI2uNMBlg-51l7NaZfLzOmhIQ0t1X");


// 生成上传 Token
$token = $auth->uploadToken("jisxu-store-app-test");


//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}



$mysqli->query("set names utf8");



$path = "./storeLogo";

$filesnames = scandir($path);

foreach ($filesnames as $f){

    if($f == "." || $f == ".."){
        continue;
    }

    $filenane   = explode('.', $f)[0];
    $exten      = explode('.', $f)[1];

    var_dump($filenane);

    $sql = "SELECT * FROM `store_infos` WHERE `name` = '".$filenane ."' limit 1";

    $sqlResult = $mysqli->query($sql);

    if(!$sqlResult){
        continue;
    }
    $info = $sqlResult->fetch_object();

    if($info){

        $filePath = "./storeLogo/" . $f;
        $filename	= time() + mt_rand(1000 , 9999);

        // 上传到七牛后保存的文件名
        $key = $filename.'.'.$exten;

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        if ($err !== null) {
            echo "上传七牛失败,商品名称:".$row->name;
        } else {
            $sql = "UPDATE `store_configs` SET `store_logo`='" . "http://7xt4zt.com2.z0.glb.clouddn.com/" . $ret['key'] . "' WHERE `store_id` = " . $info->id;
            if($mysqli->query($sql)){
                var_dump( "成功更新一条,店铺名称:".$info->name);
            }else{
                var_dump("更新失败,商品名称:".$info->name);
            }

        }
    }



}

$mysqli->close();

?>