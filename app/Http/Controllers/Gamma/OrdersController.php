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
use Validator , Input;
use Session , Cookie , Config , Log;

use App\Http\Controllers\ApiController;

use App\Models\Gamma\Orders;
use App\Libs\Message;
use App\Libs\BLogger;

use EasyWeChat\Foundation\Application;

use App\Libs\Alipay\Alipay;

class OrdersController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Orders;
        $this->_length		= 10;
    }

    /**
     * @api {POST} /gamma/store/orders[?page=1] 获取订单列表
     * @apiName orders
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取订单列表
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/orders?page=1
     *
     * @apiParam {number} status 订单类型 1获取新订单 2获取配送中的订单 3获取完成的订单 4获取意外订单 5获取有效订单
     * @apiParam {string} search 搜索条件
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/orders/1?page=1
     *      {
     *          search : 18401586654
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
            $search['status'] = array(Config::get('orderstatus.paid')['status']);
        }elseif ($type == 2){
            $search['status'] = array(Config::get('orderstatus.on_the_way')['status']);
        }elseif ($type == 3){
            $search['status'] = array(Config::get('orderstatus.completd')['status'] , Config::get('orderstatus.arrive')['status']);
        }elseif ($type == 4){
            $search['status'] = array(Config::get('orderstatus.refunding')['status'] , Config::get('orderstatus.refunded')['status']);
        }elseif ($type == 5){
            $search['status'] = array(
                Config::get('orderstatus.paid')['status'] ,
                Config::get('orderstatus.on_the_way')['status'] ,
                Config::get('orderstatus.accepted')['status'] ,
                Config::get('orderstatus.completd')['status'] ,
                Config::get('orderstatus.arrive')['status'] ,
                Config::get('orderstatus.refunding')['status']
            );
        }else{
            $search['status'] = array(
                Config::get('orderstatus.paid')['status'] ,
                Config::get('orderstatus.on_the_way')['status'] ,
                Config::get('orderstatus.completd')['status'] ,
                Config::get('orderstatus.arrive')['status'] ,
                Config::get('orderstatus.refunding')['status'] ,
                Config::get('orderstatus.refunded')['status']
            );
        }

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

        $orderNum   = $this->_model->getOrderTotalNum($this->storeId , $search);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $orderNum);
        $response['orders']   = $this->_model->getOrderList($this->storeId , $search , $this->_length , $response['pageData']->offset);

        return response()->json(Message::setResponseInfo('SUCCESS' , $response));
    }

    /**
     * @api {POST} /gamma/store/orders/change/status/{id} 修改订单状态
     * @apiName ordersChangeStatus
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 修改订单状态
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/orders/change/status/1
     *
     * @apiParam {number} status 状态
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/orders/change/status/1
     *      {
     *          status : 3
     *      }
     * @apiUse CODE_200
     *
     */
    public function changeStatus($orderId , Request $request){
        $validation = Validator::make($request->all(), [
            'status'          => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $status = $request->get('status');

        //确认退款
        if($status == Config::get('orderstatus.refunded')['status']){

            $orderInfo = $this->_model->getOrderList($this->storeId , array('id'=>$orderId));
            if(!isset($orderInfo[0])){
                return response()->json(Message::setResponseInfo('FAILED'));
            }
            if($orderInfo[0]->status != Config::get('orderstatus.refunding')['status']){
                return response()->json(Message::setResponseInfo('FAILED'));
            }


            $refundNo = time() . $this->getSalt(6 , 1);

            //$payTotal   = $orderInfo[0]->pay_total;
            $payTotal   = 0.01;
            $orderNo    = $orderInfo[0]->out_trade_no;

            if($orderInfo[0]->pay_type_id == 1 || $orderInfo[0]->pay_type_id == 4) {
                $type = 1;
                if($orderInfo[0]->pay_type_id == 4){
                    $type = 2;
                }
                if ($this->_wechatRefund($orderNo, $refundNo, $payTotal * 100 , $type)) {
                    if ($this->_model->refund($orderId, $refundNo)) {
                        return response()->json(Message::setResponseInfo('SUCCESS'));
                    } else {
                        return response()->json(Message::setResponseInfo('FAILED'));
                    }
                }
            }elseif($orderInfo[0]->pay_type_id == 2){
                $this->_aliPayRefund($orderNo, $refundNo, $payTotal);
            }

        }else {

            if ($this->_model->changeStatus($this->storeId, $this->userId, $orderId, $status)) {
                return response()->json(Message::setResponseInfo('SUCCESS'));
            } else {
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }
    }

    public function _wechatRefund($orderNo , $refundNo , $payTotal , $type=1){

        $options = array();

        if($type == 1) {
            $options = [
                'app_id' => Config::get('wechat.open_app_id'),
                'secret' => Config::get('wechat.open_secret'),

                'payment' => [
                    'merchant_id' => Config::get('wechat.open_merchant_id'),
                    'key' => Config::get('wechat.open_key'),
                    'cert_path' => public_path() . Config::get('wechat.open_cert_path'), // XXX: 绝对路径！！！！
                    'key_path'  => public_path() . Config::get('wechat.open_key_path'),      // XXX: 绝对路径！！！！
                    'fee_type'  => 'CNY'
                ],
            ];
        }else{
            $options      = [
                'app_id' => Config::get('wechat.app_id'),
                'secret' => Config::get('wechat.secret'),
                'token'  => Config::get('wechat.token'),

                'payment' => [
                    'merchant_id'        => Config::get('wechat.merchant_id'),
                    'key'                => Config::get('wechat.key'),
                    'cert_path'          => public_path() . Config::get('wechat.cert_path'), // XXX: 绝对路径！！！！
                    'key_path'           => public_path() . Config::get('wechat.key_path'),      // XXX: 绝对路径！！！！
                    'fee_type' => 'CNY'
                ],
            ];
        }

        $app = new Application($options);
        $payment = $app->payment;

        $result = $payment->refund($orderNo, $refundNo, $payTotal);
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($result));
        if($result['return_code'] == 'SUCCESS' && $result['return_msg'] == 'OK' && $result['result_code'] == 'SUCCESS') {
            return true;
        }else{
            return false;
        }
    }

    public function _aliPayRefund($orderNo , $refundNo , $payTotal){
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => trim(Config::get('alipay.service')),
            "partner" => trim(Config::get('alipay.partner')),
            "notify_url"	=> trim(Config::get('alipay.notify_url')),
            "seller_user_id"	=> trim(Config::get('alipay.seller_user_id')),
            "refund_date"	=> trim(Config::get('alipay.refund_date')),
            "batch_no"	=> date('YmdHis' , time()) . $this->getSalt(4, 1),
            "batch_num"	=> 1,
            "detail_data"	=> $orderNo.'^'.$payTotal.'^'.'正常退款',
            "_input_charset"	=> trim(strtolower(Config::get('alipay.input_charset')))

        );

        $alipay = new Alipay($parameter);

        $result = $alipay->buildRequestHttp($parameter);

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($result));
    }

}