<?php

header ( "Content-type:text/html;charset=utf-8" );
//
//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}


$mysqli->query("set names utf8");

$selectInfo = "SELECT * FROM store_infos";

$result = $mysqli->query($selectInfo);

$store = array();



while ($row = $result->fetch_object()){

    $selectNav      = "SELECT * FROM store_nav WHERE store_id = " .$row->id;
    $resultNavSql   = $mysqli->query($selectNav);

    $sort = 1;
    while ($rowNav = $resultNavSql->fetch_object()){

        $updateNavSql = "UPDATE store_nav SET `sort` = " . $sort ." WHERE id=" . $rowNav->id;
        $mysqli->query($updateNavSql);
        $sort ++;

        var_dump( "成功更新一条:" . $rowNav->id);
    }

}

$mysqli->close();

?>