<?php

namespace App\Models\Gamma;

use DB;
use Illuminate\Database\Eloquent\Model;

class StoreUsers extends Model
{
    protected $_table = 'store_users';

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

}
