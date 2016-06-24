<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config , Log , Validator;

use App\Models\Order;
use App\Models\StoreInfo;
use App\Models\WechatPayLog;

use App\Libs\Message;
use App\Libs\BLogger;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order as WechatOrder;

use App\Jobs\Jpush;

class WechatController extends ApiController
{

    private $pubOptions;
    private $openOptions;

    public function __construct(){
        parent::__construct();

        $this->_model = new Order;

        $this->pubOptions      = [
            'app_id' => Config::get('wechat.app_id'),
            'secret' => Config::get('wechat.secret'),
            'token'  => Config::get('wechat.token'),

            'payment' => [
                'merchant_id'        => Config::get('wechat.merchant_id'),
                'key'                => Config::get('wechat.key'),
                'cert_path'          => Config::get('wechat.cert_path'),
                'key_path'           => Config::get('wechat.key_path'),
                'notify_url'         => Config::get('wechat.notify_url'),
            ],
        ];

        $this->openOptions      = [
            'app_id' => Config::get('wechat.open_app_id'),
            'secret' => Config::get('wechat.open_secret'),

            'payment' => [
                'merchant_id'        => Config::get('wechat.open_merchant_id'),
                'key'                => Config::get('wechat.open_key'),
                'cert_path'          => Config::get('wechat.open_cert_path'),
                'key_path'           => Config::get('wechat.open_key_path'),
                'notify_url'         => Config::get('wechat.open_notify_url'),
            ],
        ];
    }

    /**
     * @api {POST} /sigma/order/confirm/wechat/{orderId} 确认订单-微信(生成微信订单)
     * @apiName ordersConfirmWechat
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 确认订单-微信
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/confirm/wechat/1
     *
     * @apiParam {int} out_points 使用积分
     * @apiParam {string} trade_type 来源[JSAPI，NATIVE，APP]
     * @apiParam {string} [opneid] 微信openid
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/confirm/wechat/1
     *      {
     *          out_points  : 328,
     *          trade_type  : JSAPI,
     *      }
     * @apiUse CODE_200
     *
     */
    public function wechatPay($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'trade_type'            => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $userId     = $this->userId;

        if(!$request->has('out_points')){
            $outPoints = 0;
        }else{
            $outPoints  = $request->get('out_points');
        }
        $tradeType    = $request->get('trade_type');

        //更新订单
        $payNum = $this->_model->confirmOrder( $userId , $orderId , 3 , $outPoints);

        if($payNum['code'] != 0000){
            return $payNum;
        }

        $info   = $this->_model->getOrderList($this->userId , array('id' => $orderId) , 1 , 0);

        if(count($info) == 0){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        //微信下单
        $body       = $info[0]->sname;
        $detail     = '';
        foreach ($info[0]->goods as $g){
            $detail .= $g->name . ' ' ;
        }

        $attributes = array();
        $attributes['trade_type']       = $tradeType;
        $attributes['body']             = $detail;
        $attributes['detail']           = $body;
        $attributes['out_trade_no']     = time() . $info[0]->id . $this->getSalt(8 , 1);
        $attributes['total_fee']        = 1;
        $attributes['fee_type']         = 1;
        //$attributes['time_start']       = date('YmdHis' , time());
        //$attributes['time_expire']      = date('YmdHis' , time() + 30 * 60);
        $attributes['attach']           = $orderId;
        //$attributes['total_fee']        = (int)($payNum['data'] * 100);

        if($tradeType == 'JSAPI') {

            $app = new Application($this->pubOptions);

            $openid = $request->get('openid');

            $attributes['openid']           = $openid;
            $attributes['notify_url']       = Config::get('wechat.notify_url');

        }else{
            $app = new Application($this->openOptions);
            $attributes['notify_url']       = Config::get('wechat.open_notify_url');

        }

        $payment = $app->payment;

        $order = new WechatOrder($attributes);

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($order));

        $result = $payment->prepare($order);

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($result));

