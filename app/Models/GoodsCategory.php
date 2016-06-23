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

class GoodsCategory extends Model{

    protected $table       	= 'goods_categories';

    
    /**
     *
     * 获取商品完整分类
     * @param id    最低级的分类ID
     *
     */
    public function getGoodsAllCategory($id){
        global $category;
        $category = DB::table($this->table)->where('id' , $id)->where('is_del' , 0)->frist();
        if($category->p_id == 0){
            return $category;
        }else{
            $this->getGoodsAllCategory($category->p_id);
        }
    }

    /**
     *
     * 获取商品分类
     */
    public function getGoodsCategoryByPid($pid){
        return DB::table($this->table)->where('p_id' , $pid)->where('is_del' , 0)->get();
    }

    /**
     *
     * 获取单个商品分类
     */
    public function getGoodsCategoryById($id){
        return DB::table($this->table)->where('id' , $id)->where('is_del' , 0)->first();
    }

    /**
     *
     * 更新商品分类
     */
    public function updateGoodsCategory($id , $data){
        return DB::table($this->table)->where('id' , $id)->update($data);
    }


    /**
     *
     * 获取商品分类
     */
    public function getGoodsCategoryByLevel($level){
        return DB::table($this->table)->where('level' , $level)->where('is_del' , 0)->get();
    }


    /**
     *
     * 添加商品品牌
     */
    public function addCategory($data){
        return DB::table($this->table)->insertGetId($data);
    }



    /**
     *
     * 删除商品分类
     */
    public function delCategory($id){
        return DB::table($this->table)->where('id' , $id)->update(['is_del' => 1]);
    }

}
