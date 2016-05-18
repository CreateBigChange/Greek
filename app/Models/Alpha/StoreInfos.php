<?php

namespace App\Models\Alpha;

use DB;
use Illuminate\Database\Eloquent\Model;

class StoreInfos extends Model
{
	protected $_store_infos_table       	= 'store_infos';
	protected $_store_configs_table       	= 'store_configs';
	protected $_store_categories_table      = 'store_categories';
	protected $_store_settlings_table		= 'store_settlings';
	protected $_store_users_table			= 'store_users';

	/**
	 * @return mixed
	 * 获取店铺列表
	 */
	public function getStoreInfoList($length=20 , $offset=0 , $search = array()){
		$sql = "SELECT
 					si.id,
 					si.c_id,
 					category.name as category_name,
 					si.name,
 					si.description,
 					si.business_license,
 					si.id_card_img,
                    si.address,
                    si.province,
                    si.city,
                    si.county,
                    si.contacts,
                    si.location,
                    si.contact_phone,
                    si.contact_email,
                    si.is_open,
                    si.is_checked,
                    si.is_del,
                    si.created_at,
                    si.updated_at,
                    si.is_sign
                    
 					FROM $this->_store_infos_table AS si ";

//		$sql .= " LEFT JOIN areas as ap ON ap.id = si.province";
//		$sql .= " LEFT JOIN areas as aci ON aci.id = si.city";
//		$sql .= " LEFT JOIN areas as aco ON aco.id = si.county";
		$sql .= " LEFT JOIN $this->_store_categories_table as category ON category.id = si.c_id";

		$sql .= " WHERE si.is_del = 0";

		if( isset($search['ids']) && !empty($search['ids']) ){
			$sql .= " AND si.id IN (". implode(',' , $search['ids']) .")";
		}

		if(isset($search['name']) && !empty($search['name'])){
			$sql .= " AND si.name LIKE '%" . $search['name'] . "%'";
		}
		if(isset($search['contacts']) && !empty($search['contacts'])){
			$sql .= " AND si.contacts LIKE '%" . $search['contacts'] . "%'";
		}
		if(isset($search['contact_phone']) && !empty($search['contact_phone'])){
			$sql .= " AND si.contact_phone LIKE '%" . $search['contact_phone'] . "%'";
		}
		if(isset($search['c_id']) && !empty($search['c_id'])){
			$sql .= " AND si.c_id = " . $search['c_id'] ;
		}
		if(isset($search['province']) && !empty($search['province'])){
			$sql .= " AND si.province = " . $search['province'] ;
		}
		if(isset($search['city']) && !empty($search['city'])){
			$sql .= " AND si.city = " . $search['city'] ;
		}
		if(isset($search['county']) && !empty($search['county'])){
			$sql .= " AND si.county = " . $search['county'] ;
		}
		if(isset($search['address']) && !empty($search['address'])){
			$sql .= " AND si.address LIKE '%" . $search['address'] . "%'";
		}
		if(isset($search['is_open']) && !empty($search['is_open'])){
			$sql .= " AND si.is_open = " . $search['is_open'] ;
		}
		if(isset($search['is_checked']) && !empty($search['is_checked'])){
			$sql .= " AND si.is_checked = " . $search['is_checked'] ;
		}

		$sql .= " ORDER BY created_at DESC";

		$sql .= " LIMIT $offset , $length ";

		$storeList = DB::select($sql);

		return $storeList;
	}

	/**
	 *
	 * 获取店铺总数
	 */
	public function getStoreInfoTotalNum($search = array())
	{
		$sql = "SELECT
 					count(*) as num
                    
 					FROM $this->_store_infos_table AS si ";
		
		$num = DB::select($sql);

		return $num[0]->num;
	}

	/**
	 *
	 * 获取店铺信息
	 */
	public function getStoreInfo($id){
		$info = $this->getStoreInfoList(1  , 0 , array('ids'=>array($id)));

		if(isset($info[0])){
			return $info[0];
		}else{
			return '';
		}
	}

	/**
	 * @param $data
	 * 添加店铺
	 */
	public function addStore($data){
		$storeId = DB::table($this->_store_infos_table)->insertGetId($data);
		if($storeId){
			$config = array(
				'store_id' => $storeId
			);
			DB::table($this->_store_configs_table)->insert($config);

			
		}

		return $storeId;
	}

	/**
	 * @param $storeId
	 * @param $data
	 * 更新店铺
	 */
	public function updateStore($storeId , $data){
		return DB::table($this->_store_infos_table)->where('id' , $storeId)->update($data);
	}

	/**
	 *
	 * 获取分类列表
	 */
	public function getStoreCategoriesList(){
		return DB::table($this->_store_categories_table)->get();
	}

	/**
	 * @param $pid
	 * @return mixed
	 * 获取区域列表
	 */
	public function getAreas($pid){
		return DB::table('areas')->where('parent' , $pid)->get();
	}

	/**
	 * 申请入驻列表
	 */
	public function getSettlings(){
		return DB::table($this->_store_settlings_table)
			->select(
				'store_settlings.id',
				'store_settlings.name',
				'store_settlings.contact',
				'store_settlings.address',
				'store_settlings.status',
				'store_settlings.created_at',
				'p.id AS province_id',
				'p.name AS province',
				'ci.id AS city_id',
				'ci.name AS city',
				'co.id AS county_id',
				'co.name AS county'
			)
			->leftJoin('areas as p' , 'p.id' , '=' , 'store_settlings.province')
			->leftJoin('areas as ci' , 'ci.id' , '=' , 'store_settlings.city')
			->leftJoin('areas as co' , 'co.id' , '=' , 'store_settlings.county')
			->orderBy('status' , 'ASC')
			->orderBy('created_at' , 'DESC')
			->get();
	}

	/**
	 * 完成入驻
	 */
	public function delSettlings($id){
		return DB::table($this->_store_settlings_table)->where('id' , $id)->delete();
	}

	/**
	 *
	 * 创建店铺用户
	 */
	public function addStoreUser($data){
		return DB::table($this->_store_users_table)->insert($data);
	}

	/**
	 * 获取店铺用户
	 */
	public function getStoreUserList($storeId = 0){
		$sql = DB::table($this->_store_users_table)
			->select(
				'store_users.id',
				'store_users.account',
				'store_users.real_name',
				'store_users.tel',
				'store_users.created_at',
				'si.name as sname',
				'si.id as sid'
			)
			->leftJoin("$this->_store_infos_table as si", 'si.id' , '=' , 'store_users.store_id');
		if($storeId != 0){
			$sql->where('store_id' , $storeId);
		}

		return $sql->get();
	}

	/**
	 * 添加店铺分类
	 */
	public function addStoreCategory($data){
		return DB::table($this->_store_categories_table)->insert($data);
	}

	/**
	 * 修改店铺分类
	 */
	public function updateStoreCategory($id , $data){
		return DB::table($this->_store_categories_table)->where('id' , $id)->update($data);
	}

	/**
	 * 获取单个店铺分类
	 */
	public function getStoreCategoryById($id){
		return DB::table($this->_store_categories_table)->where('id' , $id)->first();
	}
}