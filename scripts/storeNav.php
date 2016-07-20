<?php

header ( "Content-type:text/html;charset=utf-8" );


//
//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}


$mysqli->query("set names utf8");



$selectGoods = "SELECT * FROM zxshop.store_goods";

$result = $mysqli->query($selectGoods);

$store = array();

$categoryIds = array();

while ($row = $result->fetch_object()) {

    if(!in_array($row->c_id, $categoryIds)){
        $categoryIds[] = $row->c_id;
    }

    if(!isset($store[$row->store_id])){
        $store[$row->store_id] = array();
        $store[$row->store_id][] = $row->c_id;
    }else{
        if(!in_array($row->c_id, $store[$row->store_id])){
            $store[$row->store_id][] = $row->c_id;
        }
    }
}

$selectCategory = "SELECT * FROM zxshop.goods_categories where id IN (" .implode(',', $categoryIds).")";
$result = $mysqli->query($selectCategory);

while ($row = $result->fetch_object()) {

    foreach ($store as $storeId => $storeCategory) {

       if(in_array($row->id , $storeCategory)){
           $selectNav = "SELECT * FROM zxshop.store_nav where `store_id` = " . $storeId ." AND `name` = '" . $row->name ."'";
           $resultNavSQL = $mysqli->query($selectNav);
           $resultNav = $resultNavSQL->fetch_object();

           if(!$resultNav){
               $insertNav = "INSERT INTO zxshop.store_nav(`store_id` , `name` , `created_at` , `updated_at`) VALUES (" . $storeId . ",'" . $row->name . "','" . date('Y-m-d H:i:s' , time()) . "','" . date('Y-m-d H:i:s' , time()) ."')";
               if($mysqli->query($insertNav)){
                   $selectNav = "SELECT * FROM zxshop.store_nav where `store_id` = " . $storeId ." AND `name` = '" . $row->name ."'";
                   $resultNavSQL = $mysqli->query($selectNav);
                   $resultNav = $resultNavSQL->fetch_object();

                   $updateStoreGoods = "UPDATE store_goods SET nav_id = " . $resultNav->id . " WHERE `store_id` = " . $storeId . " AND c_id = " . $row->id;
                   if($mysqli->query($updateStoreGoods)){
                       var_dump( "成功插入一条,店铺ID:".$storeId);
                   }else{
                       file_put_contents('./nav.log', $storeId."\n" ,FILE_APPEND);
                   }
               }
           }else{
               $updateStoreGoods = "UPDATE store_goods SET nav_id = " . $resultNav->id . " WHERE `store_id` = " . $storeId . " AND c_id = " . $row->id;
               if($mysqli->query($updateStoreGoods)){
                   var_dump( "成功插入一条,店铺ID:".$storeId);
               }else{
                   file_put_contents('./nav.log', $storeId."\n" ,FILE_APPEND);
               }
           }

       }
    }
}



$mysqli->close();

?>