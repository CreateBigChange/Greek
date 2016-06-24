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

class StoreBankCard extends Model
{
    protected $table = 'store_bank_card';
    /**
     * 返回店铺绑定的银行卡
     */
    public function getBankCard($storeId, $bankId)
    {

        return DB::table($this->table)->where('store_id', $storeId)->first();

    }

}