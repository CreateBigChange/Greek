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
use Validator , Input;
use Session , Cookie , Config , Log;

use App\Models\Sigma\Users;
use App\Models\Sigma\WechatPayLog;

use App\Http\Controllers\ApiController;

use App\Models\Sigma\Orders;
use App\Models\Sigma\Stores;
use App\Libs\Message;
use App\Libs\BLogger;

use Omnipay\Omnipay;

//use App\Libs\Jpush;

use App\Jobs\Jpush;

class AlipayController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Orders;
        $this->_length		= 20;

    }

    public function aliPay($orderId , Request $request){

        $userId     = $this->userId;

        if(!$request->has('out_points')){
            $outPoints = 0;
        }else{
            $outPoints  = $request->get('out_points');
        }

        //更新订单
        $payNum = $this->_model->confirmOrder( $userId , $orderId , 1 , $outPoints);

        if($payNum['code'] != 0000){
            return $payNum;
        }

        $info   = $this->_model->getOrderList($this->userId , array('id' => $orderId) , 1 , 0);

        if(count($info) == 0){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        $body       = $info[0]->sname;
        $detail     = '';
        foreach ($info[0]->goods as $g){
            $detail .= $g->name . ' ' . $g->c_name . ' ' . $g->b_name . ' ' . $g->num . '<br />';
        }


        $gateway = Omnipay::create('Alipay_MobileExpress');
        $gateway->setPartner('2088121058783821');
        $gateway->setKey('2016060201471049');
        $gateway->setSellerEmail('zxhy201510@163.com');
        $gateway->setReturnUrl('http://preview.jisxu.com/sigma/alipay/return');
        $gateway->setNotifyUrl('http://preview.jisxu.com/sigma/alipay/notify');

        //For 'Alipay_MobileExpress', 'Alipay_WapExpress'
        $gateway->setPrivateKey(public_path().'/alipay/rsa_private_key.pem');

        $options = [
            'out_trade_no'  => date('YmdHis') . mt_rand(1000,9999),
            'subject'       => $body,
            'total_fee'     => '0.01',
            'body'          => $detail,
            //'total_fee'     => (int)($payNum['data'] * 100)
        ];



        $response = $gateway->purchase($options)->send();

        var_dump($response);die;

        $wechatPayLogModel = new WechatPayLog();
        if($this->_model->updateOrderOutTradeNo($orderId, $options['out_trade_no'])){
            return response()->json(Message::setResponseInfo('SUCCESS' , $response->getOrderString()));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        //For 'Alipay_MobileExpress'
        //Use the order string with iOS or Android SDK

    }


}