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


    /**
     * 更新店铺绑定的银行卡
     */
    public function updateBankCard($storeId, $data)
    {

        return DB::table($this->table)->where('store_id', $storeId)->update($data);

    }

    /**
     * @param  $data   更新或者新创建
     * @param  $search 查找的依据
     */
    public function  updateBank($bank_card_id,$data)
    {

        $result =  $user = DB::table($this->table)->where('bank_card_id', $bank_card_id)->first();
        
        if($result!=null){
            DB::table($this->table)
                ->where('bank_card_id', $bank_card_id)
                ->update($data);
            dd("up");
        }else{
            DB::table($this->table)->insert($data);

            dd("add");
        }
    }

}