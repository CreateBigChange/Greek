<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Sigma;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config , Redis;
use Validator , Input;

use App\Libs\Message;
use App\Libs\Smsrest\Sms;
use App\Libs\BLogger;

use App\Models\Sigma\Users;

class UsersController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model       = new Users;
        $this->_length		= 20;
    }



    /**
     * @api {POST} /sigma/login 登录
     * @apiName login
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/login
     *
     * @apiParam {sting} account 帐号
     * @apiParam {string} password 密码
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/login
     *      {
     *          "account" : 'wuhui',
     *          "password" : '123456',
     *      }
     * @apiUse CODE_200
     *
     */
    public function login(Request $request) {

        $validation = Validator::make($request->all(), [
            'account'                => 'required',
            'password'               => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $account            = $request->get('account');
        $password           = $request->get('password');

        /*
         * 获取加密字符串
         */
        $salt               = $this->_model->getUserSalt($account);
        if($salt == null){
            return response()->json(Message::setResponseInfo('NO_USER'));
        }

        $encrypt_password   = $this->encrypt($password , $salt->salt);

        $userInfo           = $this->_model->getUserInfoByAP($account , $encrypt_password);

        if($userInfo){
            //获取登录用户的权限

            $sessionKey = $this->getSalt(16);

            Session::put($sessionKey , $userInfo);

            $cookie = Cookie::make(Config::get('session.sigma_login_cookie') , $sessionKey , Config::get('session.sigma_lifetime'));

            $userInfo->token = $sessionKey;

            return response()->json(Message::setResponseInfo('SUCCESS' , $userInfo))->withCookie($cookie);

        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/register 注册
     * @apiName register
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/register
     *
     * @apiParam {sting} account 帐号(手机号)
     * @apiParam {string} password 密码
     * @apiParam {string} code 验证码
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/register
     *      {
     *          "account" : 'wuhui',
     *          "password" : '123456',
     *          "code"      :   1232
     *      }
     * @apiUse CODE_200
     *
     */
    public function register(Request $request){
        $validation = Validator::make($request->all(), [
            'account'                => 'required',
            'password'               => 'required',
//            'repassword'             => 'required',
            'code'                   => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $password       = $request->get('password');
//        $repassword     = $request->get('repassword');
        $account        = $request->get('account');
        $code           = $request->get('code');

        $checkCode  = session::get("jsx_sms_$account");

        if($code != $checkCode){
            return response()->json(Message::setResponseInfo('VERTIFY_CODE_ERROR'));
        }

//        if($password != $repassword){
//            return response()->json(Message::setResponseInfo('PASSWORD_IS_NOT_CONSISTENT'));
//        }

        $salt = $this->getSalt(8);

        $password = $this->encrypt($password , $salt);

        $data = array(
            'account'       => $account,
            'password'      => $password,
            'salt'          => $salt,
            'mobile'        => $account,
            'nick_name'     => $account,
            'created_at'    => date('Y-m-d H:i:s' , time()),
            'updated_at'    => date('Y-m-d H:i:s' , time())
        );

        $userId  = $this->_model->addUser($data);

        if($userId){

            $userInfo = $this->_model->getUserInfoByAP($account , $password);

            $sessionKey = $this->getSalt(16);

            Session::put($sessionKey , $userInfo);

            $cookie = Cookie::make(Config::get('session.sigma_login_cookie') , $sessionKey , Config::get('session.sigma_lifetime'));

            $userInfo->token = $sessionKey;
            return response()->json(Message::setResponseInfo('SUCCESS' , $userInfo))->withCookie($cookie);
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/logout 登出
     * @apiName logout
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/logout
     *
     * @apiHeaderExample {json} Header-Example:
     * {
     *      "Set-Cookie": "jisux_store_app=eyJpdiI6IkYrTEhXUVFJb3RXTHo1KzdCTEZoUHc9PSIsInZhbHVlIjoiQTZZT3pFbm05azRIMUNxWUE0emZpTEZpRVVrS29wcCtSK0U0aFZOZndOTT0iLCJtYWMiOiJjYzc0NjYwODI1NTJiOWI5ZGMyZDRkODY4YWYyZjk2ZjEwODIwOTMzMDQ3YzhjYzg3NTU0MjdkZDE1OGEyZTI0In0%3D"
     * }
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/login
     *      {
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function logout(){

        $sessionKey = cookie::get(config::get('session.sigma_login_cookie'));

        session::forget($sessionKey);

        return response()->json(Message::setResponseInfo('SUCCESS'));

    }

    /**
     * @api {POST} /sigma/reset/password 修改密码
     * @apiName resetPassword
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/reset/password
     *
     * @apiParam {sting} mobile 手机号
     * @apiParam {string} password 密码
     * @apiParam {string} code 验证码
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/reset/password
     * {
     *      'mobile' : 18401586654,
     *      'password'  : '123456',
     *      'code'  : '218746'
     * }
     * @apiUse CODE_200
     *
     */
    public function resetPassword(Request $request){

        $validation = Validator::make($request->all(), [
            'mobile'                => 'required',
            'code'                  => 'required',
            'password'              => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $mobile    = $request->get('mobile');
        $code       = $request->get('code');
        $password   = $request->get('password');

        $checkCode  = session::get("jsx_sms_$mobile");

        if($code != $checkCode){
            return response()->json(Message::setResponseInfo('VERTIFY_CODE_ERROR'));
        }

        //验证手机号是不是绑定的手机号
        $user               = $this->_model->getUserInfoByMobile($mobile);

        if($user == null){
            return response()->json(Message::setResponseInfo('MOBILE_NOT_BIND'));
        }


        $data = array();
        $data['salt']               = $this->getSalt(8);
        $data['password']           = $this->encrypt($password , $data['salt']);
        $data['updated_at']         = date('Y-m-d H:i:s' , time());

        if($this->_model->updateUser($user->id , $data)){
            session::forget("jsx_sms_$mobile");

            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/sendsms 短信
     * @apiName sms
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/sendsms
     *
     * @apiParam {sting} mobile 帐号
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/sendsms
     * {
     *      mobile   : 18401586654
     * }
     * @apiUse CODE_200
     *
     */

    public function sendSms(Request $request){

        $validation = Validator::make($request->all(), [
            'mobile'                => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $mobile  =  $request->get('mobile');
        if(! preg_match("/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/" , $mobile)){
            return response()->json(Message::setResponseInfo('NO_PHONE'));
        }

        $sms = new Sms;

        $code = $this->getSalt(6 , 1);

        BLogger::getLogger(BLogger::LOG_REQUEST)->notice(json_encode($code));
        $isSend = $sms->sendTemplateSMS($mobile , array($code , '1') , Config::get('sms.templateId'));

        if($isSend){
            Session::put("jsx_sms_$mobile" , $code);
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/user/address[?page=1] 用户收货地址
     * @apiName userAddress
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/user/address
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/user/address
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function getConsigneeAddressByUserId(){

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $addNum   = $this->_model->getCAByUidTotalNum($this->userId);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $addNum);
        $response['address']   = $this->_model->getConsigneeAddressByUserId($this->userId , $this->_length , $response['pageData']->offset);
        return response()->json(Message::setResponseInfo('SUCCESS' , $response));
    }

    /**
     * @api {POST} /sigma/user/address/add 用户添加收货地址
     * @apiName userAddressAdd
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/user/address/add
     *
     * @apiParam {string} mobile 手机号
     * @apiParam {string} consignee 联系人
     * @apiParam {string} province 省
     * @apiParam {string} city 市
     * @apiParam {string} county 区
     * @apiParam {string} [street] 街道
     * @apiParam {sting} address 详细地址
     * @apiParam {sting} longitude 经度
     * @apiParam {sting} latitude 纬度
     *
     *
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/user/address/add
     * {
     *      mobile : 18401586654,
     *      consignee   : 吴辉,
     *      province   :   湖南省,
     *      city        : 长沙市,
     *      county      : 岳麓区,
     *      street      : 银杉路,
     *      address     : 绿地中央广场,
     *      longitude   : 120.373,
     *      latitude    : 282.134
     * }
     * @apiUse CODE_200
     *
     */
    public function addConsigneeAddress(Request $request){
        $validation = Validator::make($request->all(), [
            'mobile'                => 'required',
            'consignee'             => 'required',
            'province'              => 'required',
            'city'                  => 'required',
            'county'                => 'required',
            'address'               => 'required',
            'longitude'             => 'required',
            'latitude'              => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        
        $data = array();
        
        $data['user_id']    = $this->userId;
        $data['province']   = $request->get('province');
        $data['city']       = $request->get('city');
        $data['county']     = $request->get('county');
        if($request->has('street')) {
            $data['street'] = $request->get('street');
        }
        $data['address']    = $request->get('address');
        $data['consignee']  = $request->get('consignee');
        $data['mobile']     = $request->get('mobile');
        $data['longitude']  = $request->get('longitude');
        $data['latitude']   = $request->get('latitude');
        $data['created_at'] = date('Y-m-d H:i:s' , time());
        $data['updated_at'] =date('Y-m-d H:i:s' , time());

        $addressid = $this->_model->addConsigneeAddress($data);

        if($addressid){
            return response()->json(Message::setResponseInfo('SUCCESS' , $addressid));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/user/address/update/{addressId} 修改收货地址
     * @apiName userAddressUpdate
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/user/address/update/3
     *
     * @apiParam {sting} [mobile] 手机号
     * @apiParam {sting} [consignee] 联系人
     * @apiParam {int} [province] 省
     * @apiParam {int} [city] 市
     * @apiParam {int} [county] 区
     * @apiParam {sting} [address] 详细地址
     * @apiParam {sting} [longitude] 经度
     * @apiParam {sting} [latitude] 纬度
     * @apiParam {string} [street] 街道
     *
     *
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/user/address/update/3
     * {
     *      mobile : 18401586654,
     *      consignee   : 吴辉,
     *      province   :   1,
     *      city        : 2,
     *      county      : 36,
     *      address     : 绿地中央广场,
     *      longitude   : 120.373,
     *      latitude    : 282.134
     * }
     * @apiUse CODE_200
     *
     */
    public function updateConsigneeAddress($addressId , Request $request){


        $data = array();

        if($request->has('province')) {
            $data['province'] = $request->get('province');
        }
        if($request->has('city')) {
            $data['city'] = $request->get('city');
        }
        if($request->has('county')) {
            $data['county'] = $request->get('county');
        }
        if($request->has('street')) {
            $data['street'] = $request->get('street');
        }
        if($request->has('address')) {
            $data['address'] = $request->get('address');
        }
        if($request->has('consignee')) {
            $data['consignee'] = $request->get('consignee');
        }
        if($request->has('mobile')) {
            $data['mobile'] = $request->get('mobile');
        }
        if($request->has('longitude')) {
            $data['longitude'] = $request->get('longitude');
        }
        if($request->has('latitude')) {
            $data['latitude'] = $request->get('latitude');
        }
        $data['updated_at'] =date('Y-m-d H:i:s' , time());


        if($this->_model->updateConsigneeAddress($this->userId , $addressId , $data)){
            return response()->json(Message::setResponseInfo('SUCCESS' ));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/user/address/del/{addressId} 删除收货地址
     * @apiName userAddressDel
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/user/address/del/3
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/user/address/del/3
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function delConsigneeAddress($addressId){


        $data = array();

        $data['is_del'] = 1;
        $data['updated_at'] =date('Y-m-d H:i:s' , time());


        if($this->_model->updateConsigneeAddress($this->userId , $addressId , $data)){
            return response()->json(Message::setResponseInfo('SUCCESS' ));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/user/update/password 修改密码
     * @apiName userUpdatePassword
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/user/update/password
     *
     * @apiParam {sting} old_password 旧密码
     * @apiParam {sting} new_password 新密码
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/user/update/password
     * {
     *      old_password:123456,
     *      new_password:111111
     * }
     * @apiUse CODE_200
     *
     */
    public function updatePassword(Request $request){

        $validation = Validator::make($request->all(), [
            'old_password'                => 'required',
            'new_password'                => 'required'

        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();

        $newpassword = $request->get('new_password');
        $oldpassword = $request->get('old_password');

        $password = $this->_model->getUserPassword($this->userId);
        if($password->password  == $this->encrypt($oldpassword, $password->salt)) {

            $data['salt'] = $this->getSalt(8);
            $data['password'] = $this->encrypt($newpassword, $data['salt']);
            $data['updated_at'] = date('Y-m-d H:i:s', time());

            if ($this->_model->updateUser($this->userId, $data)) {
                return response()->json(Message::setResponseInfo('SUCCESS'));
            } else {
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }else{
            return response()->json(Message::setResponseInfo('PASSWORD_ERROR'));
        }
    }

    /**
     * @api {POST} /sigma/user/set/pay/password 设置支付密码
     * @apiName userSetPayPassword
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/user/set/pay/password
     *
     * @apiParam {sting} pay_password 密码
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/user/set/pay/password
     * {
     *      pay_password:654321
     * }
     * @apiUse CODE_200
     *
     */
    public function setPayPassword(Request $request){

        $validation = Validator::make($request->all(), [
            'pay_password'                => 'required'

        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();

        $payPassword = $request->get('pay_password');

        $data['pay_salt']               = $this->getSalt(8);
        $data['pay_password']           = $this->encrypt($payPassword , $data['pay_salt']);
        $data['updated_at'] =date('Y-m-d H:i:s' , time());

        if($this->_model->updateUser($this->userId , $data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }


    /**
     * @api {POST} /sigma/user/update 修改用户信息
     * @apiName userUpdate
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/user/update
     *
     * @apiParam {sting} [avatar] 头像链接
     * @apiParam {sting} [nick_name] 昵称
     * @apiParam {sting} [true_name] 昵称
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/user/update
     * {
     *      avatar:http://xxx.png,
     *      nick_name:不当大哥好多年,
     *      true_name:大哥
     * }
     * @apiUse CODE_200
     *
     */
    public function updateUser(Request $request){


        $data = array();

        if($request->has('avatar')){
            $data['avatar'] = $request->get('avatar');
        }
        if($request->has('nick_name')){
            $data['nick_name'] = $request->get('nick_name');
        }
        if($request->has('true_name')){
            $data['true_name'] = $request->get('true_name');
        }
        $data['updated_at'] =date('Y-m-d H:i:s' , time());

        if($this->_model->updateUser($this->userId , $data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/update/mobile 更新的绑定手机号
     * @apiName updateMobile
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/update/mobile
     *
     * @apiParam {sting} 18401586654 手机号
     * @apiParam {string} code 验证码
     * @apiParam {string} checkMobileCode 是否验证旧手机
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/update/mobile
     * {
     *      'mobile'            : 18401586654,
     *      'code'              : '218746',
     *      'checkMobileCode'   : 'ssdas7343'
     * }
     * @apiUse CODE_200
     *
     */
    public function updateMobile(Request $request){

        $validation = Validator::make($request->all(), [
            'mobile'                => 'required',
            'code'                  => 'required',
            'checkMobileCode'       => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $mobile                 = $request->get('mobile');
        $code                   = $request->get('code');
        $checkMobileCode        = $request->get('checkMobileCode');

        $session_checkMobileCode = session::get('jsx_sms_code_check_token_'.$checkMobileCode);

        if($session_checkMobileCode != $checkMobileCode){
            return response()->json(Message::setResponseInfo('NO_KEY' , '' , '1001' , '没有校验旧手机'));
        }

        $checkCode  = session::get("jsx_sms_$mobile");

        if($code != $checkCode){
            return response()->json(Message::setResponseInfo('VERTIFY_CODE_ERROR'));
        }

        session::forget('jsx_sms_code_check_token_'.$checkMobileCode);

        //判断是否有其他用户绑定了此手机号
        //使用此接口理论上是没有用户绑定此手机号的
        $user               = $this->_model->getUserInfoByMobile($mobile);
        if($user){
            return response()->json(Message::setResponseInfo('MOBILE_BIND'));
        }

        $data = array();
        $data['mobile']             = $mobile;
        $data['updated_at']         = date('Y-m-d H:i:s' , time());

        if($this->_model->updateUser($this->userId , $data)){
            session::forget("jsx_sms_$mobile");

            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/bind/mobile 绑定手机号
     * @apiName bindMobile
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/bind/mobile
     *
     * @apiParam {sting} 18401586654 手机号
     * @apiParam {string} code 验证码
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/bind/mobile
     * {
     *      'mobile'            : 18401586654,
     *      'code'              : '218746'
     * }
     * @apiUse CODE_200
     *
     */
    public function bindMobile(Request $request){

        $validation = Validator::make($request->all(), [
            'mobile'                => 'required',
            'code'                  => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $mobile                 = $request->get('mobile');
        $code                   = $request->get('code');

        $checkCode  = session::get("jsx_sms_$mobile");

        if($code != $checkCode){
            return response()->json(Message::setResponseInfo('VERTIFY_CODE_ERROR'));
        }

        //判断是否有其他用户绑定了此手机号
        //使用此接口理论上是没有用户绑定此手机号的
        $user               = $this->_model->getUserInfoByMobile($mobile);
        if($user){
            return response()->json(Message::setResponseInfo('MOBILE_BIND'));
        }
        //判断此用户是否有帮定过手机号
        $user               = $this->_model->getUserInfoById($this->userId);
        if(!empty($user->mobile)){
            return response()->json(Message::setResponseInfo('HAVE_MOBILE'));
        }

        $data = array();
        $data['mobile']             = $mobile;
        $data['updated_at']         = date('Y-m-d H:i:s' , time());

        if($this->_model->updateUser($this->userId , $data)){
            session::forget("jsx_sms_$mobile");

            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/check/mobile/code 验证手机验证码
     * @apiName checkMobileCode
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/check/mobile/code
     *
     * @apiParam {sting} 18401586654 手机号
     * @apiParam {string} code 验证码
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/check/mobile/code
     * {
     *      'mobile' : 18401586654,
     *      'code'  : '218746'
     * }
     * @apiUse CODE_200
     *
     */
    public function checkMobileCode(Request $request){

        $validation = Validator::make($request->all(), [
            'mobile'                => 'required',
            'code'                  => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $mobile    = $request->get('mobile');
        $code       = $request->get('code');

        $checkCode  = session::get("jsx_sms_$mobile");

        if($code != $checkCode){
            return response()->json(Message::setResponseInfo('VERTIFY_CODE_ERROR'));
        }else{
            $token      = $this->getSalt(8 , 0);
            Session::put('jsx_sms_code_check_token_'.$token , $token);
            return response()->json(Message::setResponseInfo('SUCCESS' , $token));
        }

    }

    /**
     * @api {POST} /sigma/weixin/callback 微信回调
     * @apiName weixinCallback
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/weixin/callback
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/weixin/callback
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function weixinCallback(){
        if(!isset($_GET['code'])){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxcf33b66899744aab&secret=b72623568c915d47aab9227fa178c544&code=".$_GET['code']."&grant_type=authorization_code";

        $data = $this->curlGet($url);
        $data	= json_decode($data);

        $token		= $data->access_token;
        $openid		= $data->openid;


        $getUserInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid;

        $weixinUserInfo = $this->curlGet($getUserInfoUrl);
        $weixinUserInfo = json_decode($weixinUserInfo);

        $sqlDta = array(
            'wx_openid'		=> $weixinUserInfo->openid,
            'nick_name'		=> $weixinUserInfo->nickname,
            'avatar'		=> $weixinUserInfo->headimgurl,
            'login_type'	=> 'weixin',
            'wx_unionid'    => $weixinUserInfo->unionid,
            'created_at'    => date('Y-m-d H:i:s' , time()),
            'updated_at'    => date('Y-m-d H:i:s' , time()),
            'login_ip'      => $this->getRealIp(),
        );

        if($weixinUserInfo->sex == 1){
            $sqlDta['sex'] = "男";
        }else{
            $sqlDta['sex'] = "女";
        }

        $userInfo = $this->_model->getUserInfoByOpenID($sqlDta['openid']);

        if(!$userInfo){
            if($this->_model->addUser($sqlDta)){
                $userInfo = $this->_model->getUserInfoByOpenID($sqlDta['openid']);
                return response()->json(Message::setResponseInfo('SUCCESS' , $userInfo));
            }else{
                return response()->json(Message::setResponseInfo('FAILED'));
            }

        }else{
            return response()->json(Message::setResponseInfo('SUCCESS' , $userInfo));
        }

    }

}