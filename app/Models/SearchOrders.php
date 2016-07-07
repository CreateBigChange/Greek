<?php
	/**
 * bannerModel
 * @author  yangxiansheng
 * @time    2016/07-006
 * @email   312479274@qq.com
 */
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model{

	protect $table;
	protect $sign=0;//用于标记作用
	protect $sql='';
	//获取查询的条件
	function getsql($key,$value){
		if(isset($condition)){
			if($this->sign==1)
			{
				$this->sql .="AND  $key LIKE % $value %";
			}
			else
			{
				$this->sql .=" $key LIKE % $value %";
				$this->sign=1;
			}
		}
	}
	
	//获取订单
	function getordersList(){
				$this->sql = "select
								consignee,
								consignee_tel,
								consignee_address,
								sname,
								true_name,
								smobile,
								pay_type_name,
								deliver,
								total
								from  $this->table";


				getsql('name',$search['name']);
				getsql('address',$search['address']);
				getsql('telphone',$search['telphone']);
				getsql('payway',$search['payway']);
	}
}
