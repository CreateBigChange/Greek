<?php
/**
 * bannerModel
 * @author  wuhui
 * @time    2016/06-08
 * @email   wuhui904107775@qq.com
 */
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\StoreConfig;
use App\Models\StoreCategory;
use App\Models\StoreActivity;

class StoreInfo extends Model{

    protected $table       = 'store_infos';

    /**
     *
     * 获取店铺信息
     */
    public function getStoreList($search = array() , $length=20 , $offset=0){
        $storeConfigModel   = new StoreConfig();
        $storeCategoryModel = new StoreCategory();

        $sql  = "select 
                      si.id,
                      si.name as store_name,
                      si.province,
                      si.city,
                      si.county,
                      si.address,
                      si.contacts,
                      si.contact_phone,
                      si.is_open,
                      si.is_checked,
                      si.id_card_img,
                      si.business_license,
                      si.contact_email,
                      si.location,
                      sca.name as category_name,
                      sc.store_logo,
                      sc.start_price,
                      sc.deliver,
                      sc.business_cycle,
                      sc.business_time,
                      sc.is_close,
                      sc.bell,
                      sc.money,
                      sc.balance,
                      sc.notice,
                      sc.score,
                      si.is_del,
                      si.created_at,
                      si.updated_at,
                      si.is_sign
                  FROM $this->table as si";

        $sql .= " LEFT JOIN ".$storeConfigModel->getTable()." as sc ON si.id = sc.store_id";
        $sql .= " LEFT JOIN ".$storeCategoryModel->getTable()." as sca ON si.c_id = sca.id";

        $sql .= " WHERE si.is_del = 0";
        if(isset($search['is_open'])){
            $sql .= " AND si.is_open = " . $search['is_open'];
        }else{
            $sql .= " AND si.is_open = 1";
        }

        if(isset($search['ids'])){
            $sql .= " AND si.id IN (" . $search['ids'] .")";
        }

        if(isset($search['name']) && !empty($search['name'])){
            $sql .= " AND si.store_name LIKE '%" . $search['name'] . "%'";
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
        if(isset($search['is_checked'])){
            $sql .= " AND si.is_checked = " . $search['is_checked'] ;
        }else{
            $sql .= " AND si.is_checked = 1" ;
        }

        $sql .= " LIMIT $offset , $length ";

        $info = DB::select($sql);

        $ids = array();
        foreach ($info as $si){
            $ids[] = $si->id;
        }

        $storeActivityModel = new StoreActivity();

        $activity = $storeActivityModel->getStoreActivityByStoreIds($ids);

        //计算店铺是否在营业时间内
        $today      = date('Y-m-d' , time());
        $time       = date('H:i' , time());

        $week_tmp   = date('w', strtotime($today));

        $weekTime = array(
            '一',
            '二',
            '三',
            '四',
            '五',
            '六',
            '日'
        );

        $week = $weekTime[$week_tmp - 1];

        $restStore = array();
        $i = 0;
        foreach ($info as $si){

            $si->activity = array();
            foreach ($activity as $a){
                if($si->id == $a->store_id){
                    $si->activity[] = $a;
                }
            }

            $si->isDoBusiness = 1;

            if($si->business_cycle == "每天"){
                $weeks = array(
                    '一',
                    '二',
                    '三',
                    '四',
                    '五',
                    '六',
                    '日'
                );
            }elseif($si->business_cycle == "工作日") {
                $weeks = array(
                    '一',
                    '二',
                    '三',
                    '四',
                    '五',
                );
            }else {
                $weeks = explode(',', $si->business_cycle);
            }

            if (!in_array($week, $weeks)) {
                $si->isDoBusiness = 0;
            }

            /**
             * 如果设置了营业时间
             */
            if($si->business_time) {
                $starTime = explode('-', $si->business_time)[0];
                $endTime = explode('-', $si->business_time)[1];

                if($time <= $starTime || $time > $endTime ){
                    $si->isDoBusiness = 0;
                }
            }else{
                $si->isDoBusiness = 0;
            }

            if($si->isDoBusiness){
                $restStore[] = $si;
                unset($info[$i]);
            }

            $i++;

        }

        $info = array_merge($info , $restStore);

        return $info;
    }

    /**
     *
     * 获取店铺信息
     */
    public function getStoreInfo($id){
        $info = $this->getStoreList(array('ids'=>$id) , 1  , 0 );

        if(isset($info[0])){
            return $info[0];
        }else{
            return $info;
        }

    }


    /**
     *
     * 获取店铺信息
     */
    public function getStoreTotalNum($search = array() ){
        $sql  = "select 
                      count(*) as num
                  FROM $this->table as si";

        $sql .= " LEFT JOIN store_configs as sc ON si.id = sc.store_id";
        $sql .= " LEFT JOIN store_categories as sca ON si.c_id = sca.id";

        $sql .= " WHERE si.is_del = 0";
        if(isset($search['is_open'])){
            $sql .= " AND si.is_open = " . $search['is_open'];
        }else{
            $sql .= " AND si.is_open = 1";
        }

        if(isset($search['ids'])){
            $sql .= " AND si.id IN (" . $search['ids'] .")";
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
        if(isset($search['is_checked'])){
            $sql .= " AND si.is_checked = " . $search['is_checked'] ;
        }else{
            $sql .= " AND si.is_checked = 1" ;
        }

        $num = DB::select($sql);

        return $num[0]->num;
    }


    /**
     * @param $data
     * 添加店铺
     */
    public function addStore($data){

        $storeConfigModel   = new StoreConfig();

        $storeId = DB::table($this->table)->insertGetId($data);
        if($storeId){
            $config = array(
                'store_id' => $storeId
            );
            DB::table($storeConfigModel->getTable())->insert($config);

        }

        return $storeId;
    }

    /**
     * @param $storeId
     * @param $data
     * 更新店铺
     */
    public function updateStore($storeId , $data){
        return DB::table($this->table)->where('id' , $storeId)->update($data);
    }


}
