<?php

header ( "Content-type:text/html;charset=utf-8" );
//
//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}


$mysqli->query("set names utf8");

$selectBrand = "SELECT * FROM goods_brand";

$result = $mysqli->query($selectBrand);

$store = array();

<<<<<<< HEAD
//while ($row = $result->fetch_object()){
//
//    $selectGoods = "SELECT * FROM store_goods WHERE name like '%". $row->name . "%'";
////    $selectGoods = "SELECT * FROM store_goods WHERE name like '%". "康师傅" . "%'";
//    $resultGoods = $mysqli->query($selectGoods);
//
//    $goodsIds = array();
//    while ($rowGoods = $resultGoods->fetch_object()){
//
//        $categorySql = "SELECT * FROM goods_categories WHERE id = " . $row->c_id;
//        $category = $mysqli->query($categorySql);
//        $categoryResult = $category->fetch_object();
//        if($categoryResult){
//            $goodsCategoryId = $categoryResult->id;
//            $navSql = "SELECT * FROM goods_categories WHERE `id` = " . $categoryResult->p_id;
//            $nav = $mysqli->query($navSql);
//            $navResult = $nav->fetch_object();
//            if($navResult){
//                $storeNav = $navResult->name;
//            }
//            echo "查找一条:".$storeNav."\n";
//            $isNavSql       = "SELECT * FROM store_nav WHERE `name` = '" . $storeNav . "' AND `store_id` = " . $rowGoods->store_id;
//
//            $isNav          = $mysqli->query($isNavSql);
//            if(!$isNav) {
//                $insertSql = "INSERT INTO store_nav(`store_id` , `name` , `created_at` , `updated_at`) VALUES (" . $rowGoods->store_id . ",'" . $storeNav . "','" . date('Y-m-d H:i:s', time()) . "','" . date('Y-m-d H:i:s', time()) . "')";
//                $mysqli->query($insertSql);
//                echo "成功插入一条,店铺名称:".$storeNav."\n";
//            }else{
//                $isNavResult = $isNav->fetch_object();
//                if (!$isNavResult) {
//                    $insertSql = "INSERT INTO store_nav(`store_id` , `name` , `created_at` , `updated_at`) VALUES (" . $rowGoods->store_id . ",'" . $storeNav . "','" . date('Y-m-d H:i:s', time()) . "','" . date('Y-m-d H:i:s', time()) . "')";
//                    $mysqli->query($insertSql);
//                    echo "成功插入一条,店铺名称:".$storeNav."\n";
//                }
//            }
//
//        }
//    }
//
//}


while ($row = $result->fetch_object()){

    $selectGoods = "SELECT * FROM store_goods WHERE name like '%". $row->name . "%'";
//    $selectGoods = "SELECT * FROM store_goods WHERE name like '%". "康师傅" . "%'";
=======
while ($row = $result->fetch_object()){

//    $selectGoods = "SELECT * FROM store_goods WHERE name like '%". $row->name . "%'";
    $selectGoods = "SELECT * FROM store_goods WHERE name like '%". "康师傅" . "%'";
>>>>>>> c9c15b22d9b82130289d881e636349f91f88c4b3
    $resultGoods = $mysqli->query($selectGoods);

    $goodsIds = array();
    while ($rowGoods = $resultGoods->fetch_object()){

        $categorySql = "SELECT * FROM goods_categories WHERE id = " . $row->c_id;
        $category = $mysqli->query($categorySql);
        $categoryResult = $category->fetch_object();
        if($categoryResult){
            $goodsCategoryId = $categoryResult->id;
            $navSql = "SELECT * FROM goods_categories WHERE `id` = " . $categoryResult->p_id;
            $nav = $mysqli->query($navSql);
            $navResult = $nav->fetch_object();
            if($navResult){
                $storeNav = $navResult->name;
            }
            $isNavSql       = "SELECT * FROM store_nav WHERE `name` = '" . $storeNav . "' AND `store_id` = " . $rowGoods->store_id;

<<<<<<< HEAD
            $isNav = $mysqli->query($isNavSql);

            $isNavResult = $isNav->fetch_object();

            if($isNavResult){
                $updateGooods = "UPDATE store_goods SET c_id = ". $goodsCategoryId . ", nav_id = " . $isNavResult->id . ", b_id = " . $row->id . " where id = " . $rowGoods->id;
                $mysqli->query($updateGooods);
                echo "成功更新一条:".$rowGoods->id."\n";
=======
            $isNav          = $mysqli->query($isNavSql);
            if(!$isNav) {
                $insertSql = "INSERT INTO store_nav(`store_id` , `name` , `created_at` , `updated_at`) VALUES (" . $rowGoods->store_id . ",'" . $storeNav . "','" . date('Y-m-d H:i:s', time()) . "','" . date('Y-m-d H:i:s', time()) . "')";
                $mysqli->query($insertSql);
            }else{
                $isNavResult = $isNav->fetch_object();
                if (!$isNavResult) {
                    $insertSql = "INSERT INTO store_nav(`store_id` , `name` , `created_at` , `updated_at`) VALUES (" . $rowGoods->store_id . ",'" . $storeNav . "','" . date('Y-m-d H:i:s', time()) . "','" . date('Y-m-d H:i:s', time()) . "')";
                    $mysqli->query($insertSql);
                }
>>>>>>> c9c15b22d9b82130289d881e636349f91f88c4b3
            }

        }
    }

}
<<<<<<< HEAD
=======

>>>>>>> c9c15b22d9b82130289d881e636349f91f88c4b3
die;
foreach ($storeGoods as $sgK => $sgV) {

    foreach ($sgV as $gv){
        $sql            = "INSERT INTO store_goods(`store_id` , `goods_id` , `name` , `out_price` , `img` , `spec` ,  `desc`) VALUES ($sgK" ."," . 0 . ",'" . $gv['name']. "', " . $gv['price'] . ",'" . $gv['img'] . "','" . $gv['spec'] . "','" . $gv['desc'] . "')";
        $result         = $mysqli->query($sql);

        if($result){
            echo "成功插入一条,店铺名称:".$gv['name']."\n";
        }else{
            echo "失败一条,店铺名称:" . $gv['name']."\n";
        }
    }

}

$mysqli->close();

?>