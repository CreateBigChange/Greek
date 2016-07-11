<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Gamma;

use App\Models\StoreWithdrawCashLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config;

use App\Models\StoreUser;
use App\Models\Feedback;
use App\Models\Version;
use App\Libs\Message;
use App\Libs\Smsrest\Sms;
use App\Libs\BLogger;

class StoreUsersController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model       = new StoreUser;
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
        $userInfo           = $this->_model->getStoreUserInfo($account , $encrypt_password);
        if($userInfo){
            //获取登录用户的权限

            //$request->session()->getHandler()->destroy($userInfo[0]->remember_token);

            $sessionKey = $this->getSalt(16);

            $rememberToken = array(
                'remember_token'    => $request->session()->getId()
            );

            $this->_model->reset($userInfo[0]->id , $rememberToken);

            $request->session()->put($sessionKey , $userInfo[0]);

            $userInfo[0]->remember_token = $rememberToken['remember_token'];

            $cookie = Cookie::make(Config::get('session.store_app_login_cookie') , $sessionKey , Config::get('session.store_app_lifetime'));

            return response()->json(Message::setResponseInfo('SUCCESS' , $userInfo[0]))->withCookie($cookie);
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
    public function logout(Request $request){

        $sessionKey = cookie::get(config::get('session.store_app_login_cookie'));

        BLogger::getLogger(BLogger::LOG_REQUEST)->info($sessionKey);

        $request->session()->forget($sessionKey);

        //$request->session()->getHandler()->destroy($request->session()->getId());

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
        BLogger::getLogger(BLogger::LOG_REQUEST)->notice(json_encode($code));
        $isSend = $sms->sendTemplateSMS($phone , array($code , '1') , Config::get('sms.templateId'));

        if($isSend->statusCode == '000000'){
            Session::put("jsx_sms_$account" , $code);
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('SMS-FAILED' , '' , $isSend->statusCode , $isSend->statusMsg));
        }

    }

    /**
     * @api {POST} /gamma/store/cash/log 提现记录
     * @apiName cashLog
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/cash/log
     *
     * @apiParamExample {json} Request Example
     * POST /gamma/store/cash/log
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function getWithdrawCashLog(Request $request){

        $page = 1;

        if($request->has('page')){
            $page = $request->get('page');
        }

        $cashModel = new StoreWithdrawCashLog;

        $totalNum = $cashModel->withdrawCashLogTotalNum(array('store_id' => $this->storeId));
        $pageData = $this->getPageData($page , $this->_length , $totalNum);

        $log = $cashModel->getWithdrawCashLog(array('store_id' => $this->storeId) , $this->_length , $pageData->offset);

        $total = $cashModel->getWithdrawCashTotal(array('store_id' => $this->storeId) );

        $withdraw_cash_total_num = !isset($total[0]) && $total[0]->withdraw_cash_total_num == null ? 0 : $total[0]->withdraw_cash_total_num;

        $response = array(
            'log'                           => $log,
            'pageData'                      => $pageData,
            'withdraw_cash_total_num'       => $withdraw_cash_total_num
        );

        return response()->json(Message::setResponseInfo('SUCCESS' , $response));
    }

    /**
     * @api {POST} /gamma/store/cash 提现
     * @apiName cash
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/cash
     *
     * @apiParam {sting} num 金额
     *
     * @apiParamExample {json} Request Example
     * POST /gamma/store/cash
     * {
     *      num   : 102.33
     * }
     * @apiUse CODE_200
     *
     */
    public function withdrawCash(Request $request){
        if(!$request->has('num')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();
        $data['withdraw_cash_num']          = $request->get('num');
        $data['store_id']                   = $this->storeId;
        $data['user_id']                    = $this->userId;
        $data['status']                     = 1;
        $data['created_at']                 = date('Y-m-d H:i:s' , time());

        $cashModel = new StoreWithdrawCashLog;
        $result = $cashModel->withdrawCash($data);

        return $result;

    }

    /**
     * @api {POST} /gamma/store/cash/config 提现配置
     * @apiName cashConfig
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/cash/config
     *
     * @apiParamExample {json} Request Example
     * POST /gamma/store/cash/config
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function withdrawCashConfig(Request $request){

        $cashModel = new StoreWithdrawCashLog;
        $data = $cashModel->withdrawCashConfig($this->storeId , date('Y-m-d' , time()));

        return response()->json(Message::setResponseInfo('SUCCESS' , $data));

    }

    /**
     * @api {POST} /gamma/feedback 意见反馈
     * @apiName feedBack
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/feedback
     *
     * @apiParamExample {json} Request Example
     * POST /gamma/feedback
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function feedback(Request $request){

        if(!$request->has('content')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        if(!$request->has('type')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $feedbackModel = new Feedback;

        $feedbackModel->content    = $request->get('content');
        $feedbackModel->type       = $request->get('type');

        if($request->has('qq')){
            $feedbackModel->qq = $request->get('qq');
        }
        if($request->has('name')){
            $feedbackModel->name = $request->get('name');
        }
        if($request->has('tel')){
            $feedbackModel->tel = $request->get('tel');
        }
        if($request->has('email')){
            $feedbackModel->email = $request->get('email');
        }

        $feedbackModel->user_id    = $this->userId;


        if($feedbackModel->save()) {
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }



    public function appDown(){

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            $system = 'ios';
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            $system = 'android';
        }else{
            return;
        }

        $type = 2;

        $versionModel = new Version();
        $version = (Array)$versionModel->getNew($system , $type);

        if(empty($version)) {
            $version['download'] = '';
        }

        $version['title']   = "急所需商户版APP下载";
        $version['system']  = $system;

        return view("app.download" , $version);


    }

    public function checkVersion(Request $request){

        //这个type是为了兼容上一个版本1.0.1
        $type           = $request->get('type');
        $system         = $request->get('system' , '');
        $version        = $request->get('version');

        if($type == 'android'){
            $system = 'android';
        }elseif($type == 'ios'){
            $system = 'ios';
        }

        $type = 2;

        $versionModel = new Version();
        $version = $versionModel->versionIsNew($version , $system , $type);

        if($version === true){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('NO_NEW' , $version));
        }

    }



}