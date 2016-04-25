<?php

namespace App\Models\Alpha;

use DB;
use Illuminate\Database\Eloquent\Model;

class StoreInfos extends Model
{
	protected $_store_infos_table       	= 'store_infos';
	protected $_store_configs_table       	= 'store_configs';
	protected $_store_categories_table      = 'store_categories';

	/**
	 * @return mixed
	 * 获取店铺列表
	 */
	public function getStoreInfoList($search = array()){
		$sql = "SELECT
 					si.id,
 					si.c_id,
 					category.name as category_name,
 					si.name,
 					si.description,
 					si.business_license,
 					si.id_card_img,
                    ap.id as province_id,
                    ap.name as province,
                    aci.id as city_id,
                    aci.name as city,
                    aco.id as county_id,
                    aco.name as county,
                    si.address,
                    si.contacts,
                    si.contact_phone,
                    si.contact_email,
                    si.is_open,
                    si.is_checked,
                    si.is_del,
                    si.created_at,
                    si.updated_at
                    
 					FROM $this->_store_infos_table AS si ";

		$sql .= " LEFT JOIN areas as ap ON ap.id = si.province";
		$sql .= " LEFT JOIN areas as aci ON aci.id = si.city";
		$sql .= " LEFT JOIN areas as aco ON aco.id = si.county";
		$sql .= " LEFT JOIN $this->_store_categories_table as category ON category.id = si.c_id";

		if( isset($search['ids']) && !empty($search['ids']) ){
			$sql .= " WHERE si.id IN (". implode(',' , $search['ids']) .")";
		}

		$sql .= " ORDER BY created_at DESC";

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
		$info = $this->getStoreInfoList(array('ids'=>array($id)));

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

}