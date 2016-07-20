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

class User extends Model{

    protected $table = 'users';

    protected $field = [
        'id' ,
        'account' ,
        'nick_name' ,
        'true_name',
        'mobile',
        'avatar',
        'email',
        'created_at',
        'points',
        'money',
        'sex',
        'pay_password',
        'wx_app_openid',
        'wx_pub_openid',
        'wx_unionid',
    ];

    /**
     * @param $tel
     * @return mixed
     */
    public function getUserSalt($account){
        return DB::table($this->table)
            ->select('salt')
            ->where('account' , $account)
            ->first();
    }

    /**
     * @param $account
     * @param $password
     */

    public function getUserInfoByAP( $account , $password ){
        $userInfo =  DB::table($this->table)
            ->select($this->field)
            ->where('is_del' , 0)
            ->where('account' , $account)
            ->where('password', $password)
            ->first();

        if($userInfo) {
            $userInfo = $this->__checkUserInfo($userInfo);
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
        return DB::table($this->table)
            ->select('password' , 'salt')
            ->where('id' , $id)->first();
    }

    /**
     * @param $id
     * @return mixed
     * 获取用户支付密码
     */
    public function getUserPayPassword($id ){
        return DB::table($this->table)
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
        return DB::table($this->table)->where('id' , $id)->update($data);
    }

    /**
     * @param $openid
     * @return mixed
     * 根据openID获取用户信息
     */
    public function getUserInfoByOpenID($openid){
        $userInfo = DB::table($this->table)
            ->select($this->field)
            ->where('is_del' , 0)
            ->where('wx_openid' , $openid)
            ->first();

        if($userInfo) {
            $userInfo = $this->__checkUserInfo($userInfo);
            return $userInfo;
        }else{
            return false;
        }
    }

    /**
     * @param $openid
     * @return mixed
     * 根据openID获取用户信息
     */
    public function getUserInfoByUnionid($unionid){
        $userInfo = DB::table($this->table)
            ->select($this->field)
            ->where('is_del' , 0)
            ->where('wx_unionid' , $unionid)
            ->first();

        if($userInfo) {
            $userInfo = $this->__checkUserInfo($userInfo);
            return $userInfo;
        }else{
            return false;
        }
    }

    public function addUser($data){
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     * @param $account
     * @return mixed
     * 根据ID获取用户信息
     */
    public function getUserInfoById($id){
        $userInfo =  DB::table($this->table)
            ->select($this->field)
            ->where('is_del' , 0)
            ->where('id' , $id)
            ->first();

        if($userInfo) {
            $userInfo = $this->__checkUserInfo($userInfo);
            return $userInfo;
        }else{
            return false;
        }
    }

    /**
     * @param $account
     * @return mixed
     * 根据帐号获取用户信息
     */
    public function getUserInfoByAccount($account){
        $userInfo = DB::table($this->table)
            ->select($this->field)
            ->where('is_del' , 0)
            ->where('account' , $account)
            ->first();

        if($userInfo) {
            $userInfo = $this->__checkUserInfo($userInfo);
            return $userInfo;
        }else{
            return false;
        }
    }

    /**
     * @param $account
     * @return mixed
     * 根据手机号获取用户信息
     */
    public function getUserInfoByMobile($mobile){
        $userInfo =  DB::table($this->table)
            ->select($this->field)
            ->where('is_del' , 0)
            ->where('mobile' , $mobile)
            ->first();

        if($userInfo) {
            $userInfo = $this->__checkUserInfo($userInfo);
            return $userInfo;
        }else{
            return false;
        }
    }

    private function __checkUserInfo($userInfo){
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
//        unset($userInfo->wx_openid);
//        unset($userInfo->wx_unionid);
//        unset($userInfo->qq_openid);
//        unset($userInfo->qq_unionid);

        return $userInfo;
    }

    /**
     * @param $userId
     * @param $point
     * @return bool
     *
     * 确认积分是否充足
     */
    public function isAmplePoint($userId , $point){
        $havePoint = DB::table($this->table)->select('points')->where('id' , $userId)->first();

        if($point <= $havePoint->points){
            return $havePoint->points - $point;
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
        $haveMoney = DB::table($this->table)->select('money')->where('id' , $userId)->first();


        if($money <= $haveMoney->money){
            return $haveMoney->money - $money;
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
        return DB::table($this->table)->where('id' , $userId)->update(['points'=>$point]);
    }

    /**
     * @param $userId
     * @param $point
     * @return mixed
     * 更新用户余额
     */
    public function updateMoney($userId , $money){
        return DB::table($this->table)->where('id' , $userId)->update(['money'=>$money]);
    }

    /**
     * @return mixed
     *
     * 获取用户列表
     */
    public function getUserList(){
        return DB::table($this->table)
            ->select(
                'id',
                'account',
                'nick_name',
                'true_name',
                'sex',
                'mobile',
                'avatar',
                'points',
                'money',
                'login_type',
                'created_at'
            )
            ->where('is_del' , 0)->get();
    }

    /**
     * @param $account
     * @return mixed
     * 检测注册用户帐号和手机号是否存在
     */
    public function checkUserByAM($account , $mobile){
        return DB::table($this->table)
            ->select($this->field)
            ->where('account' , $account)
            ->orWhere('mobile' , $mobile)
            ->first();

    }
    /**
     *  @return mixed
     *  获取用户总数
     */
    public function getUserNum(){
        return DB::table($this->table)
            ->count();
    }
}
