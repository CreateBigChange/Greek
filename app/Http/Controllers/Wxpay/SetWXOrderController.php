<?php

namespace App\Http\Controllers\Wxpay;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config;

use App\Libs\Message;

use Overtrue\Wechat\Payment;
use Overtrue\Wechat\Payment\Order;
use Overtrue\Wechat\Payment\Business;
use Overtrue\Wechat\Payment\UnifiedOrder;
use Overtrue\Wechat\Payment\Notify;

class SetWXOrderController extends ApiController
{
//    private $_model;
//    private $_length;

    private $business;
    private $notify;

    public function __construct(){
        parent::__construct();

        $this->business = new Business(
            'wx40bf86f9bf3f1c1e',
            'be3cf5a36a3797484968d7976c9d5465',
            '1288143301',
            'e10adc3949ba59abbe56e057f20f883e'
        );

        $this->notify = new Notify(
            'wx40bf86f9bf3f1c1e',
            'be3cf5a36a3797484968d7976c9d5465',
            '1288143301',
            'e10adc3949ba59abbe56e057f20f883e'
        );
    }





    public function setOrder(Request $request){

        $user = session::get('wechat.oauth_user');
        var_dump($user);die;
        /**
         * 定义订单
         */
        $order = new Order();
        $order->body = 'test body';
        $order->out_trade_no = md5(uniqid().microtime());
        $order->total_fee = '1'; // 单位为 “分”, 字符串类型
        $order->openid = OPEN_ID;
        $order->notify_url = 'http://preview.jisxu.com/wechat/notify';

        /**
         * 第 3 步：统一下单
         */
        $unifiedOrder = new UnifiedOrder($this->business, $order);

        /**
         * 第 4 步：生成支付配置文件
         */
        $payment = new Payment($unifiedOrder);

        var_dump($payment->getConfig());die;

    }

    public function notify(){


        $transaction = $this->notify->verify();

        if (!$transaction) {
            $this->notify->reply('FAIL', 'verify transaction error');
        }


        echo $this->notify->reply();
    }


}
