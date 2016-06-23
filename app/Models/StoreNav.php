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

class StoreNav extends Model{

    protected $table  = 'store_nav';

    /**
     *
     * 获取栏目
     * @param storeId   number
     */
    public function getNav($storeId){
        return DB::table($this->table)->where('store_id' , $storeId)->where('is_del' , 0)->orderBy('sort','ASC')->orderBy('updated_at','desc')->get();
    }


}
