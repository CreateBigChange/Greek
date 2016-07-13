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
if (!file_exists("storeGoods.xls")) {
    exit("not found storeGoods.xls.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("storeGoods.xls"); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数
//
$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
//$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$mysqli->query("set names utf8");

$i = 1;

/** 循环读取每个单元格的数据 */
for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始

    $sname = trim($sheet->getCell('A'.$row)->getValue());


    $selectStore    = "SELECT * from store_infos WHERE name ='". $sname ."' limit 1";
    $result         = $mysqli->query($selectStore);

    if($result) {
        $resultRow = $result->fetch_object();

        if($resultRow){
            $storeId = $resultRow->id;
        }else{
            file_put_contents('./store_goods.log', $sname."\n" ,FILE_APPEND);
            continue;
        }

    }else{
        file_put_contents('./store_goods.log', $sname."\n" ,FILE_APPEND);
        continue;
    }

    for ($column = 'B'; $column <= $highestColumm; $column++) {

        $value = trim($sheet->getCell($column.$row)->getValue());
        if($column == 'B'){
            $name = $value;
            $desc = $value;
        }
        if($column == 'C'){
            $selectBrand = "SELECT * FROM goods_brand WHERE `name` = '" . $value ."'";
            $selectBrandResult = $mysqli->query($selectBrand);
            $brand = $selectBrandResult->fetch_object();
            if($brand) {
                $b_id = $brand->id;
            }
        }
        if($column == 'D'){
            $selectCategory = "SELECT * FROM goods_categories WHERE `name` = '" . $value ."'";
            $selectCategoryResult = $mysqli->query($selectCategory);
            $category = $selectCategoryResult->fetch_object();
            if($category) {
                $c_id = $category->id;
            }

            $selectNav = "SELECT * FROM store_nav WHERE `name` = '" . $value ."' AND `store_id` = {$storeId}";
            $selectNavResult = $mysqli->query($selectNav);
            $nav = $selectNavResult->fetch_object();
            if($nav) {
                $nav_id = $nav->id;
            }

        }
        if($column == 'E'){
            $spec = $value;
        }
        if($column == 'F'){
            $img = $value;
        }
        if($column == 'L'){
            $price = $value;
        }

    }
    $sql            = "INSERT INTO store_goods(`store_id` , `c_id` , `nav_id` , `b_id` , `goods_id` , `name` , `out_price` , `img` , `spec` ,  `desc`) VALUES ($storeId" ."," . $c_id . "," . $nav_id . "," . $b_id . "," . 0 . ",'" . $name. "', " . $price . ",'" . $img . "','" . $spec . "','" . $desc . "')";
    $result         = $mysqli->query($sql);

    if($result){
        echo "成功插入{$i}条 , 店铺名称".$sname.",商品名称:".$name."\n";
        $i++;
    }else{
        echo "失败一条,店铺名称".$sname.",商品名称:".$name."\n";
    }



}


$mysqli->close();

?>