<?php



//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}


$mysqli->query("set names utf8");


$storeGoodsSql = "SELECT * FROM `store_goods`";

$storeGoodsResult = $mysqli->query($storeGoodsSql);

while ($row = $storeGoodsResult->fetch_object()){

    $name = $row->name;
    $spec = $row->spec;

    $issetGoodsSql = "SELECT * FROM goods WHERE `name` = '" . $name . "' AND `spec` = '" . $spec . "' LIMIT 1";
    $issetGoodsResult = $mysqli->query($issetGoodsSql);
    if(!$issetGoodsResult->fetch_object()){
        $c_id       = $row->c_id;
        $b_id       = $row->b_id;
        $img        = $row->img;
        $in_price   = $row->in_price;
        $out_price  = $row->out_price;
        $spec       = $row->spec;
        $desc       = $row->desc;
        $is_open    = 1;
        $is_checked = 1;

        $insertGoodsSql = "INSERT INTO `goods` (`name` , `c_id` , `b_id` , `img` , `in_price` , `out_price` , `spec` , `desc` , `is_open` , `is_checked`) VALUES ('"
                        . $name . "' ,"

                          .")";

    }
}


$mysqli->close();

?>