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

class StoreInfo extends Model{

    protected $table       = 'store_infos';

    /**
     *
     * 获取店铺信息
     */
    public function getStoreList($search = array() , $length=20 , $offset=0){
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
                      sca.name as category_name,
                      sc.store_logo,
                      sc.start_price,
                      sc.deliver,
                      sc.business_cycle,
                      sc.business_time,
                      sc.is_close,
                      sc.bell
                  FROM $this->table as si";

        $sql .= " LEFT JOIN store_configs as sc ON si.id = sc.store_id";
        $sql .= " LEFT JOIN store_categories as sca ON si.c_id = sca.id";

        $sql .= " WHERE si.is_del = 0 AND si.is_open = 1";

        if(isset($search['ids'])){
            $sql .= " AND si.id IN (" . $search['ids'] .")";
        }

        $sql .= " LIMIT $offset , $length ";

        $info = DB::select($sql);

        return $info;
    }

    /**
     *
     * 获取店铺信息
     */
    public function getStoreInfo($id){
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
                      sca.name as category_name,
                      sc.store_logo,
                      sc.start_price,
                      sc.deliver,
                      sc.business_cycle,
                      sc.business_time,
                      sc.is_close,
                      sc.bell,
                      sc.notice,
                      sc.point
                  FROM $this->table as si";

        $sql .= " LEFT JOIN store_configs as sc ON si.id = sc.store_id";
        $sql .= " LEFT JOIN store_categories as sca ON si.c_id = sca.id";

        $sql .= " WHERE si.id = $id AND si.is_del = 0 AND si.is_open = 1";

        $info = DB::select($sql);

        if(isset($info[0])){
            return $info[0];
        }else{
            return $info;
        }
    }

}
