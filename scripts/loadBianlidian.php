<?php

header ( "Content-type:text/html;charset=utf-8" );

/** PHPExcel_IOFactory */
require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

//
//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}


$mysqli->query("set names utf8");


error_reporting(E_ALL);

date_default_timezone_set('Asia/ShangHai');

// Check prerequisites
if (!file_exists("bianlidianShop.xls")) {
    exit("not found bianlidianShop.xls.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("bianlidianShop.xls"); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数

$storeIds = array();
for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
        $storeName = trim($sheet->getCell($column.$row)->getValue());

        if($column == 'A'){

            $selectStore = "SELECT * FROM store_infos WHERE name = '" . $storeName . "'";
            $resultSQL = $mysqli->query($selectStore);

            $result = $resultSQL->fetch_object();
            if($result){
                $storeIds[] = $result->id;
            }


        }

    }
}


$selectGoods = "SELECT * FROM zxshop.goods where id <= 99 ";

$result = $mysqli->query($selectGoods);

$storeIds = array_merge($storeIds , [21]);

while ($row = $result->fetch_object()) {

    foreach ($storeIds as $sid) {

        $storeGoodsSql = "INSERT INTO store_goods(`store_id` , `c_id` , `b_id` , `goods_id` , `name` , `out_price` , `img` , `spec` ,  `desc`) VALUES ($sid". "," . $row->c_id ."," . $row->b_id . "," . $row->id . ",'" . $row->name . "', " . $row->out_price . ",'" . $row->img . "','" . $row->spec . "','" . $row->desc . "')";

        if(!$mysqli->query($storeGoodsSql)){
            file_put_contents('./bianlidianShop.log', $sid."\n" ,FILE_APPEND);
        }else{
            var_dump( "成功插入一条,店铺ID:".$sid);
        }
    }
}

$mysqli->close();

?>