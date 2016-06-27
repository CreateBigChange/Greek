<?php
/**
 * bannerModel
 * @author  wuhui
 * @time    2016/06-08
 * @email   wuhui904107775@qq.com
 */
namespace App\Models;

use DB , Config;
use Illuminate\Database\Eloquent\Model;
use App\Models\StoreInfo;
use App\Models\StoreBankCard;

class StoreWithdrawCashLog extends Model
{

    protected $table = 'store_withdraw_cash_log';

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 增加提现记录
     */
    public function withdrawCash($data){

        if(!isset($data['store_id'])){
            return false;
        }

        //获取银行信息
        $storeBankCardModel         = new StoreBankCard;
        $bankInfo = $storeBankCardModel->getBankCard($data['store_id'], 0);

        if(!$bankInfo){
            return Message::setResponseInfo('NO_BANK');
        }
        $data['bank_card_num']                          = $bankInfo->bank_card_num;
        $data['bank_card_holder']                       = $bankInfo->bank_card_holder;
        $data['bank_card_type']                         = $bankInfo->bank_card_type;
        $data['bank_name']                              = $bankInfo->bank_name;
        $data['bank_reserved_telephone']                = $bankInfo->bank_reserved_telephone;


        if($this->getWithdrawCashTimes($data['store_id'] , date('Y-m-d' , time()) ) + 1 > Config::get('withdrawcash.times')){
            return Message::setResponseInfo('NO_TIMES');
        }

        if($data['withdraw_cash_num'] > Config::get('withdrawcash.total')){
            return Message::setResponseInfo('EXCEED_MONEY_LIMIT');
        }

        $storeConfigModel             = new StoreConfig;
        $isAmple = $storeConfigModel->isAmpleStoreMoney($data['store_id'], $data['withdraw_cash_num']);
        if($isAmple === false){
            return Message::setResponseInfo('MONEY_NOT_AMPLE');
        }

        DB::beginTransaction();

        try{

            $storeConfigModel->config($data['store_id'] , array('money' => $isAmple));

            DB::table($this->table)->insert($data);
            DB::commit();
            return Message::setResponseInfo('SUCCESS');

        }catch (Exception $e){
            DB::rollBack();

            return Message::setResponseInfo('FAILED');
        }

        return Message::setResponseInfo('FAILED');
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 获取提现记录
     */
    public function getWithdrawCashLog($storeId , $length , $offset , $date=''){

        $sql = "SELECT 
                    su.real_name,
                    sw.withdraw_cash_num,
                    sw.created_at,
                    sw.status,
                    sw.reason,
                    sw.bank_card_num
               
               FROM $this->table as sw" ;

        $sql .= " LEFT JOIN store_users as su on su.id = sw.user_id";

        $sql .= " WHERE sw.store_id = $storeId";
        if($date){
            $sql.= " AND sw.created_at LIKE '" . $date ."%'";
        };

        $sql .= " LIMIT $offset , $length ";

        $result = DB::select($sql);
        foreach ($result as $r){

            $r->bank_card_num = preg_replace('/(^.*)\d{4}(\d{4})$/','\\2',$r->bank_card_num);
            //$r->bank_card_num = substr_replace($r->bank_card_num, '', -1 , 4);
        }

        return $result;
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 获取提现总计
     */
    public function getWithdrawCashTotal($storeId , $date=''){

        $sql = "SELECT 
                    sum(sw.withdraw_cash_num) as withdraw_cash_total_num
               FROM $this->table as sw" ;

        $sql .= " WHERE sw.store_id = $storeId";
        if($date){
            $sql.= " AND sw.created_at LIKE '" . $date ."%'";
        };

        $result = DB::select($sql);

        return $result;
    }

    public function withdrawCashLogTotalNum($storeId){
        return DB::table($this->table)->where('store_id' , $storeId)->count();
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 获取今日提现的次数
     */
    public function getWithdrawCashTimes($storeId , $date=''){

        $sql = "SELECT 
                    count(*) as num
               
               FROM $this->table as sw" ;

        $sql .= " WHERE sw.store_id = $storeId";
        if($date){
            $sql.= " AND sw.created_at LIKE '" . $date ."%'";
        };

        $result = DB::select($sql);

        return isset($result[0]) ? $result[0]->num : 0;
    }


    public function withdrawCashConfig($storeId , $date=''){
        $data = array();
        $data['used_times']             = $this->getWithdrawCashTimes($storeId , $date);
        $data['times']                  = Config::get('withdrawcash.times');
        $data['total']                  = Config::get('withdrawcash.total');
        $data['using_times']            = $data['times'] - $data['used_times'];
        $storeConfig                    = DB::table('store_configs')->where('store_id' , $storeId)->first();

        $data['can_withdraw_cash']      = $storeConfig->money;

        return $data;
    }

}