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
use App\Models\StoreInfo;

class StoreUser extends Model{

    protected $table  = 'store_users';

    /**
     *
     * 创建店铺用户
     */
    public function addStoreUser($data){
        return DB::table($this->table)->insert($data);
    }

    /**
     * 获取店铺用户
     */
    public function getStoreUserList($storeId = 0){
        $storeInfoModel = new StoreInfo();
        $sql = DB::table($this->table)
            ->select(
                'store_users.id',
                'store_users.account',
                'store_users.real_name',
                'store_users.tel',
                'store_users.created_at',
                'si.name as sname',
                'si.id as sid'
            )
            ->leftJoin( $storeInfoModel->getTable()." as si", 'si.id' , '=' , 'store_users.store_id');
        if($storeId != 0){
            $sql->where('store_id' , $storeId);
        }

        return $sql->get();
    }

    /**
     * @param $tel
     * @return mixed
     */
    public function getShopUserSalt($account){
        return DB::table($this->table)
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
                      sc.point,
                      sc.money,
                      si.province,
                      si.city,
                      si.county
                FROM $this->table AS su";
        $sql .= " LEFT JOIN store_infos as si ON su.store_id = si.id";
        $sql .= " LEFT JOIN store_configs as sc ON su.store_id = sc.store_id";
        $sql .= " LEFT JOIN store_categories as sca ON si.c_id = sca.id";

        $sql .= " WHERE account='" . $account . "' AND password='". $password . "' AND su.is_del=0";
        /*
        $isLogin = DB::table($this->table)
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
        return DB::table($this->table)->where('id' , $id)->update($data);
    }

    /**
     * @param $account
     * @param $password
     * @return mixed
     * 根据帐号获取用户信息
     */
    public function getUserInfoByAccount( $account ){

        $isLogin = DB::table($this->table)
            ->select('id' , 'store_id' , 'account' , 'real_name' , 'tel' )
            ->where('account' , $account)
            ->first();


        return $isLogin;
    }
}
