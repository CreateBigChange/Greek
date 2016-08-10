<?php

error_reporting(E_ALL);

date_default_timezone_set('Asia/ShangHai');



$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'jsx' , '*pzsJqbd^6rvTeuz' , 'jsx');
//$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$mysqli->query("set names utf8");


$fromStoreId  = 21;
$toStoreId = 24;

$selectStoreSql = "SELECT `sg`.`store_id` , 
                          `sg`.`c_id` , 
                          `sg`.`b_id` , 
                          `sg`.`goods_id` , 
                          `sg`.`name` as sgname, 
                          `sg`.`img` , 
                          `sg`.`out_price` , 
                          `sg`.`give_points` , 
                          `sg`.`spec` , 
                          `sg`.`desc` , 
                          `sg`.`stock` , 
                          `sg`.`is_open` , 
                          `sg`.`is_checked` ,
                          `sg`.`is_del` , 
                          `sn`.`name` as snname
                    FROM  `store_goods` as `sg` LEFT JOIN `store_nav` as `sn` ON `sg`.`nav_id` = `sn`.`id`  WHERE `sg`.`store_id` = $fromStoreId ";

$selectResult = $mysqli->query($selectStoreSql);

while ($storeGoodsData = $selectResult->fetch_object()){
    $selectStoreNavSql = "SELECT * FROM `store_nav` WHERE `name` = '" . $storeGoodsData->snname ."' AND store_id = " . $toStoreId;
    $selectStoreNavResult = $mysqli->query($selectStoreNavSql);
    $storeNav = $selectStoreNavResult->fetch_object();

    if($storeNav == null){
        $insertNav = "INSERT INTO `store_nav`(`store_id` , `name` , `sort` , `created_at` , `updated_at`) VALUES ($toStoreId , '{$storeGoodsData->snname}' , 1 , '" . date('Y-m-d H:i:s' , time()) ."' , '" . date('Y-m-d H:i:s' , time()) ."')";
        if($mysqli->query($insertNav)){
            $selectStoreNavSql = "SELECT * FROM `store_nav` WHERE `name` = '" . $storeGoodsData->snname ."' AND store_id = " . $toStoreId;
            $selectStoreNavResult = $mysqli->query($selectStoreNavSql);
            $storeNav = $selectStoreNavResult->fetch_object();
        }
    }

    $insertGoods = "INSERT INTO `store_goods`(`store_id` , `goods_id` , `nav_id` , `c_id` , `b_id` , `name` , `img` , `out_price` , `is_open` , `is_checked` , `spec` , `created_at` , `updated_at`)
                            VALUES(
                                $toStoreId , 
                                {$storeGoodsData->goods_id} , 
                                {$storeNav->id} , 
                                {$storeGoodsData->c_id} , 
                                {$storeGoodsData->b_id} , 
                                '{$storeGoodsData->sgname}',
                                '{$storeGoodsData->img}',
                                {$storeGoodsData->out_price},
                                {$storeGoodsData->is_open},
                                {$storeGoodsData->is_checked}, '" .
                                $storeGoodsData->spec . "','".
        date('Y-m-d H:i:s' , time())
        ."','"
        .
        date('Y-m-d H:i:s' , time())
        .

        "')";

    if($mysqli->query($insertGoods)){
        var_dump("成功插入一条");
    }else{
        var_dump("失败");
    }
}




$mysqli->close();

?>