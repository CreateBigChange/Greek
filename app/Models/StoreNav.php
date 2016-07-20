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

use App\Models\StoreGoods;

class StoreNav extends Model{

    protected $table  = 'store_nav';

    /**
     * 添加栏目
     * @param data   array
     */
    public function addNav($data){
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     *
     * 更新栏目
     * @param navId     number
     * @param storeId   number
     * @param data      array
     */
    public function updateNav($navId , $storeId , $data){
        if(isset($data['sort'])) {
            $sort = DB::table($this->table)->where('id', $navId)->where('store_id', $storeId)->first();

            //up
            if($sort->sort > $data['sort']){
                $nav = DB::table($this->table)
                    ->where('store_id', $storeId)
                    ->where('sort' , '<' , $sort->sort)
                    ->where('sort' , '>=' , $data['sort'])
                    ->orderBy('sort' , 'asc')
                    ->get();

                foreach ($nav as $n){
                    if($n->sort > $data['sort'] && $n->sort < $sort->sort ){
                        DB::table($this->table)->where('id' , $n->id)->where('store_id' , $storeId)->update(['sort'=>$n->sort + 1]);
                    }else if($n->sort == $data['sort'] ){
                        DB::table($this->table)->where('id' , $n->id)->where('store_id' , $storeId)->update(['sort'=>$n->sort + 1]);
                        DB::table($this->table)->where('id' , $navId)->where('store_id' , $storeId)->update(['sort'=>$data['sort']]);
                    }
                }

            }else{
                $nav = DB::table($this->table)
                    ->where('store_id', $storeId)
                    ->where('sort' , '>' , $sort->sort)
                    ->where('sort' , '<=' , $data['sort'])
                    ->orderBy('sort' , 'asc')
                    ->get();
                foreach ($nav as $n){
                    if($n->sort < $data['sort']){
                        DB::table($this->table)->where('id' , $n->id)->where('store_id' , $storeId)->update(['sort'=>$n->sort - 1]);
                    }else if($n->sort == $data['sort']  ){
                        DB::table($this->table)->where('id' , $n->id)->where('store_id' , $storeId)->update(['sort'=>$n->sort - 1]);
                        DB::table($this->table)->where('id' , $navId)->where('store_id' , $storeId)->update(['sort'=>$data['sort']]);
                    }
                }

            }
        }
        return DB::table($this->table)->where('id' , $navId)->where('store_id' , $storeId)->update($data);
    }

    /**
     *
     * 更新栏目
     * @param navId     number
     * @param storeId   number
     * @param data      array
     */
    public function updateSortNav($navIds , $storeId , $data){
        for ($i=0 ; $i<count($navIds) ; $i++){
            return DB::table($this->table)->where('id' , $navIds[$i])->where('store_id' , $storeId)->update(array('sort'=>$data[$i]));
        }

    }

    /**
     *
     * 获取栏目
     * @param storeId   number
     */
    public function getNav($storeId){
        return DB::table($this->table)->where('store_id' , $storeId)->where('is_del' , 0)->orderBy('sort','ASC')->orderBy('updated_at','desc')->get();
    }

    /**
     *
     * 获取栏目
     * @param storeId   number
     */
    public function getNavLast($storeId){
        return DB::table($this->table)->where('store_id' , $storeId)->where('is_del' , 0)->orderBy('sort','DESC')->first();
    }

    /**
     *
     * 获取单个栏目
     * @param navId     number
     * @param storeId   number
     */
    public function getNavInfo($navId , $storeId){
        return DB::table($this->table)->where('id' , $navId)->where('store_id' , $storeId)->where('is_del' , 0)->first();
    }

    /**
     *
     * 删除栏目
     * @param navId     number
     * @param storeId   number
     */
    public function delNav($navId , $storeId){

        $storeGoodsModel = new StoreGoods();

        //统计此栏目下是否有售商品
        $goodsNum = DB::table($storeGoodsModel->getTable())->where('nav_id' , $navId)->where('is_del' , 0)->where('is_open' , 1)->where('store_id' , $storeId)->count();
        if($goodsNum != 0){
            if($storeGoodsModel->xiaJiaNavGoods($navId , $storeId)){
                return DB::table($this->table)->where('id' , $navId)->update(array('is_del' => 1));
            }else{
                return false;
            }
        }
        return DB::table($this->table)->where('id' , $navId)->update(array('is_del' => 1));
    }

}
