<?php

namespace App\Models\Gamma;

use DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Gamma\Stores;
use Config;
use App\Libs\Message;
use Mockery\Exception;
use App\Libs\BLogger;

class StoreUsers extends Model
{
    protected $_table = 'store_users';
    protected $_store_withdraw_cash_log_table = 'store_withdraw_cash_log';

    /**
     * 获取用户权限列表
     */
    /**
    public function getShopUserPermissions($userId){
        $permissions = DB::table('store_user_roles as ur')
            ->join('store_permission_roles as pr' , 'pr.role_id' , '=' , 'ur.role_id')
            ->join('store_permissions as p' , 'p.id' , '=' , 'pr.permission_id')
            ->where('shop_user_id' , $userId)
            ->get();

        $data = array();
        foreach($permissions as $p){
            if(!empty($p->name)){
                $data[] = $p->name;
            }
        }

        return $data;

    }
    */
    /**
     * @param $tel
     * @return mixed
     */
    public function getShopUserSalt($account){
        return DB::table($this->_table)
            ->select('salt')
            ->where('account' , $account)
            ->first();
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     */
    public function getStoreUserInfo( $account , $password ){
        $sql = "SELECT
                      su.id,
                      su.store_id,
                      su.account,
                      su.real_name,
                      su.remember_token,
                      su.tel,
                      si.name as store_name,
                      si.address,
                      si.contacts,
                      si.contact_phone,
                      si.is_open,
                      si.is_checked,
                      sca.name as category_name,
                      sc.point,
                      sc.store_logo,
                      sc.start_price,
                      sc.deliver,
                      sc.business_cycle,
                      sc.business_time,
                      sc.is_close,
                      sc.bell,
                      ap.id as province_id,
                      ap.name as province,
                      aci.id as city_id,
                      aci.name as city,
                      aco.id as county_id,
                      aco.name as county
                FROM $this->_table AS su";
        $sql .= " LEFT JOIN store_infos as si ON su.store_id = si.id";
        $sql .= " LEFT JOIN store_configs as sc ON su.store_id = sc.store_id";
        $sql .= " LEFT JOIN store_categories as sca ON si.c_id = sca.id";
        $sql .= " LEFT JOIN areas as ap ON ap.id = si.province";
        $sql .= " LEFT JOIN areas as aci ON aci.id = si.city";
        $sql .= " LEFT JOIN areas as aco ON aco.id = si.county";

        $sql .= " WHERE account='" . $account . "' AND password='". $password . "' AND su.is_del=0";
        /*
        $isLogin = DB::table($this->_table)
            ->select('id' , 'store_id' , 'account' , 'real_name' , 'tel' )
            ->where('is_del' , 0)
            ->where('account' , $account)
            ->where('password' , $password)
            ->first();
        */

        $userInfo = DB::select($sql);

        return $userInfo;
    }

    /**
     * 更新用户信息
     */
    public function reset($id  , $data){
        return DB::table($this->_table)->where('id' , $id)->update($data);
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 根据帐号获取用户信息
     */
    public function getUserInfoByAccount( $account ){

        $isLogin = DB::table($this->_table)
            ->select('id' , 'store_id' , 'account' , 'real_name' , 'tel' )
            ->where('account' , $account)
            ->first();


        return $isLogin;
    }

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
        $storeModel = new Stores;
        $bankInfo = $storeModel->getBankCard($data['store_id'], 0);

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

        $isAmple = $storeModel->isAmpleStoreMoney($data['store_id'], $data['withdraw_cash_num']);
        if($isAmple === false){
            return Message::setResponseInfo('MONEY_NOT_AMPLE');
        }

        DB::beginTransaction();

        try{

            $storeModel->config($data['store_id'] , array('money' => $isAmple));

            DB::table($this->_store_withdraw_cash_log_table)->insert($data);
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
               
               FROM store_withdraw_cash_log as sw" ;

        $sql .= " LEFT JOIN store_users as su on su.id = sw.user_id";

        $sql .= " WHERE sw.store_id = $storeId";
        if($date){
            $sql.= " AND sw.created_at LIKE '" . $date ."%'";
        };

        $sql .= "limit $offset , $length ";

        $result = DB::select($sql);
        foreach ($result as $r){

            $r->bank_card_num = preg_replace('/(^.*)\d{4}(\d{4})$/','\\2',$r->bank_card_num);
            //$r->bank_card_num = substr_replace($r->bank_card_num, '', -1 , 4);
        }


        return $result;
    }

    public function withdrawCashLogTotalNum($storeId){
        return DB::table('store_withdraw_cash_log')->where('store_id' , $storeId)->count();
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
               
               FROM store_withdraw_cash_log as sw" ;

        $sql .= " WHERE sw.store_id = $storeId";
        if($date){
            $sql.= " AND sw.created_at LIKE '" . $date ."%'";
        };

        $result = DB::select($sql);

        return isset($result[0]) ? $result[0]->num : 0;
    }


    public function withdrawCashConfig($storeId , $date=''){
        $data = array();
        $data['used_times']     = $this->getWithdrawCashTimes($storeId , $date);
        $data['times']          = Config::get('withdrawcash.times');
        $data['total']          = Config::get('withdrawcash.total');
        $data['using_times']    = $data['times'] - $data['used_times'];

        return $data;
    }


}
