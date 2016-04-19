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
}
