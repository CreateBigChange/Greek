<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/7/20
 * Time: 下午8:10
 */

$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'jsx' , '*pzsJqbd^6rvTeuz' , 'jsx');
//$mysqli = new mysqli('127.0.0.1' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

for ($i = 203 ; $i > 0 ; $i--){
    for ($j = 0 ; $j < 3 ; $j++){
        $sql = "INSERT INTO `user_coupon`(`user_id` , `coupon_id` , `created_at` , `expire_time`) VALUES ( $i , 205 , '2016-07-20 20:08:48' , '2016-07-27 20:08:48')";
        $mysqli->query($sql);
    }
}