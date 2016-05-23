<?php

namespace App\Models\Sigma;

use DB;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $_table = 'users';
    protected $_consignee_address_table = 'consignee_address';

    /**
     * @param $tel
     * @return mixed
     */
    public function getUserSalt($account){
        return DB::table($this->_table)
            ->select('salt')
            ->where('account' , $account)
            ->first();
    }

    /**
     * @param $account
     * @param $password
     */

    public function getUserInfoByAP( $account , $password ){
        return DB::table($this->_table)
            ->select(
                'id' ,
                'account' ,
                'nick_name' ,
                'true_name' ,
                'mobile' ,
                'avatar' ,
                'email' ,
                'created_at',
                'points',
                'money'
            )
            ->where('is_del' , 0)
            ->where('account' , $account)
            ->where('password', $password)
            ->first();

    }

    /**
     * @param $id
     * @return mixed
     * 获取用户密码
     */
    public function getUserPassword($id ){
        return DB::table($this->_table)
            ->select('password' , 'salt')
            ->where('id' , $id)->first();
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     *
     * 更新用户信息
     */

    public function updateUser($id  , $data){
        return DB::table($this->_table)->where('id' , $id)->update($data);
    }

    /**
     * @param $account
     * @return mixed
     * 根据帐号获取用户信息
     */
    public function getUserInfoByAccount($account){
        return DB::table($this->_table)
            ->select(
                'id' ,
                'account' ,
                'nick_name' ,
                'true_name' ,
                'mobile' ,
                'avatar' ,
                'email' ,
                'created_at'
            )
            ->where('is_del' , 0)
            ->where('account' , $account)
            ->first();
    }

    /**
     * @param $account
     * @return mixed
     * 根据手机号获取用户信息
     */
    public function getUserInfoByMobile($mobile){
        return DB::table($this->_table)
            ->select(
                'id' ,
                'account' ,
                'nick_name' ,
                'true_name' ,
                'mobile' ,
                'avatar' ,
                'email' ,
                'created_at'
            )
            ->where('is_del' , 0)
            ->where('mobile' , $mobile)
            ->first();
    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户收货地址
     */
    public function getConsigneeAddressByUserId($userId , $length = 20 , $offset = 0){
        return DB::table($this->_consignee_address_table)
            ->where('user_id' , $userId)
            ->where('is_del' , 0)
            ->skip($offset)->take($length)
            ->get();
    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户收货地址的总数
     */
    public function getCAByUidTotalNum($userId){
        return DB::table($this->_consignee_address_table)
            ->where('user_id' , $userId)
            ->where('is_del' , 0)
            ->count();
    }

    /**
     * @param $data
     * @return mixed
     * 添加收货地址
     */
    public function addConsigneeAddress($data){
        return DB::table($this->_consignee_address_table)->insertGetId($data);
    }

    /**
     * @param $id
     * @return mixed
     * 根据ID获取收货地址
     */
    public function getConsigneeAddressById($id){
        return DB::table($this->_consignee_address_table)->where('id' , $id)->where('is_del' , 0)->first();
    }

    /**
     * @param $userId
     * @param $id
     * @param $data
     * @return mixed
     * 修改收货地址
     */
    public function updateConsigneeAddress($userId , $id , $data){
        return DB::table($this->_consignee_address_table)->where('id' , $id)->where('user_id' , $userId)->update($data);
    }

    /**
     * @param $userId
     * @param $point
     * @return bool
     *
     * 确认积分是否充足
     */
    public function isAmplePoint($userId , $point){
        $havePoint = DB::table($this->_table)->select('points')->where('id' , $userId)->first();

        if($point <= $havePoint->points){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $userId
     * @param $point
     * @return mixed
     * 更新用户积分
     */
    public function updatePoint($userId , $point){
        return DB::table($this->_table)->where('id' , $userId)->update(['points'=>$point]);
    }

}
