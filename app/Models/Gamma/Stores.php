<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models\Gamma;

use DB;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    protected $_store_users_table       = 'store_users';
    protected $_store_settings_table    = 'store_settings';
    protected $_store_configs_table     = 'store_configs';
    protected $_store_goods_table       = 'store_goods';
    protected $_store_nav_table         = 'store_nav';

    /**
     *
     * 申请入驻
     * @param data  array
     */
    public function setting($data){
        return DB::table($this->_store_settings_table)->insert($data);
    }

    /**
     *
     * 配置店铺
     * @param storeId   number
     * @param config    array
     */
    public function config($storeId , $config){
        return DB::table($this->_store_configs_table)->where('store_id' , $storeId)->update($config);
    }

    /**
     * 添加商品
     * @param data   array
     */
    public function addGoods($data){
        return DB::table($this->_store_goods_table)->insert($data);
    }

    /**
     * 获取商品列表
     * @param search   array
     */
    public function getGoodsList($storeId , $search = array() , $length = 20 , $offset = 0 ){

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


        $sql .= " WHERE sg.store_id = $storeId";
        if(isset($search['is_open'])){
            $sql .= " AND sg.is_open = ".$search['is_open'];
        }
        if(isset($search['name'])){
            $sql .= " AND sg.name LIKE '%" . $search['name'] . "%'";
        }
        $sql .= " AND sg.is_del = 0";

        $sql .= " ORDER BY created_at DESC";
        $sql .= " LIMIT $offset , $length";


        return DB::select($sql);

    }

    public function getGoodsTotalNum($storeId , $search = array()){
        $sql = "SELECT 
                    count(*) as num
                FROM $this->_store_goods_table AS sg ";

        $sql .= " LEFT JOIN goods_categories as gc ON gc.id = sg.c_id";
        $sql .= " LEFT JOIN goods_brand as gb ON gb.id = sg.b_id";
        $sql .= " LEFT JOIN $this->_store_nav_table as sn ON sn.id = sg.nav_id";


        $sql .= " WHERE sg.store_id = $storeId";
        if(isset($search['is_open'])){
            $sql .= " AND sg.is_open = ".$search['is_open'];
        }
        if(isset($search['name'])){
            $sql .= " AND sg.name LIKE '%" . $search['name'] . "%'";
        }
        $sql .= " AND sg.is_del = 0";

        $num = DB::select($sql);

        return $num[0]->num;
    }

    /**
     * 添加栏目
     * @param data   array
     */
    public function addNav($data){
        return DB::table($this->_store_nav_table)->insert($data);
    }

    /**
     *
     * 更新栏目
     * @param navId     number
     * @param storeId   number
     * @param data      array
     */
    public function updateNav($navId , $storeId , $data){
        return DB::table($this->_store_nav_table)->where('id' , $navId)->where('store_id' , $storeId)->update($data);
    }

    /**
     *
     * 获取栏目
     * @param storeId   number
     */
    public function getNav($storeId){
        return DB::table($this->_store_nav_table)->where('store_id' , $storeId)->get();
    }

    /**
     *
     * 获取单个栏目
     * @param navId     number
     * @param storeId   number
     */
    public function getNavInfo($navId , $storeId){
        return DB::table($this->_store_nav_table)->where('id' , $navId)->where('store_id' , $storeId)->where('is_del' , 0)->first();
    }

    /**
     *
     * 删除栏目
     * @param navId     number
     * @param storeId   number
     */
    public function delNav($navId , $storeId){

        //统计此栏目下是否有售商品
        $goodsNum = DB::table($this->_store_goods_table)->where('nav_id' , $navId)->where('store_id' , $storeId)->count();
        if($goodsNum == 0){
            return -1;
        }else{
            return DB::table($this->_store_nav_table)->where('id' , $navId)->update(array('is_del' => 1));
        }
    }
}
