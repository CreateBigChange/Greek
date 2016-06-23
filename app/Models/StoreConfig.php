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

class StoreConfig extends Model{

    protected $table  = 'store_configs';


    /**
     * 更新商铺积分
     */
    public function updatePoint($storeId , $point){
        return DB::table($this->table)->where('store_id' , $storeId)->update(array('point'=>$point));
    }

    
}
