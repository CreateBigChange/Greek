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
if (!file_exists("storeGoodsData.xls")) {
    exit("not found storeGoodsData.xls.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("storeGoodsData.xls"); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数
//
//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$mysqli->query("set names utf8");

$storeGoods             = array();
$storeName              = array();
$storeGoodsNum          = 0;
/** 循环读取每个单元格的数据 */
for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始

    $sname = trim($sheet->getCell('A'.$row)->getValue());


    $selectStore    = "SELECT * from store_infos WHERE name ='". $sname ."' limit 1";
//    $selectStore    = "SELECT * from store_infos"." limit 1";
    $result         = $mysqli->query($selectStore);

    if($result) {
        $resultRow = $result->fetch_object();

        if($resultRow){
            $storeId = $resultRow->id;
            if(!isset($storeGoods[$storeId])) {
                $storeGoods[$storeId] = array();
            }
        }else{
            file_put_contents('./store_goods.log', $sname."\n" ,FILE_APPEND);
            continue;
        }

    }else{
        file_put_contents('./store_goods.log', $sname."\n" ,FILE_APPEND);
        continue;
    }

    $length = count($storeGoods[$storeId]);
    $storeGoods[$storeId][$length] = array();

    for ($column = 'B'; $column <= $highestColumm; $column++) {

        if($column == 'B'){
            $storeGoods[$storeId][$length]['name'] = trim($sheet->getCell($column.$row)->getValue());
            $storeGoods[$storeId][$length]['desc'] = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'C'){
            $storeGoods[$storeId][$length]['spec'] = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'D'){
            $storeGoods[$storeId][$length]['img'] = trim($sheet->getCell($column.$row)->getValue());
        }
        if($column == 'J'){
            $storeGoods[$storeId][$length]['price'] = trim($sheet->getCell($column.$row)->getValue());
        }

    }

}


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