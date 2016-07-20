<?php
	$con = new mysqli('rdssp928y1935gcevfl4.mysql.rds.aliyuncs.com:3306' , 'zxshop' , 'zxshop2015' , 'zxshop');
	if ($con->connect_error) {
		die('Could not connect: ' . $con->connect_error);
	}

	$sql = "select * from wd_member";

	$result = $con->query($sql);

	while ($row = $result->fetch_assoc()){
		$sql = "INSERT INTO `user_third_party` (`open_id` , `nick_name` , `avatar` , `type` , `wd_id`) 
				VALUES ( '"
			. $row['member_vid'] . "','" . $row['member_name'] . "','" . $row['member_avatar'] . "', 'weixin'" . "," . $row['member_id'] .
 		");";

		file_put_contents('./user_third_party.sql' , $sql , FILE_APPEND);
	}