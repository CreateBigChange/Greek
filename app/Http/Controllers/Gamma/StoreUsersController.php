<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Gamma;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config;

use App\Models\Gamma\StoreUsers;
use App\Libs\Message;
use App\Libs\Smsrest\Sms;

class StoreUsersController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model       = new StoreUsers;
        $this->_length		= 20;
    }



    /**
     * @api {POST} /gamma/login 登录
     * @apiName login
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/login
     *
     * @apiParam {sting} account 帐号
     * @apiParam {string} password 密码
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/login
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

        $salt               = $this->_model->getShopUserSalt($account);
        if($salt == null){
            return response()->json(Message::setResponseInfo('NO_USER'));
        }
        $encrypt_password   = $this->encrypt($password , $salt->salt);
        $userInfo           = $this->_model->checkLogin($account , $encrypt_password);
        if($userInfo){
            //获取登录用户的权限

            $sessionKey = $this->getSalt(16);
            Session::put($sessionKey , $userInfo);

            $cookie = Cookie::make(Config::get('session.store_app_login_cookie') , $sessionKey , Config::get('session.store_app_lifetime'));

            //$userInfo->token = $sessionKey;
            return response()->json(Message::setResponseInfo('SUCCESS' , $userInfo))->withCookie($cookie);
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /gamma/logout 登出
     * @apiName logout
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/logout
     *
     * @apiHeaderExample {json} Header-Example:
     * {
     *      "Set-Cookie": "jisux_store_app=eyJpdiI6IkYrTEhXUVFJb3RXTHo1KzdCTEZoUHc9PSIsInZhbHVlIjoiQTZZT3pFbm05azRIMUNxWUE0emZpTEZpRVVrS29wcCtSK0U0aFZOZndOTT0iLCJtYWMiOiJjYzc0NjYwODI1NTJiOWI5ZGMyZDRkODY4YWYyZjk2ZjEwODIwOTMzMDQ3YzhjYzg3NTU0MjdkZDE1OGEyZTI0In0%3D"
     * }
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/login
     *      {
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function logout(){

        $sessionKey = cookie::get(config::get('session.store_app_login_cookie'));

        session::forget($sessionKey);

        return response()->json(Message::setResponseInfo('SUCCESS'));

    }

    /**
     * @api {POST} /gamma/reset/password 修改密码
     * @apiName resetPassword
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/reset/password
     *
     * @apiParam {sting} account 帐号
     * @apiParam {string} password 密码
     * @apiParam {string} code 验证码
     *
     * @apiParamExample {json} Request Example
     * POST /gamma/reset/password
     * {
     *      'account' : wuhui,
     *      'password'  : '123456',
     *      'code'  : '218746'
     * }
     * @apiUse CODE_200
     *
     */
    public function resetPassword(Request $request){

        $account    = $request->get('account');
        $code       = $request->get('code');
        $password   = $request->get('password');

        $checkCode  = session::get("jsx_sms_$account");

        if($code != $checkCode){
            return response()->json(Message::setResponseInfo('VERTIFY_CODE_ERROR'));
        }

        $user               = $this->_model->getUserInfoByAccount($account);
        if($user == null){
            return response()->json(Message::setResponseInfo('NO_USER'));
        }

        $data = array();
        $data['salt']               = $this->getSalt(8);
        $data['password']           = $this->encrypt($password , $data['salt']);
        $data['updated_at']         = date('Y-m-d H:i:s' , time());

        if($this->_model->reset($user->id , $data)){
            session::forget("jsx_sms_$account");

            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }



    }

    /**
     * @api {POST} /gamma/sms 短信
     * @apiName sms
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/sms
     *
     * @apiParam {sting} account 帐号
     *
     * @apiParamExample {json} Request Example
     * POST /gamma/sms
     * {
     *      account   : wuhui
     * }
     * @apiUse CODE_200
     *
     */
    public function sendSms(Request $request){

        $account  =  $request->get('account');
//        if(! preg_match("/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/" , $phone)){
//            return response()->json(Message::setResponseInfo('NO_PHONE'));
//        }

        $user               = $this->_model->getUserInfoByAccount($account);
        if($user == null){
            return response()->json(Message::setResponseInfo('NO_USER'));
        }

        $phone = $user->tel;

        $sms = new Sms;

        $code = $this->getSalt(6 , 1);

        if($sms->sendTemplateSMS($phone , array($code , '1') , Config::get('sms.templateId'))){
            Session::put("jsx_sms_$account" , $code);
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }



}