<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models\Sigma;

use DB;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    protected $_store_users_table       = 'store_users';
    protected $_store_settings_table    = 'store_settlings';
    protected $_store_configs_table     = 'store_configs';
    protected $_store_infos_table       = 'store_infos';
    protected $_store_goods_table       = 'store_goods';
    protected $_store_nav_table         = 'store_nav';
    protected $_store_categories_table  = 'store_categories';

    /**
     *
     * 获取店铺信息
     */
    public function getStoreList($search = array()){
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
                  FROM $this->_store_infos_table as si";

        $sql .= " LEFT JOIN store_configs as sc ON si.id = sc.store_id";
        $sql .= " LEFT JOIN store_categories as sca ON si.c_id = sca.id";

        $sql .= " WHERE si.is_del = 0 AND si.is_open = 1";

        if(isset($search['ids'])){
            $sql .= " AND si.id IN (" . $search['ids'] .")";
        }

        $info = DB::select($sql);

        return $info;
    }

    /**
     * 获取商品列表
     * @param storeId   number
     * @param search   array
     * @param length   number
     * @param offset   number
     */
    public function getStoreGoodsList($search = array() , $length = 20 , $offset = 0 ){

        $sql = "SELECT 
                    sg.id ,
                    sg.store_id,
                    sg.name,
                    sg.img,
                    sg.in_price,
                    sg.out_price,
                    sg.give_points,
                    sg.spec,
                    sg.desc,
                    sg.stock,
                    sg.is_open,
                    sg.is_checked,
                    sg.created_at,
                    sg.updated_at,
                    gc.name AS category_name ,
                    gc.id AS category_id ,
                    gb.id AS brand_id,
                    gb.name AS brand_name,
                    sn.id AS nav_id,
                    sn.name AS nav_name
                FROM $this->_store_goods_table AS sg ";

        $sql .= " LEFT JOIN goods_categories as gc ON gc.id = sg.c_id";
        $sql .= " LEFT JOIN goods_brand as gb ON gb.id = sg.b_id";
        $sql .= " LEFT JOIN $this->_store_nav_table as sn ON sn.id = sg.nav_id";


        $sql .= " WHERE sg.is_open = 1";

        if(isset($search['store_id'])) {
            $sql .= " AND sg.store_id = " . $search['store_id'];
        }

        if(isset($search['nav_id'])){
            $sql .= " AND sg.nav_id = ".$search['nav_id'];
        }
        if(isset($search['name'])){
            $sql .= " AND sg.name LIKE '%" . $search['name'] . "%'";
        }
        if(isset($search['id'])){
            $sql .= " AND sg.id = ".$search['id'];
        }
        if(isset($search['ids'])){
            $sql .= " AND sg.id IN (".implode(',' , $search['ids']) .")";
        }
        $sql .= " AND sg.is_del = 0";

        if(isset($search['sort_stock']) && $search['sort_stock'] == 'desc'){
            $sql .= " ORDER BY stock DESC ";
        }elseif(isset($search['sort_stock']) && $search['sort_stock'] == 'asc'){
            $sql .= " ORDER BY stock ASC ";
        }else{
            $sql .= " ORDER BY created_at DESC";
        }

        $sql .= " LIMIT $offset , $length";


        return DB::select($sql);

    }

    /**
     * 获取商品列表
     * @param storeId   number
     * @param search   array
     */
    public function getStoreGoodsTotalNum( $search = array()){
        $sql = "SELECT 
                    count(*) as num
                FROM $this->_store_goods_table AS sg ";

        $sql .= " LEFT JOIN goods_categories as gc ON gc.id = sg.c_id";
        $sql .= " LEFT JOIN goods_brand as gb ON gb.id = sg.b_id";
        $sql .= " LEFT JOIN $this->_store_nav_table as sn ON sn.id = sg.nav_id";

        $sql .= " WHERE sg.is_open = 1";

        if(isset($search['store_id'])) {
            $sql .= " AND sg.store_id = " . $search['store_id'];
        }
        if(isset($search['nav_id'])){
            $sql .= " AND sg.nav_id = ".$search['nav_id'];
        }
        if(isset($search['name'])){
            $sql .= " AND sg.name LIKE '%" . $search['name'] . "%'";
        }
        $sql .= " AND sg.is_del = 0";

        $num = DB::select($sql);

        return $num[0]->num;
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
                      sc.bell
                  FROM $this->_store_infos_table as si";

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


    /**
     *
     * 获取栏目
     * @param storeId   number
     */
    public function getNav($storeId){
        return DB::table($this->_store_nav_table)->where('store_id' , $storeId)->where('is_del' , 0)->orderBy('sort','ASC')->orderBy('updated_at','desc')->get();
    }

    /**
     *
     * 获取栏目
     * @param storeId   number
     */
    public function getStoreCategory(){
        return DB::table($this->_store_categories_table)->where('is_del' , 0)->orderBy('sort','ASC')->orderBy('updated_at','desc')->get();
    }


}
