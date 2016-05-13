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

use Session , Cookie , Config;

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

        if( !$request->has('account') ) {
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        if( !$request->has('password') ) {
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
     * @apiParam {sting} 18401586654 手机号
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

        $mobile    = $request->get('mobile');
        $code       = $request->get('code');
        $password   = $request->get('password');

        $checkCode  = session::get("jsx_sms_$mobile");

        if($code != $checkCode){
            return response()->json(Message::setResponseInfo('VERTIFY_CODE_ERROR'));
        }

        $user               = $this->_model->getUserInfoByMobile($mobile);

        if($user == null){
            return response()->json(Message::setResponseInfo('NO_USER'));
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
     * @api {POST} /sigma/sms 短信
     * @apiName sms
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/sms
     *
     * @apiParam {sting} mobile 帐号
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/sms
     * {
     *      mobile   : 18401586654
     * }
     * @apiUse CODE_200
     *
     */

    public function sendSms(Request $request){

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



}