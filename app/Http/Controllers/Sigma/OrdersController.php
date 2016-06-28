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
use Validator , Input , RedisClass as Redis;
use Session , Cookie , Config , Log;

use App\Models\User;
use App\Models\WechatPayLog;

use App\Http\Controllers\ApiController;

use App\Models\Order;
use App\Models\StoreInfo;
use App\Models\OrderLog;
use App\Models\OrderComplaint;
use App\Models\OrderEvaluate;
use App\Libs\Message;

use App\Jobs\Jpush;

class OrdersController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Order;
        $this->_length		= 20;

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

        $info   = $this->_model->getOrderList( array('user' => $this->userId  , 'id' => $orderId) , 1 , 0);

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
                //Config::get('orderstatus.accepted')['status'] ,
                Config::get('orderstatus.completd')['status'] ,
                Config::get('orderstatus.arrive')['status'] ,
                Config::get('orderstatus.refunding')['status']
            );
        }

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

        $search['user'] = $this->userId;

        $orderNum   = $this->_model->getOrderTotalNum( $search);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $orderNum);
        $response['orders']   = $this->_model->getOrderList($search , $this->_length , $response['pageData']->offset);

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

        $orderInfo = $this->_model->getOrderList(array('store' => $this->storeId , 'id'=>$id , 'user'=>$this->userId));
        if(!isset($orderInfo[0])){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        /**
         * **************************************
         * 订单状态
         * **************************************
         */
        foreach (Config::get('orderstatus') as $status) {
            if ($orderInfo[0]->status == $status['status']) {
                if (!in_array($status, $status['next'])) {
                    return response()->json(Message::setResponseInfo('FAILED'));
                }
            }
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
        $storeId    = $request->get('store');
        $goods      = json_decode($request->get('goods'));
        $userId     = $this->userId;

        $storeModel = new StoreInfo();
        $storeInfo  = $storeModel->getStoreInfo($storeId);

        if(!$storeInfo){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        if($storeInfo->is_close == 1 || $storeInfo->isDoBusiness == 0){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        //判断是否绑定手机
        $userModel = new User;
        $userinfo = $userModel->getUserInfoById($userId);

        if(empty($userinfo->mobile)){
            return response()->json(Message::setResponseInfo('MOBILE_NO_BIND'));
        }



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

        $order = $this->_model->getOrderList(array('user' => $this->userId  , 'id' => $orderId));
        if(!isset($order[0])){
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        $order = $order[0];

        //如果是余额支付,直接进入支付环节
        if($payType == Config::get('paytype.money')){
            $userModel = new User;
            $payPassword = $request->get('pay_password');
            $userInfo  =  $userModel->getUserPayPassword($this->userId);

            if(empty($userInfo->pay_password)){
                return response()->json(Message::setResponseInfo('NO__HAVE_PAY_PASSWORD'));
            }
            if($userInfo->pay_password != $this->encrypt($payPassword , $userInfo->pay_salt)){
                return response()->json(Message::setResponseInfo('PAY_PASSWORD_ERROR'));
            }
            if($this->_model->pay($orderId , $payNum['data'] , $payType)){
                $storeModel = new StoreInfo;
                $store = $storeModel->getStoreList(array('ids'=>$order->store_id));

                if(empty($store)){
                    return true;
                }

                $bell = empty($store[0]->bell) ? 'default' : $store[0]->bell;

                $new =  Redis::get("store:$order->store_id:new") == null ? 0 : Redis::get("store:$order->store_id:new");
                $new = $new + 1;

                Redis::set("store:$order->store_id:new"  , $new );

                //消息推送队列
                $this->dispatch(new Jpush(
                    "急所需有新订单啦,请及时处理",
                    "急所需新订单",
                    array('ios' , 'android'),
                    "$order->store_id",
                    array(),
                    $bell,
                    'new'
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
    public function getOrderStatus($orderId){
        $orderInfo = $this->_model->getOrderById($this->userId , $orderId);

        if(!$orderInfo){
            return response()->json(Message::setResponseInfo('ORDER_NOT_EXIST'));
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

        $order = $this->_model->getOrderList(array('user' => $this->userId  , 'id' => $orderId));
        if(!isset($order[0])){
            return response()->json(Message::setResponseInfo('FAILED'));
        }
        $order = $order[0];

        $userId     = $this->userId;

        $userModel = new User();
        $userInfo =$userModel->getUserInfoById($userId);

        //积分不足不能退款
        if($userInfo->points < $order->in_points - $order->out_points){
            return response()->json(Message::setResponseInfo('REFUND_POINT_NOT_AMPLE'));
        }

        $content    = $request->get('content');

        $request = $this->_model->refundReson( $userId , $orderId , $content );

        if($request){
            $storeModel = new StoreInfo;
            $store = $storeModel->getStoreList(array('ids'=>$order->store_id));

            if(empty($store)){
                return response()->json(Message::setResponseInfo('FAILED'));
            }

            $bell = empty($store[0]->bell) ? 'default' : $store[0]->bell;

            $accident =  Redis::get("store:$order->store_id:accident") == null ? 0 : Redis::get("store:$order->store_id:accident");

            Redis::set("store:$order->store_id:accident"  , $accident++ );

            //消息推送队列
            $this->dispatch(new Jpush(
                "急所需有退款订单,请及时处理",
                "急所需意外订单",
                array('ios' , 'android'),
                "$order->store_id",
                array(),
                $bell,
                'accident'
            ));

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
        $orderLogModel = new OrderLog;
        return response()->json(Message::setResponseInfo('SUCCESS' , $orderLogModel->getOrderLog( $orderId  ) ));
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

        $orderComplaintModel = new OrderComplaint;

        if($orderComplaintModel->complaint($data)){
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

        $orderEvaluateModel = new OrderEvaluate;
        if($orderEvaluateModel->evaluate($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }
    
}
