<?php

namespace App\Models\Sigma;

use DB;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $_table = 'users';
    protected $_consignee_address_table = 'consignee_address';
    protected $_user_third_party_table  = 'user_third_party';

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
        $userInfo =  DB::table($this->_table)
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
                'money',
                'sex',
                'pay_password',
                'wx_openid',
                'wx_unionid',
                'qq_openid',
                'qq_unionid'
            )
            ->where('is_del' , 0)
            ->where('account' , $account)
            ->where('password', $password)
            ->first();

        if($userInfo) {
            $userInfo->is_set_pay_password = 0;
            if ($userInfo->pay_password == null || $userInfo->pay_password == '') {
                $userInfo->is_set_pay_password = 0;
            }else{
                $userInfo->is_set_pay_password = 1;
            }

            if(empty($userInfo->wx_openid) && empty($userInfo->wx_unionid)){
                $userInfo->is_bind_wx = 0;
            }else{
                $userInfo->is_bind_wx = 1;
            }

            if(empty($userInfo->qq_openid) && empty($userInfo->qq_unionid)){
                $userInfo->is_bind_qq = 0;
            }else{
                $userInfo->is_bind_qq = 1;
            }

            unset($userInfo->pay_password);
            unset($userInfo->wx_openid);
            unset($userInfo->wx_unionid);
            unset($userInfo->qq_openid);
            unset($userInfo->qq_unionid);
            return $userInfo;
        }else{
            return false;
        }

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
     * @param $id
     * @return mixed
     * 获取用户支付密码
     */
    public function getUserPayPassword($id ){
        return DB::table($this->_table)
            ->select('pay_password' , 'pay_salt')
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
     * @param $openid
     * @return mixed
     * 根据openID获取用户信息
     */
    public function getUserInfoByOpenID($openid){
        $userInfo = DB::table($this->_table)
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
                'money',
                'sex',
                'pay_password',
                'wx_openid',
                'wx_unionid',
                'qq_openid',
                'qq_unionid'
            )
            ->where('is_del' , 0)
            ->where('wx_openid' , $openid)
            ->first();

        if($userInfo) {
            $userInfo->is_set_pay_password = 0;
            if ($userInfo->pay_password == null || $userInfo->pay_password == '') {
                $userInfo->is_set_pay_password = 0;
            }else{
                $userInfo->is_set_pay_password = 1;
            }

            if(empty($userInfo->wx_openid) && empty($userInfo->wx_unionid)){
                $userInfo->is_bind_wx = 0;
            }else{
                $userInfo->is_bind_wx = 1;
            }

            if(empty($userInfo->qq_openid) && empty($userInfo->qq_unionid)){
                $userInfo->is_bind_qq = 0;
            }else{
                $userInfo->is_bind_qq = 1;
            }

            unset($userInfo->pay_password);
            unset($userInfo->wx_openid);
            unset($userInfo->wx_unionid);
            unset($userInfo->qq_openid);
            unset($userInfo->qq_unionid);
            return $userInfo;
        }else{
            return false;
        }
    }

    public function addUser($data){
        return DB::table($this->_table)->insertGetId($data);
    }

    /**
     * 添加第三方登录的用户信息
     */
//    public function addUserThirdParty($data){
//
//        $tUser = DB::table($this->_user_third_party_table)->where('open_id' , $data['open_id'])->first();
//        //如果是第一次登录
//        if(!$tUser) {
//            $userData = array(
//                'nick_name'     => $data['nick_name'],
//                'avatar'        => $data['avatar'],
//                'created_at'    => date('Y-m-d H:i:s' , time()),
//                'updated_at'    => date('Y-m-d H:i:s' , time()),
//            );
//            return DB::table($this->_user_third_party_table)->insert($data);
//        }
//    }

    /**
     * @param $account
     * @return mixed
     * 根据ID获取用户信息
     */
    public function getUserInfoById($id){
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
                'money',
                'sex'
            )
            ->where('is_del' , 0)
            ->where('id' , $id)
            ->first();
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
                'created_at',
                'points',
                'money',
                'sex'
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
                'created_at',
                'points',
                'money',
                'sex'
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
     * @return bool
     *
     * 确认余额是否充足
     */
    public function isAmpleMoney($userId , $money){
        $haveMoney = DB::table($this->_table)->select('points')->where('id' , $userId)->first();


        if($money <= $haveMoney->money){
            return $money - $haveMoney->money;
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

    /**
     * @param $userId
     * @param $point
     * @return mixed
     * 更新用户余额
     */
    public function updateMoney($userId , $money){
        return DB::table($this->_table)->where('id' , $userId)->update(['money'=>$money]);
    }

    /**
     * @param $userId
     * @param $point
     * @return mixed
     *
     */
    public function addSmsErrorLog($mobile , $errorCode){
        return DB::table('sms_error_log')->insert(
            array(
                'mobile'        => $mobile,
                'error_code'    => $errorCode,
                'created_at'    => date('Y-m-d H:i:s' , time())
            )
        );
    }

}
