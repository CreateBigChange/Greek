<?php
require '../vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;


//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}



$mysqli->query("set names utf8");




// Check prerequisites
if (!file_exists("store.xls")) {
    exit("not found store.xls.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("store.xls"); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数


/** 循环读取每个单元格的数据 */
for ($row = 2; $row <= $highestRow; $row++) {//行数是以第1行开始
    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始

        $value = trim($sheet->getCell($column . $row)->getValue());

        if($column == 'A'){
            $storeId = $value;
        }

        if($column == 'C'){
            $money = $value;
        }



    }

    if ($money != 0) {

        $money = 1000;

        $updateSql = "UPDATE `store_configs` SET `construction_money` = " . $money . " , `is_collect_construction_money` = 1" . " WHERE `store_id` = " . $storeId . ";\n";

        file_put_contents('construction_money.sql' , $updateSql , FILE_APPEND);
        //$mysqli->query($updateSql);
    }

}

$mysqli->close();

?>