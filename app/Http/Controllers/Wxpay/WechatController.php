<?php

namespace App\Http\Controllers\Wxpay;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config;

use App\Libs\Message;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;

class WechatController extends ApiController
{
//    private $_model;
//    private $_length;

//    private $business;
//    private $notify;

    public function __construct(){
        parent::__construct();

//        $this->business = new Business(
//            'wx40bf86f9bf3f1c1e',
//            'be3cf5a36a3797484968d7976c9d5465',
//            '1288143301',
//            'e10adc3949ba59abbe56e057f20f883e'
//        );
//
//        $this->notify = new Notify(
//            'wx40bf86f9bf3f1c1e',
//            'be3cf5a36a3797484968d7976c9d5465',
//            '1288143301',
//            'e10adc3949ba59abbe56e057f20f883e'
//        );

    }

    public function view(){
        return view('wechat');
    }

    public function pay(){

        $options = [
            // 前面的appid什么的也得保留哦
            'app_id' => 'wx40bf86f9bf3f1c1e',
            'secret' => 'be3cf5a36a3797484968d7976c9d5465',
            'token'  => 'p5p3luQ13QZv5E3q5l1z3k1h3M3iZk5H',
            // ...

            // payment
            'payment' => [
                'merchant_id'        => '1288143301',
                'key'                => 'e10adc3949ba59abbe56e057f20f883e',
                'cert_path'          => '/cert/apiclient_cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => '/cert/apiclient_key.pem',      // XXX: 绝对路径！！！！
                'notify_url'         => 'http://preview.jisxu.com/wechat/notify',       // 你也可以在下单时单独设置来想覆盖它
            ],
        ];

        $app = new Application($options);

        $payment = $app->payment;

        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => '1217752501201407033233368018',
            'openid'           => session('wechat.oauth_user')->id,
            'total_fee'        => 1,
            'notify_url'       => 'http://preview.jisxu.com/wechat/notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
        ];
        $order = new Order($attributes);

        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
            $json = $payment->configForPayment($prepayId);
            $json = json_decode($json);
            return response()->json(Message::setResponseInfo('SUCCESS' , $json));
        }else{
            return response()->json(Message::setResponseInfo('FAILED' , $result));
        }

    }


    public function notify(){
        $options = [
            // 前面的appid什么的也得保留哦
            'app_id' => 'wx40bf86f9bf3f1c1e',
            'secret' => 'be3cf5a36a3797484968d7976c9d5465',
            'token'  => 'p5p3luQ13QZv5E3q5l1z3k1h3M3iZk5H',
            // ...

            // payment
            'payment' => [
                'merchant_id'        => '1288143301',
                'key'                => 'e10adc3949ba59abbe56e057f20f883e',
                'cert_path'          => '/cert/apiclient_cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => '/cert/apiclient_key.pem',      // XXX: 绝对路径！！！！
                'notify_url'         => 'http://preview.jisxu.com/wechat/notify',       // 你也可以在下单时单独设置来想覆盖它
            ],
        ];

        $app = new Application($options);
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 你的逻辑
            return true; // 或者错误消息
        });

        return $response;
    }





}
