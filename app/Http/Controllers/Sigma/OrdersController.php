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
use Session , Cookie , Config,Log;

use App\Models\Sigma\Users;

use App\Http\Controllers\ApiController;

use App\Models\Sigma\Orders;
use App\Libs\Message;
use App\Libs\BLogger;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;


class OrdersController extends ApiController
{
    private $_model;
    private $_length;

    private $options;

    public function __construct(){
        parent::__construct();
        $this->_model = new Orders;
        $this->_length		= 20;

        $this->options      = [
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
     * @apiParam {number} status 订单类型 1获取新订单 2获取配送中的订单 3获取完成的订单 4获取意外订单
     * @apiParam {string} search 搜索条件
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/list?page=1
     *      {
     *          status : 1
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
        if($request->has('status')){
            $type = $request->get('status');
        }

        if ($type == 1){
            $search['status'] = array('2');
        }elseif ($type == 2){
            $search['status'] = array('3');
        }elseif ($type == 3){
            $search['status'] = array('4');
        }elseif ($type == 4){
            $search['status'] = array('5' , '6' , '7');
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
            return $this->_model->pay( $userId , $orderId , $payNum['data'] , $payType);
        }else{
            return $payNum;
        }

    }

    /**
     * @api {POST} /sigma/order/confirm/wechat/{orderId} 确认订单-微信
     * @apiName ordersConfirmWechat
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 确认订单-微信
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/confirm/wechat/1
     *
     * @apiParam {int} pay_type 支付方式
     * @apiParam {int} out_points 使用积分
     * @apiParam {string} trade_type 来源[JSAPI，NATIVE，APP]
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/confirm/wechat/1
     *      {
     *          out_points : 328,
     *          trade_type : JSAPI,
     *      }
     * @apiUse CODE_200
     *
     */
    public function wechatPay($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'trade_type'             => 'required'
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
        $payNum = $this->_model->confirmOrder( $userId , $orderId , 1 , $outPoints);

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
            $detail .= $g->name . ' ' . $g->c_name . ' ' . $g->b_name . ' ' . $g->num . '<br />';
        }

        $app = new Application($this->options);

        $payment = $app->payment;

        $attributes = [
            'trade_type'       => $tradeType, // JSAPI，NATIVE，APP...
            'body'             => $body,
            'detail'           => $detail,
            'out_trade_no'     => time() . $info[0]->id . $this->getSalt(8 , 1),
            'openid'           => 'ovZmSs24EokHlOEyNT8Qn8a8EbZc',
            'total_fee'        => $payNum['data'] * 100,
            'notify_url'       => 'http://preview.jisxu.com/wechat/notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
        ];

        $order = new Order($attributes);

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($order));

        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
            $json = $payment->configForPayment($prepayId);
            $json = json_decode($json);
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($json));
            return response()->json(Message::setResponseInfo('SUCCESS' , $json));
        }else{
            return response()->json(Message::setResponseInfo('FAILED' , $result));
        }

    }


    public function notify(){

        $app = new Application($this->options);

        $response = $app->payment->handleNotify(function($notify, $successful){

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($notify));

            if($successful){

            }
            // 你的逻辑
            return true; // 或者错误消息
        });

        return $response;
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