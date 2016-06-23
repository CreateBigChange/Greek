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

class GoodsBrand extends Model{

    protected $table       	    = 'goods_brand';

    /**
     *
     * 删除商品品牌
     */
    public function delBrand($id){
        return DB::table($this->table)->where('id' , $id)->update(['is_del' => 1]);
    }

    /**
     *
     * 添加商品品牌
     */
    public function addBrand($data){
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     *
     * 获取商品品牌
     */
    public function getGoodsBrandByCid($cid){
        return DB::table($this->table)->where('c_id' , $cid)->where('is_del' , 0)->get();
    }


    /**
     *
     * 获取单个商品分类
     */
    public function getGoodsBrandById($id){
        return DB::table($this->table)->where('id' , $id)->where('is_del' , 0)->first();
    }

    /**
     *
     * 更新商品品牌
     */
    public function updateGoodsBrand($id , $data){
        return DB::table($this->table)->where('id' , $id)->update($data);
    }

}
