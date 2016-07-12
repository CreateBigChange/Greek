<?php

$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');

$mysqliJsx = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'jsx' , '*pzsJqbd^6rvTeuz' , 'jsx');
//$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}



$mysqli->query("set names utf8");

$zxshopSql = "SELECT * FROM store_infos";

$zxshopResult = $mysqli->query($zxshopSql);

while ($row = $zxshopResult->fetch_object()){

    $jsxSql = "UPDATE `store_infos` SET `business_license` = '" . $row->business_license . "' , `id_card_img` = '". $row->id_card_img ."' WHERE `store_id` = " .$row->store_id;
    $mysqliJsx->query($jsxSql);
}

$mysqli->close();

?>