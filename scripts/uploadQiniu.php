<?php
require '../vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;



$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');

if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$selectGoods  = "SELECT * FROM goods";

$goodsResult = $mysqli->query($selectGoods);


// 构建鉴权对象
$auth = new Auth("_mg2zyrq_pBlWx5pMe4OCk2F7vf1usEKC1Usaivn", "CxJOBYAA7-drI2uNMBlg-51l7NaZfLzOmhIQ0t1X");


// 生成上传 Token
$token = $auth->uploadToken("jisxu-store-app-test");


while ($row = $goodsResult->fetch_object()) {
    $filename	= time() + mt_rand(1000 , 9999);
    $imgName  = $row->img;
    echo $imgName;
    if(file_exists('/tmp/goods_img/'.$imgName.'.jpg')){
        $exten = "jpg";
        $path = '/tmp/goods_img/'.$imgName.'.jpg';
    }elseif(file_exists('/tmp/goods_img/'.$imgName.'.jpeg')){
        $exten = "jpeg";
        $path = '/tmp/goods_img/'.$imgName.'.jpeg';
    }elseif(file_exists('/tmp/goods_img/'.$imgName.'.JPG')){
        $exten = "jpg";
        $path = '/tmp/goods_img/'.$imgName.'.JPG';
    }

    // 上传到七牛后保存的文件名
    $key = $filename.'.'.$exten;

    // 初始化 UploadManager 对象并进行文件的上传
    $uploadMgr = new UploadManager();

    // 调用 UploadManager 的 putFile 方法进行文件的上传
    list($ret, $err) = $uploadMgr->putFile($token, $key, $path);

    if ($err !== null) {
        return response()->json( Message::setResponseInfo( 'FAILED' ) );
    } else {
        $sql = "UPDATE goods SET `img`=" . "http://7xt4zt.com2.z0.glb.clouddn.com/" . $ret['key'] . " WHERE `id` = " . $row->id;
        var_dump($sql);die;
        $mysqli->query($sql);

        die;
    }

}





?>