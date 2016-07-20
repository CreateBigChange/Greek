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
if (!file_exists("zuobiao.xls")) {
    exit("not found zuobiao.xls.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("zuobiao.xls"); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数



$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'jsx' , '*pzsJqbd^6rvTeuz' , 'jsx');
//$mysqli = new mysqli('127.0.0.1' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}


/** 循环读取每个单元格的数据 */
for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
    $storeId = 0;
    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始

        $value = trim($sheet->getCell($column.$row)->getValue());

        if($column == 'A'){
            $storeName = $value;

            $findStoreSql = "SELECT * FROM `store_infos` WHERE name = '" . $storeName . "'";
            $findResult = $mysqli->query($findStoreSql);
            if($findResult){
                $store = $findResult->fetch_object();
                if($store) {
                    $storeId = $store->id;
                }else{
                    continue;
                }
            }else{
                continue;
            }
        }

        if($column  == 'B'){
            $province = $value;
        }
        if($column == 'C'){
            $city = $value;
        }
        if($column == 'D'){
            $county = $value;
        }
        if($column == 'E'){
            $address = $value;
        }
        if($column == 'F'){
            $location = $value;
        }

    }

    if($storeId != 0){

        $updateSql = "UPDATE `store_infos` SET `province` = '" . $province . "' , `city` = '" . $city . "' , `county` = '" . $county . "' , `address` = '" . $address . "' , `location` = '" . $location. "' WHERE id = " . $storeId;

        $mysqli->query($updateSql);
    }

}


$mysqli->close();

?>