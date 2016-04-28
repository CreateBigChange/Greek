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
    public function checkLogin( $account , $password ){

        $isLogin = DB::table($this->_table)
            ->select('id' , 'store_id' , 'account' , 'real_name' , 'tel' )
            ->where('is_del' , 0)
            ->where('account' , $account)
            ->where('password' , $password)
            ->first();
        

        return $isLogin;
    }

    /**
     * 更新密码
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
