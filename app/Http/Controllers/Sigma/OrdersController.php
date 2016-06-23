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

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order as WechatOrder;

//use App\Libs\Jpush;

use App\Jobs\Jpush;

class OrdersController extends ApiController
{
    private $_model;
    private $_length;

    private $pubOptions;
    private $openOptions;

    public function __construct(){
        parent::__construct();
        $this->_model = new Orders;
        $this->_length		= 20;

        $this->pubOptions      = [
            'app_id' => Config::get('wechat.app_id'),
            'secret' => Config::get('wechat.secret'),
            'token'  => Config::get('wechat.token'),

            'payment' => [
                'merchant_id'        => Config::get('wechat.merchant_id'),
                'key'                => Config::get('wechat.key'),
                'cert_path'          => Config::get('wechat.cert_path'), // XXX: 绝对路径！！！！
                'key_path'           => Config::get('wechat.key_path'),      // XXX: 绝对路径！！！！
                'notify_url'         => Config::get('wechat.notify_url'),       // 你也可以在下单时单独设置来想覆盖它
            ],
        ];

        $this->openOptions      = [
            'app_id' => Config::get('wechat.open_app_id'),
            'secret' => Config::get('wechat.open_secret'),

            'payment' => [
                'merchant_id'        => Config::get('wechat.open_merchant_id'),
                'key'                => Config::get('wechat.open_key'),
                'cert_path'          => Config::get('wechat.open_cert_path'), // XXX: 绝对路径！！！！
                'key_path'           => Config::get('wechat.open_key_path'),      // XXX: 绝对路径！！！！
                'notify_url'         => Config::get('wechat.open_notify_url'),       // 你也可以在下单时单独设置来想覆盖它
            ],
        ];
    }

    /**
     * @api {POST} /sigma/order/info/{orderId} 获取订单详情
     * @apiName orderInfo
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取订单详情
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/info/1
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/info/1
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getOrderInfo($orderId){

        $info   = $this->_model->getOrderList($this->userId , array('id' => $orderId) , 1 , 0);

        if(isset($info[0])) {
            return response()->json(Message::setResponseInfo('SUCCESS' , $info[0]));
        }else{
            return response()->json(Message::setResponseInfo('SUCCESS' , $info));
        }

    }

    /**
     * @api {POST} /sigma/order/list[?page=1] 获取订单列表
     * @apiName orders
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取订单列表
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/list?page=1
     *
     * @apiParam {number} type 1为有效订单
     * @apiParam {string} search 搜索条件
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/list?page=1
     *      {
     *          type : 1
     *      }
     * @apiUse CODE_200
     *
     */
    public function getOrderList(Request $request){
        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $type = 0;
        if($request->has('type')){
            $type = $request->get('type');
        }

        if ($type == 1){
            $search['status'] = array(
                Config::get('orderstatus.paid')['status'] ,
                Config::get('orderstatus.on_the_way')['status'] ,
                Config::get('orderstatus.accepted')['status'] ,
                Config::get('orderstatus.completd')['status'] ,
                Config::get('orderstatus.arrive')['status'] ,
                Config::get('orderstatus.refunding')['status']
            );
        }

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

        $orderNum   = $this->_model->getOrderTotalNum($this->userId , $search);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $orderNum);
        $response['orders']   = $this->_model->getOrderList($this->userId , $search , $this->_length , $response['pageData']->offset);

