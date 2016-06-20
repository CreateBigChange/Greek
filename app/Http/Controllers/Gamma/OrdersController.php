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
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($orderInfo));
            if(!isset($orderInfo[0])){
                return response()->json(Message::setResponseInfo('FAILED'));
            }
            if($orderInfo[0]->status != Config::get('orderstatus.refunding')['status']){
                return response()->json(Message::setResponseInfo('FAILED'));
            }


            $refundNo = time() . $this->getSalt(6 , 1);

            $payTotal   = $orderInfo[0]->pay_total;
            $orderNo    = $orderInfo[0]->out_trade_no;

            if($orderInfo[0]->pay_type_id == 1) {
                if ($this->_wechatRefund($orderNo, $refundNo, $payTotal)) {
                    BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode(4444444444444444444444444444));
                    if ($this->_model->refund($orderId, $refundNo)) {
                        return response()->json(Message::setResponseInfo('SUCCESS'));
                    } else {
                        return response()->json(Message::setResponseInfo('FAILED'));
                    }
                }
            }elseif($orderInfo[0]->pay_type_id == 2){

            }

        }else {

            if ($this->_model->changeStatus($this->storeId, $this->userId, $orderId, $status)) {
                return response()->json(Message::setResponseInfo('SUCCESS'));
            } else {
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }
    }

    public function _wechatRefund($orderNo , $refundNo , $payTotal ){
        $openOptions      = [
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

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($openOptions));
        $app = new Application($openOptions);
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($app->payment));
        $payment = $app->payment;

        $result = $payment->refund($orderNo, $refundNo, $payTotal);
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode(999999));
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($result));
        if($result) {
            return true;
        }else{
            return false;
        }
    }

}