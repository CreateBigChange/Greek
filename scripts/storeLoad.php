<?php
/**
 *
 * @copyright 2007-2012 Xiaoqiang.
 * @author Xiaoqiang.Wu <jamblues@gmail.com>
 * @version 1.01
 */

error_reporting(E_ALL);

date_default_timezone_set('Asia/ShangHai');

/** PHPExcel_IOFactory */
require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';


// Check prerequisites
if (!file_exists("goodsData.xls")) {
    exit("not found goodsData.xls.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("storeData.xls"); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数


$store = array();

/** 循环读取每个单元格的数据 */
$storeNum = 0;
for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
    $store[$storeNum] = array();
    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始

        if($column == 'A'){
            $store[$storeNum]['name']                   = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'F'){
            $store[$storeNum]['id_card_img']            = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'H'){
            $store[$storeNum]['business_license']       = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'J'){
            $store[$storeNum]['province']               = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'K'){
            $store[$storeNum]['city']                   = trim($sheet->getCell($column.$row)->getValue()) == NULL ? 0 : trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'L'){
            $store[$storeNum]['county']                 = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'M'){
            $store[$storeNum]['address']                = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'I'){
            $store[$storeNum]['contact_phone']          = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'P'){
            $store[$storeNum]['location']               = trim($sheet->getCell($column.$row)->getValue());
        }
    }
    $storeNum ++ ;
}

var_dump($store);die;

//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
//if ($mysqli->connect_error) {
//    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
//}
//
//
//foreach ($goods as $g) {
//    $selectCategor  = "SELECT * FROM goods_categories WHERE name = '" . $g['category'] . "' limit 1";
//    $selectBrand    = "SELECT * FROM goods_brand WHERE name  = '" . $g['brand'] . "' limit 1";
//
//    $categoryResult = $mysqli->query($selectCategor);
//    $brandResult    = $mysqli->query($selectBrand);
//
//
//    $categoryRow = $categoryResult->fetch_object();
//    if ($categoryRow ) {
//        unset($g['category']);
//        $g['c_id'] = $categoryRow->id;
//    }else{
//        $g['c_id'] = 74;
//    }
//
//    $brandRow = $brandResult->fetch_object();
//    if ($brandRow) {
//        unset($g['brand']);
//        $g['b_id']  = $brandRow->id;
//    }else{
//        $insertBrand = "INSERT INTO goods_brand(`c_id` , `name` , `is_del` , `created_at` , `updated_at`) VALUES (". $g['c_id'] .",'" . $g['brand']."',0 ,'". date('Y-m-d H:i:s' , time()) . "','" . date('Y-m-d H:i:s' , time()) ."');";
//        $mysqli->query($insertBrand);
//
//        $selectBrand    = "SELECT * FROM goods_brand WHERE name = '" . $g['brand'] . "' limit 1";
//
//        $brandResult    = $mysqli->query($selectBrand);
//        $brandRow = $brandResult->fetch_object();
//        $g['b_id'] = $brandRow->id;
//        unset($g['brand']);
//
//    }
//
//    $insertGoods = "INSERT INTO goods(`c_id` , `b_id` , `name` , `img` , `in_price` , `out_price` , `spec` , `created_at` , `updated_at`) VALUES (". $g['c_id'] ."," . $g['b_id'].", '". $g['name'] ."' ,'" . $g['img'] . "',".$g['in_price'].",".$g['out_price'] .",'".$g['spec'] ."','" . date('Y-m-d H:i:s' , time()) . "','" . date('Y-m-d H:i:s' , time()) ."');";
//
//    $result    = $mysqli->query($insertGoods);
//    if($result){
//        echo "成功插入一条,商品名称:".$g['name'];
//    }else{
//        echo "失败一条,商品名称:" . $g['name'];
//    }
//
//    $results->free();
//
//}
//
//$mysqli->close();

?>