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
    public function getGoodsList($storeId , $length , $search){
        $sql = DB::table($this->_store_goods_table)->where('store_id' , $storeId);
        if(isset($search['is_open'])){
            $sql->where('is_open' , $search['is_open']);
        }
        if(isset($search['name'])){
            $sql->where('name' , 'like' , '%'.$search['name'].'%');
        }

        return $sql->paginate($length);
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
