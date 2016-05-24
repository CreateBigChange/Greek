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
use Session , Cookie , Config;

use App\Http\Controllers\ApiController;

use App\Models\Sigma\Orders;
use App\Libs\Message;

class OrdersController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Orders;
        $this->_length		= 20;
    }

    /**
     * @api {POST} /sigma/order/info/{orderId} 获取订单详情
     * @apiName orderInfo
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取订单详情
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/order/info
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/order/info/1
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
     * @api {POST} /sigma/orders/change/status/{id} 修改订单状态
     * @apiName ordersChangeStatus
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 修改订单状态
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/orders/change/status/1
     *
     * @apiParam {number} status 状态
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/orders/change/status/1
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
     * @apiParam {json} data 状态
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
     * @api {POST} /sigma/orders/confirm/{orderId} 确认订单
     * @apiName ordersConfirm
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 确认订单
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/orders/confirm/1
     *
     * @apiParam {int} pay_type 支付方式
     * @apiParam {int} put_points 使用积分
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/orders/confirm/1
     *      {
     *          pay_type : 1,
     *          out_points : 328,
     *      }
     * @apiUse CODE_200
     *
     */
    public function confirmOrder($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'pay_type'             => 'required',
            'out_points'           => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $userId     = $this->userId;

        $payType    = $request->get('pay_type');
        $outPoints  = $request->get('out_points');

        $data = $this->_model->confirmOrder( $userId , $orderId , $payType , $outPoints);

        return response()->json($data);

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

        if($this->_model->updateOrderAddress( $userId , $orderId , $addressId )){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/order/refund/reason/{orderId} 退款原因
     * @apiName ordersRefundReason
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 退款原因
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/refund/reason/1
     *
     * @apiParam {string} content 原因
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/refund/reason/1
     *      {
     *          content : "太慢了,懒得等",
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function refundReason($orderId , Request $request){

        $validation = Validator::make($request->all(), [
            'content'             => 'required'
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $userId     = $this->userId;

        $content    = $request->get('content');

        if($this->_model->refundReason( $userId , $orderId , $content )){
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
        $userId     = $this->userId;
        return response()->json(Message::setResponseInfo('SUCCESS' , $this->_model->getOrderLog( $userId , $orderId  ) ));
    }

    /**
     * @api {POST} /sigma/order/complaint 投诉
     * @apiName ordersComplaint
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 投诉
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/complaint
     *
     * @apiParam {int} store_id 店铺ID
     * @apiParam {int} order_id 订单ID
     * @apiParam {string} content 投诉内容
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/complaint
     *      {
     *          'order_id' : 1,
     *          'store_id' : 1,
     *          'content'  : "配送太慢了"
     *      }
     * @apiUse CODE_200
     *
     */
    public function complaint(Request $request){
        $validation = Validator::make($request->all(), [
            'order_id'              => 'required',
            'content'               => 'required',
            'store_id'              => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();
        $data['user_id']     = $this->userId;
        $data['store_id']    = $request->get('store_id');
        $data['order_id']    = $request->get('order_id');
        $data['content']     = $request->get('content');
        $data['created_at']  = date('Y-m-d H:i:s' , time());

        if($this->_model->complaint($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /sigma/order/evaluate 评价
     * @apiName ordersEvaluate
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 评价
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/order/evaluate
     *
     * @apiParam {int} order_id 订单ID
     * @apiParam {string} content 评价内容
     * @apiParam {FLOAT} speed 配送速度
     * @apiParam {FLOAT} attitude 服务态度
     * @apiParam {FLOAT} quality 商品质量
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/order/evaluate
     *      {
     *          'order_id'  : 1,
     *          'content'   : "太好了",
     *          'speed'     : 4,
     *          'attitude'  : 5,
     *          'quality'   : 5
     *      }
     * @apiUse CODE_200
     *
     */
    public function evaluate(Request $request){
        $validation = Validator::make($request->all(), [
            'order_id'              => 'required',
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
        $data['order_id']       = $request->get('order_id');
        $data['content']        = $request->get('content');
        $data['created_at']     = date('Y-m-d H:i:s' , time());

        if($this->_model->evaluate($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

}