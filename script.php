<?php
$conSrc = mysqli_connect("localhost","root","123456","zxshop");
//设置编码字符集
mysqli_query($conSrc,"set names 'utf8' ");
mysqli_query($conSrc,"set character_set_client=utf8");
mysqli_query($conSrc,"set character_set_results=utf8");

if(!$conSrc)
{
	die("连接失败!");
}


transfer($conSrc);

updateId($conSrc);


/*
*数据库store_goods转移到goods中
*
*/

function transfer($conSrc)
{
	$totle=0;//总数目
	$insertNum=0;//插入数目
	$dropNum=0;//放弃数目

	$result = mysqli_query($conSrc,"select * from store_goods");
	while($row=mysqli_fetch_array($result))
	{	
		//查询goods所有记录再然后遍历

		$myresult=mysqli_query($conSrc,"select * from goods where name='".$row['name']."' and spec = '".$row['spec']."'");
			
			if(!mysqli_num_rows($myresult))
			{
				
		//记录不存在，记录
				$c_id=$row['c_id'];
				$b_id=$row['b_id'];
				$name=$row['name'];
				$img=$row['img'];
				$in_price=$row['in_price'];
				$out_price=$row['out_price'];
				$give_points=$row['give_points']; 
				$spec=$row['spec'];
				$desc=$row['desc'];
				$stock=$row['stock'];
				$is_open=$row['is_open'];
				$is_checked=$row['is_checked'];
				$is_del=$row['is_del'];
				$created_at=$row['created_at'];
				$updated_at=$row['updated_at'];
				if($in_price=="")
				{
			
					$in_price=0;
			
				}
				if($spec==null)
				{
					$spec=null;

				}
				if($desc==null)
				{
					$desc=null;

				}
		$totle++;
		$insertNum++;		
		echo "name: $name 规格:$spec 在goods不存在进行插入\n";
		$sql ="insert into goods  (
							`c_id`,
							`b_id`,
							`name`,
							`img`,
							`in_price`,
							`out_price`,
							`give_points`,
							`spec`,
							`desc`,
							`stock`,
							`is_open`,
							`is_checked`,
							`is_del`,
							`created_at`,
							`updated_at`
						)
					values
						(
					'$c_id',
                    '$b_id',
                    '$name',
                    '$img',
                    '$in_price',
                    '$out_price',
                    '$give_points',
                    '$spec',
                    '$desc',
                    '$stock',
                    '$is_open',
                    '$is_checked',
                    '$is_del',
                    '$created_at',
                    '$updated_at'
			   			);";	
		//在goods中插入
				
				if(mysqli_query($conSrc,$sql))
				{
					echo "name: $name 规格:$spec 在goods不存在进行 插入成功\n";
				}
				else{
					echo  mysqli_error($conSrc);
				}
			}
			else
			{
				echo "id:".$row['name']." 规格:".$row['spec']."在goods已存在不进行插入\n";
				$totle++;
				$dropNum++;	
			}
	
	}
			echo "一共插入 ".$insertNum."条记录 \n";
			echo "一共放弃 ".$dropNum."条记录 \n";
			echo "总数目 ".$totle."条记录 \n";
}


/*
*由goods的id更新store_goods的id
*
*/

function updateId($conSrc){
$id;
$name;
$spec;
$totle=0;//记录总数
$successNum=0;
$errorNum=0;
//遍历good 取出 id,name,spec
$result = mysqli_query($conSrc,"select * from goods ");
while($row = mysqli_fetch_array($result)){
	
	$id=$row['id'];
	$name=$row['name'];
	$spec=$row['spec'];

	if($spec=null)
	{
		$spe="";
	}
		//在store_goods中进行更新
	if(mysqli_query($conSrc,"update store_goods set goods_id = $id where name = '".$name."' and spec ='".$spec."'"))
		{
			echo "id：$id  name:$name spec:$spec 进行更新\n";
			$successNum++;
		}
	else
		{
			echo "id：$id  name:$name spec:$spec goods的记录不存于store_goods中在无法更新\n";
			$errorNum++;
		}

	if($name=""){
		$errorNum++;
		echo"记录 name:$name spec:$spec 由于 spec 不存在无法进行更新";
		$totle++;
		continue;
	}

$totle++;
}
echo "共更新记录 $successNum 条\n";
echo "失败记录 $errorNum 条\n";
echo "总记录 $totle 条\n";
}
//关闭数据库
mysqli_close($conSrc);