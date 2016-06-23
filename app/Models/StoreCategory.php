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

class StoreCategory extends Model{

    protected $table  = 'store_categories';

    /**
     *
     * 获取栏目
     * @param storeId   number
     */
    public function getStoreCategory(){
        return DB::table($this->table)->where('is_del' , 0)->orderBy('sort','ASC')->orderBy('updated_at','desc')->get();
    }

    /**
     * 添加店铺分类
     */
    public function addStoreCategory($data){
        return DB::table($this->table)->insert($data);
    }

    /**
     * 修改店铺分类
     */
    public function updateStoreCategory($id , $data){
        return DB::table($this->table)->where('id' , $id)->update($data);
    }

    /**
     * 获取单个店铺分类
     */
    public function getStoreCategoryById($id){
        return DB::table($this->table)->where('id' , $id)->first();
    }
}
