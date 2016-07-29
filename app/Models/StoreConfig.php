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
     * 获取店铺信息配置信息
     */
    public function getStoreConfigs($storeId){
        return DB::table($this->table)->where("store_id",$storeId)->first();
    }


    /**
     * 更新商铺积分
     */
    public function updateBalancePoint($storeId , $point){
        return DB::table($this->table)->where('store_id' , $storeId)->update(array('balance_point'=>$point));
    }

    /**
     * 更新商铺积分
     */
    public function updatePoint($storeId , $point){
        return DB::table($this->table)->where('store_id' , $storeId)->update(array('point'=>$point));
    }

    /**
     * 更新商铺可提现金额
     */
    public function updateMoney($storeId , $money){
        return DB::table($this->table)->where('store_id' , $storeId)->update(array('money'=>$money));
    }

    /**
     * 更新商铺余额
     */
    public function updateBalance($storeId , $balance){
        return DB::table($this->table)->where('store_id' , $storeId)->update(array('balance'=>$balance));
    }

    /**
     *
     * 配置店铺
     * @param storeId   number
     * @param config    array
     */
    public function config($storeId , $config){
        return DB::table($this->table)->where('store_id' , $storeId)->update($config);
    }

    /**
     * 判断余额是否充足
     */
    public function isAmpleStoreMoney($storeId , $money){
        $storeMoney = DB::table($this->table)->select('money')->where('store_id' , $storeId)->first();

        $temMoney = $storeMoney->money - $money;
        if($temMoney >= 0) {
            return $temMoney;
        }else{
            return false;
        }
    }
    
}
