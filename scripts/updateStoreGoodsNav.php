<?php

//
//$mysqli = new mysqli('rm-wz9s022vq140vwejy.mysql.rds.aliyuncs.com' , 'zxshop' , 'zxhy-2016' , 'zxshop');
$mysqli = new mysqli('192.168.0.249' , 'root' , '123456' , 'zxshop');
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$selectBrand = "SELECT * FROM goods";

$result = $mysqli->query($selectBrand);

while ($row = $result->fetch_object()){
    var_dump($row->name);
}
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