        return response()->json(Message::setResponseInfo('SUCCESS' , $response));
    }

    /**
     * @api {POST} /sigma/order/change/status/{id} 修改订单状态
     * @apiName ordersChangeStatus
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 修改订单状态
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/change/status/1
     *
     * @apiParam {number} status 状态
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/change/status/1
     *      {
     *          status : 3
     *      }
     * @apiUse CODE_200
     *
     */
    public function changeStatus($id , Request $request){

        $validation = Validator::make($request->all(), [
            'status'          => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $status = $request->get('status');

        if($status != Config::get('orderstatus.completd') || $status != Config::get('orderstatus.cancel') || $status != Config::get('orderstatus.arrive')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        if($this->_model->changeStatus($this->storeId , $this->userId , $id , $status)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/order/init 下单
     * @apiName orderint
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 下单
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/init
     *
     * @apiParam {int} store 店铺ID
     * @apiParam {json} goods 商品信息
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/init
     *      {
     *          store:1,
     *          goods:[{"goods_id":"1","num":"1"},{"goods_id":"3","num":"2"},{"goods_id":"4","num":"3"}]
     *      }
     * @apiUse CODE_200
     *
     */
    public function initOrder(Request $request){

        $validation = Validator::make($request->all(), [
            'goods'             => 'required',
            'store'             => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $userId     = $this->userId;

        //判断是否绑定手机
        $userModel = new Users;
        $userinfo = $userModel->getUserInfoById($userId);

        if(empty($userinfo->mobile)){
            return response()->json(Message::setResponseInfo('MOBILE_NO_BIND'));
        }

        $storeId    = $request->get('store');
        $goods = json_decode($request->get('goods'));

        $orderId = $this->_model->initOrder($storeId , $userId , $goods);
        if($orderId){
            return response()->json(Message::setResponseInfo('SUCCESS' , $orderId));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /sigma/order/confirm/{orderId} 确认订单
     * @apiName ordersConfirm
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 确认订单
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/confirm/1
     *
     * @apiParam {int} pay_type 支付方式
     * @apiParam {int} out_points 使用积分
     * @apiParam {string} pay_password 支付密码
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/confirm/1
     *      {
     *          pay_type : 1,
     *          out_points : 328,
     *          pay_password : 123456,
     *      }
     * @apiUse CODE_200
     *
     */
    public function confirmOrder($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'pay_type'             => 'required'
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
        $payType    = $request->get('pay_type');

        //更新订单状态
        $payNum = $this->_model->confirmOrder( $userId , $orderId , $payType , $outPoints);

        if($payNum['code'] != 0000){
            return $payNum;
        }

        if($payNum['data'] < 0){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        //如果是积分全额支付
        if($payNum['data'] == 0 && $outPoints != 0){
            $payType = Config::get('paytype.money');

            if(!$request->has('pay_password')){
                return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
            }
        }

        $order = $this->_model->getOrderList($this->userId , array('id' => $orderId));
        if(!isset($order[0])){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        $order = $order[0];

        //如果是余额支付,直接进入支付环节
        if($payType == Config::get('paytype.money')){
            $userModel = new Users;
            $payPassword = $request->get('pay_password');
            $userInfo  =  $userModel->getUserPayPassword($this->userId);

            if(empty($userInfo->pay_password)){
                return response()->json(Message::setResponseInfo('NO__HAVE_PAY_PASSWORD'));
            }
            if($userInfo->pay_password != $this->encrypt($payPassword , $userInfo->pay_salt)){
                return response()->json(Message::setResponseInfo('PAY_PASSWORD_ERROR'));
            }
            if($this->_model->pay($orderId , $payNum['data'] , $payType)){
                $storeModel = new Stores;
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

                return response()->json(Message::setResponseInfo('SUCCESS'));
            }else{
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }else{
            return response()->json(Message::setResponseInfo('SUCCESS' , $payNum));
        }

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
     *          pay_type    : 4
     *      }
     * @apiUse CODE_200
     *
     */
    public function wechatPay($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'trade_type'            => 'required',
            'pay_type'              => 'required'
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
        $tradeType      = $request->get('trade_type');
        $payType        = $request->get('pay_type');

        //更新订单
        $payNum = $this->_model->confirmOrder( $userId , $orderId , $payType , $outPoints);

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
        //$attributes['total_fee']        = 1;
        $attributes['fee_type']         = 1;
        //$attributes['time_start']       = date('YmdHis' , time());
        //$attributes['time_expire']      = date('YmdHis' , time() + 30 * 60);
        $attributes['attach']           = $orderId;
        $attributes['total_fee']        = (int)($payNum['data'] * 100);

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

            $payLog = array(
                'trade_type'        => $attributes['trade_type'],
                'body'              => $attributes['body'],
                'detail'            => $attributes['detail'],
                'out_trade_no'      => $attributes['out_trade_no'],
                'openid'            => $attributes['openid'],
                'total_fee'         => $attributes['total_fee'],
                'fee_type'          => $attributes['fee_type'],
                'spbill_create_ip'  => $order->spbill_create_ip,
                'timeStamp'         => $json->timeStamp,
                'nonceStr'          => $json->nonceStr,
                'package'           => $json->package,
                'signType'          => $json->signType,
                'paySign'           => $json->paySign
            );

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

        return $this->setPayData($app , 4);

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

        return $this->setPayData($app , 1);

    }

    private function setPayData($app , $payType){

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

                $storeModel = new Stores;
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
     * @api {POST} /sigma/order/status/{orderId} 获取订单状态
     * @apiName ordersStatus
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取订单状态
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/status
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/status
     *      {
     *          trade_type  : APP
     *      }
     * @apiUse CODE_200
     *
     */
    public function getOrderStatus($orderId , Request $request){
        $orderInfo = $this->_model->getOrderById($this->userId , $orderId);

        if(!$orderInfo){
            return response()->json(Message::setResponseInfo('ORDER_NOT_EXIST'));
        }
        $tradeType    = $request->get('trade_type');

        $orderNo = $orderInfo->out_trade_no;

        if($orderInfo->status == Config::get('orderstatus.no_pay')['status'] && !empty($orderNo)){
            if($tradeType == 'APP') {

                $app = new Application($this->openOptions);

            }

            $payment = $app->payment;
            $wechat = $payment->query($orderNo);

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($wechat));

        }

        return response()->json(Message::setResponseInfo('SUCCESS' , $orderInfo->status));



    }


    /**
     * @api {POST} /sigma/order/update/address/{orderId} 修改订单地址
     * @apiName ordersUpdateAddress
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 修改订单地址
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/update/address/1
     *
     * @apiParam {int} address_id 地址ID
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/update/address/1
     *      {
     *          address_id : 2,
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function updateOrderAddress($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'address_id'             => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $userId     = $this->userId;

        $addressId    = $request->get('address_id');

        $order = $this->_model->getOrderStatus($orderId);

        if($order->status == Config::get('orderstatus.no_pay')['status']) {

            if ($this->_model->updateOrderAddress($userId, $orderId, $addressId)) {
                return response()->json(Message::setResponseInfo('SUCCESS'));
            } else {
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }else{
            return response()->json(Message::setResponseInfo('NOT_UPDATE_ADDRESS'));
        }

    }

    /**
     * @api {POST} /sigma/order/refund/{orderId} 退款原因
     * @apiName ordersRefundReason
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 退款原因
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/refund/1
     *
     * @apiParam {string} content 原因
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/refund/1
     *      {
     *          content : "太慢了,懒得等",
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function refund($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'content'             => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $userId     = $this->userId;

        $content    = $request->get('content');

        if($this->_model->refund( $userId , $orderId , $content )){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/order/status/log/{orderId} 获取订单状态改变的日志
     * @apiName ordersStatusLog
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取订单状态改变的日志
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/status/log/1
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/status/log/1
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getOrderStatusLog($orderId){
        return response()->json(Message::setResponseInfo('SUCCESS' , $this->_model->getOrderLog( $orderId  ) ));
    }

    /**
     * @api {POST} /sigma/order/complaint/{orderID} 投诉
     * @apiName ordersComplaint
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 投诉
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/complaint/1
     *
     * @apiParam {int} order_id 订单ID
     * @apiParam {string} content 投诉内容
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/complaint/1
     *      {
     *          'content'  : "配送太慢了"
     *      }
     * @apiUse CODE_200
     *
     */
    public function complaint($orderId , Request $request){
        $validation = Validator::make($request->all(), [
            'content'               => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();
        $data['user_id']     = $this->userId;
        $data['order_id']    = $orderId;
        $data['content']     = $request->get('content');
        $data['created_at']  = date('Y-m-d H:i:s' , time());

        if($this->_model->complaint($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/order/evaluate/1 评价
     * @apiName ordersEvaluate
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 评价
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/evaluate/1
     *
     * @apiParam {string} content 评价内容
     * @apiParam {FLOAT} speed 配送速度
     * @apiParam {FLOAT} attitude 服务态度
     * @apiParam {FLOAT} quality 商品质量
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/evaluate/1
     *      {
     *          'content'   : "太好了",
     *          'speed'     : 4,
     *          'attitude'  : 5,
     *          'quality'   : 5
     *      }
     * @apiUse CODE_200
     *
     */
    public function evaluate($orderId , Request $request){
        $validation = Validator::make($request->all(), [
            'speed'                 => 'required',
            'attitude'              => 'required',
            'quality'              => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();
        $data['user_id']        = $this->userId;
        $data['speed']          = $request->get('speed');
        $data['attitude']       = $request->get('attitude');
        $data['quality']        = $request->get('quality');
        $data['order_id']       = $orderId;
        $data['content']        = $request->get('content');
        $data['created_at']     = date('Y-m-d H:i:s' , time());

        if($this->_model->evaluate($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

}