        if ($result->return_code == 'SUCCESS' && $result->return_msg == 'OK'){

            $prepayId = $result->prepay_id;

            $payLog = array();
            $payLog['trade_type']           = $attributes['trade_type'];
            $payLog['body']                 = $attributes['body'];
            $payLog['detail']               = $attributes['detail'];
            $payLog['out_trade_no']         = $attributes['out_trade_no'];
            $payLog['trade_type']           = $attributes['trade_type'];
            $payLog['body']                 = $attributes['body'];
            $payLog['detail']               = $attributes['detail'];
            $payLog['out_trade_no']         = $attributes['out_trade_no'];
            $payLog['total_fee']            = $attributes['total_fee'];
            $payLog['fee_type']             = $attributes['fee_type'];
            $payLog['spbill_create_ip']     = $order->spbill_create_ip;

            if($tradeType == 'JSAPI') {
                $json = $payment->configForPayment($prepayId);
                $json = json_decode($json);

                $payLog['openid']           = $attributes['openid'];
                $payLog['timeStamp']        = $json->timeStamp;
                $payLog['nonceStr']         = $json->nonceStr;
                $payLog['package']          = $json->package;
                $payLog['signType']         = $json->signType;
                $payLog['paySign']          = $json->paySign;

            }else if($tradeType == 'APP'){
                $json = $payment->configForAppPayment($prepayId);

                if(isset($json['package'])){
                    $json['packageValue'] = $json['package'];
                }

                $payLog['timeStamp']        = $json['timestamp'];
                $payLog['nonceStr']         = $json['noncestr'];
                $payLog['package']          = $json['package'];
                $payLog['signType']         = '';
                $payLog['paySign']          = $json['sign'];
            }

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($json));

            $wechatPayLogModel = new WechatPayLog();
            if($wechatPayLogModel->addLog($payLog) && $this->_model->updateOrderOutTradeNo($orderId, $attributes['out_trade_no'])){
                return response()->json(Message::setResponseInfo('SUCCESS' , $json));
            }
        }else{
            return response()->json(Message::setResponseInfo('FAILED' , $result));
        }

    }


    /**
     * @api {POST} /sigma/wechat/notify/pub 微信pub支付回调
     * @apiName ordersWechatNotifyPub
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 确认订单-微信
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/wechat/notify/pub
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/wechat/notify/pub
     *      {
     *      }
     * @apiUse CODE_200
     *
     */

    public function notifyPub(){

        $app = new Application($this->pubOptions);

        return $this->setPayData($app);

    }

    /**
     * @api {POST} /sigma/wechat/notify/open 微信open支付回调
     * @apiName ordersWechatNotifyOpen
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 确认订单-微信
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/wechat/notify/open
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/wechat/notify/open
     *      {
     *      }
     * @apiUse CODE_200
     *
     */

    public function notifyOpen(){

        $app = new Application($this->openOptions);

        return $this->setPayData($app);

    }

    private function setPayData($app){
        $response = $app->payment->handleNotify(function($notify, $successful){

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($notify);

            $outTradeNo = $notify->out_trade_no;

            $order = $this->_model->getOrderByOutTradeNo($outTradeNo);

            if(!$order){
                return 'Order not exist.';
            }

            $data = array(
                'appid'             => $notify->appid,
                'bank_type'         => $notify->bank_type,
                'cash_fee'          => $notify->cash_fee,
                'mch_id'            => $notify->mch_id,
                'result_code'       => $notify->result_code,
                'return_code'       => $notify->return_code,
                'sign'              => $notify->sign,
                'time_end'          => $notify->time_end,
                'transaction_id'    => $notify->transaction_id
            );

            //已经支付了
            if($order->pay_time){
                return true;
            }

            if($successful){

                //更新支付时间和订单状态
                $this->_model->pay($order->id , ($notify->total_fee / 100) , 1 , $notify->time_end);

                $storeModel = new StoreInfo;
                $store = $storeModel->getStoreList(array('ids'=>$order->store_id));

                if(empty($store)){
                    return true;
                }

                $bell = empty($store[0]->bell) ? 'default' : $store[0]->bell;

                //消息推送队列
                $this->dispatch(new Jpush(
                    "急所需有新订单啦,请及时处理",
                    "急所需新订单",
                    array('ios' , 'android'),
                    "$order->store_id",
                    array(),
                    $bell
                ));
            }

            //更新微信日志
            $wechatPayLogModel = new WechatPayLog();
            $wechatPayLogModel->updateWechatLog($outTradeNo , $data);

            // 你的逻辑
            return true; // 或者错误消息
        });

        return $response;
    }


    /**
     *
     * 微信验证token
     */

    public function wechatToken(){

        $signature      = $_GET['signature'];
        $timestamp      = $_GET['timestamp'];
        $nonce          = $_GET['nonce'];
        $token          = "03fc1d5aa549c36";
        $tmpArr = array($token , $timestamp , $nonce);
        sort($tmpArr);

        if(sha1(implode($tmpArr)) == $signature){
            echo $_GET['echostr'];exit;
        }else{
            return false;
        }
    }

}
