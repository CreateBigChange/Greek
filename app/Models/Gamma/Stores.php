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
    protected $_store_settings_table    = 'store_settlings';
    protected $_store_configs_table     = 'store_configs';
    protected $_store_infos_table       = 'store_infos';
    protected $_store_goods_table       = 'store_goods';
    protected $_store_nav_table         = 'store_nav';

    /**
     *
     * 地区
     * @param pid  number
     */
    public function areas($pid){
        return DB::table('areas')->where('parent' , $pid)->get();
    }

    /**
     *
     * 所有地区
     */
    public function allAreas(){
        $parent =  DB::table('areas')->where('deep'  , 1)->get();

        $son = DB::table('areas')->where('deep' , 2)->get();

        $grandson = DB::table('areas')->where('deep' , 3)->get();

        foreach ($son as $s) {
            $s->son = array();
            foreach ($grandson as $g) {
                if($s->id == $g->parent){
                    $s->son[] = $g;
                }
            }
        }


        foreach ($parent as $p) {
            $p->son = array();
            foreach ($son as $s){
                if ($p->id == $s->parent) {
                    $p->son[] = $s;
                }
            }
        }

        return $parent;
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
                      sc.point,
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

        $sql .= " WHERE si.id = $id AND si.is_del = 0";

        $info = DB::select($sql);

        if(isset($info[0])){
            return $info[0];
        }else{
            return $info;
        }
    }

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
     * 修改商品
     * @param storeId   number
     * @param id   number
     * @param data   array
     */
    public function updateGoods($storeId , $id , $data){
        return DB::table($this->_store_goods_table)->where('id' , $id)->where('store_id' , $storeId)->update($data);
    }

    /**
     * 批量修改商品状态
     * @param storeId   number
     * @param ids   array
     * @param data   array
     */
    public function updateUtatus($storeId , $ids , $data){
        return DB::table($this->_store_goods_table)->whereIn('id' , $ids)->where('store_id' , $storeId)->update($data);
    }

    /**
     * 获取商品列表
     * @param storeId   number
     * @param search   array
     * @param length   number
     * @param offset   number
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
        if(isset($search['nav_id'])){
            $sql .= " AND sg.nav_id = ".$search['nav_id'];
        }
        if(isset($search['name'])){
            $sql .= " AND sg.name LIKE '%" . $search['name'] . "%'";
        }
        if(isset($search['id'])){
            $sql .= " AND sg.id = ".$search['id'];
        }
        $sql .= " AND sg.is_del = 0";

        $sql .= " ORDER BY created_at DESC";
        if(isset($search['sort_stock']) && $search['sort_stock'] == 'desc'){
            $sql .= " , stock DESC ";
        }elseif(isset($search['sort_stock']) && $search['sort_stock'] == 'asc'){
            $sql .= " , stock ASC ";
        }else{
            $sql .= "";
        }

        $sql .= " LIMIT $offset , $length";


        return DB::select($sql);

    }

    /**
     * 获取商品列表
     * @param storeId   number
     * @param search   array
     */
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
        return DB::table($this->_store_nav_table)->where('store_id' , $storeId)->orderBy('sort','ASC')->orderBy('updated_at','desc')->get();
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

    /**
     *
     * 获取商品分类
     * @param pid     number
     */
    public function getGoodsCategories($pid){

        return DB::table('goods_categories')->where('p_id' , $pid)->get();
    }

    /**
     *
     * 获取商品品牌
     * @param cid     number
     */
    public function getGoodsBrand(){

        return DB::table('goods_brand')->get();
    }

}
