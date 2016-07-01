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


$store          = array();

/** 循环读取每个单元格的数据 */
$storeNum = 0;
for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
    $store[$storeNum]           = array();
    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始

        if($column == 'A'){
            $store[$storeNum]['name']                   = trim($sheet->getCell($column.$row)->getValue());
            $store[$storeNum]['contacts']               = trim($sheet->getCell($column.$row)->getValue());
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
        if($column == 'O'){
            $store[$storeNum]['logo']                   = trim($sheet->getCell($column.$row)->getValue());
        }
    }
    $storeNum ++ ;
}

//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('127.0.0.1' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}


$storeId = 25;
foreach ($store as $s) {

    $insertStore = "INSERT INTO store_infos(`id` , `c_id` , `name` , `id_card_img` , `business_license` , `province` , `city` , `county` , `address` , `contacts` , `contact_phone` , `location` , `created_at` , `updated_at`) VALUES ($storeId ,". 1 .",'" . $s['name']."', '". $s['id_card_img'] ."' ,'" . $s['business_license'] . "','".$s['province']."','".$s['city'] ."','".$s['county'] ."','" . $s['address']. "','" . $s['contacts'] . "','" . $s['contact_phone'] . "','" . $s['location'] . "','" . date('Y-m-d H:i:s' , time()) . "','" . date('Y-m-d H:i:s' , time()) ."');";

    $result    = $mysqli->query($insertStore);
    if($result){

        $insertStoreConfig  = "INSERT INTO store_configs(`store_id` , `store_logo` , `created_at` , `updated_at`) VALUES ($storeId , '" . $s['logo'] . "','"  . date('Y-m-d H:i:s' , time()) . "','" . date('Y-m-d H:i:s' , time()) ."');";
        $insertStoreUser    = "INSERT INTO store_users(`store_id` , `account` , `real_name`  , `tel` , `created_at` , `updated_at`) VALUES ($storeId , '" . $s['contact_phone']. "','"  . $s['name'] . $s['contact_phone'] . "','"  . "','"  . date('Y-m-d H:i:s' , time()) . "','" . date('Y-m-d H:i:s' , time()) ."');";

        $mysqli->query($insertStoreConfig);
        $mysqli->query($insertStoreUser);

        $storeId ++;
        echo "成功插入一条,店铺名称:".$s['name']."\n";
    }else{
        echo "失败一条,店铺名称:" . $s['name']."\n";
    }

}

$mysqli->close();

?>