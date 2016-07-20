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
if (!file_exists("shuiguoShop.xls")) {
    exit("not found shuiguoShop.xls.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("shuiguoShop.xls"); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数

$store = array();

$storeNum = 0;
for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始

    $store[$storeNum] = array();
    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
        $value = trim($sheet->getCell($column.$row)->getValue());

        if($column == 'B'){
            $store[$storeNum]['name'] = $value;
        }
        if($column == 'c'){
            $store[$storeNum]['name'] = $value;
        }

    }
}


$mysqli->close();

?>