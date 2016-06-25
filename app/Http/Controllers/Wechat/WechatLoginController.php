<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config;
use Validator , Input;

use App\Libs\Message;

use App\Models\User;

class WechatLoginController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model       = new User;
        $this->_length		= 20;
    }



    /**
     * @api {POST} /sigma/weixin/login?code='sadsae2342dadaxxs'&state='app' 微信登录回调
     * @apiName weixinCallback
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/weixin/login?code='sadsae2342dadaxxs'&state='app'
     *
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/weixin/login
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function weixinLogin(){
        if(!isset($_GET['code'])){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        if(isset($_GET['state']) && $_GET['state'] == 'app') {
            $appid = Config::get('weixin.app_appid');
            $secret = Config::get('weixin.app_secret');
        }elseif(isset($_GET['state']) && $_GET['state'] == 'pub'){
            $appid = Config::get('weixin.pub_appid');
            $secret = Config::get('weixin.pub_secret');
        }else{
            $appid = Config::get('weixin.web_appid');
            $secret = Config::get('weixin.web_secret');
        }

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$_GET['code']."&grant_type=authorization_code";

        $data = $this->curlGet($url);
        $data	= json_decode($data);

        if(isset($data->errcode)){
            return response()->json(Message::setResponseInfo('WX_TOKEN_FAILED'));
        }

        $token		= $data->access_token;
        $openid		= $data->openid;


        $getUserInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid;

        $weixinUserInfo = $this->curlGet($getUserInfoUrl);
        $weixinUserInfo = json_decode($weixinUserInfo);

        $sqlDta = array(
            'nick_name'		=> $weixinUserInfo->nickname,
            'avatar'		=> $weixinUserInfo->headimgurl,
            'login_type'	=> 'weixin',
            'wx_unionid'    => isset($weixinUserInfo->unionid) ? $weixinUserInfo->unionid : '',
            'created_at'    => date( 'Y-m-d H:i:s' , time()),
            'updated_at'    => date('Y-m-d H:i:s' , time()),
            'login_ip'      => $this->getRealIp(),
        );

        if($_GET['state'] == 'app'){
            $sqlDta['wx_app_openid']		= $weixinUserInfo->openid;
        }elseif($_GET['state'] == 'pub'){
            $sqlDta['wx_pub_openid']		= $weixinUserInfo->openid;
        }

        if($weixinUserInfo->sex == 1){
            $sqlDta['sex'] = "男";
        }else{
            $sqlDta['sex'] = "女";
        }

        //判断该微信是否之前有过登录,有登录直接返回用户信息
        $userInfo = $this->_model->getUserInfoByUnionid($sqlDta['wx_unionid']);

        if(!$userInfo){
            if($this->_model->addUser($sqlDta)){

                $userInfo = $this->_model->getUserInfoByUnionid($sqlDta['wx_unionid']);
                if($userInfo) {
                    $sessionKey = $this->getSalt(16);

                    Session::put($sessionKey, $userInfo);
                    $cookie = Cookie::make(Config::get('session.sigma_login_cookie'), $sessionKey, Config::get('session.sigma_lifetime'));

                    return response()->json(Message::setResponseInfo('SUCCESS', $userInfo))->withCookie($cookie);
                }else{
                    return response()->json(Message::setResponseInfo('FAILED'));
                }

            }else{
                return response()->json(Message::setResponseInfo('FAILED'));
            }

        }else{

            $updateData = array();
            if($_GET['state'] == 'app' && !$userInfo->wx_app_openid){
                $updateData['wx_app_openid']		= $weixinUserInfo->openid;
                $this->_model->updateUser($userInfo->id , $updateData);
            }elseif($_GET['state'] == 'pub' && !$userInfo->wx_pub_openid){
                $updateData['wx_pub_openid']		= $weixinUserInfo->openid;
                $this->_model->updateUser($userInfo->id , $updateData);
            }



            $sessionKey = $this->getSalt(16);

            Session::put($sessionKey, $userInfo);

            $cookie = Cookie::make(Config::get('session.sigma_login_cookie'), $sessionKey, Config::get('session.sigma_lifetime'));

            $userInfo->token = $sessionKey;

            return response()->json(Message::setResponseInfo('SUCCESS', $userInfo))->withCookie($cookie);
        }

    }


    /**
     * @api {POST} /sigma/weixin/openid?code='sadsae2342dadaxxs'&state='app' 微信登录回调
     * @apiName weixinOpenid
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/weixin/openid?code='sadsae2342dadaxxs'&state='app'
     *
     *
     * @apiParamExample {json} Request Example
     * POST /sigma/weixin/openid
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function weixinOpenId(){

        if(!isset($_GET['code'])){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        if(isset($_GET['state']) && $_GET['state'] == 'app') {
            $appid = Config::get('weixin.app_appid');
            $secret = Config::get('weixin.app_secret');
        }elseif(isset($_GET['state']) && $_GET['state'] == 'pub'){
            $appid = Config::get('weixin.pub_appid');
            $secret = Config::get('weixin.pub_secret');
        }else{
            $appid = Config::get('weixin.web_appid');
            $secret = Config::get('weixin.web_secret');
        }

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$_GET['code']."&grant_type=authorization_code";

        $data = $this->curlGet($url);
        $data	= json_decode($data);

        if(isset($data->errcode)){
            return response()->json(Message::setResponseInfo('WX_TOKEN_FAILED'));
        }

        $openid		= $data->openid;

        return response()->json(Message::setResponseInfo('SUCCESS', $openid));
    }

}
