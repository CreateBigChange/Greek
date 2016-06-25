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

use App\Models\Order;
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
        $this->_model = new Order;
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

        $search['store'] = $this->storeId;

        $orderNum   = $this->_model->getOrderTotalNum($search);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $orderNum);
        $response['orders']   = $this->_model->getOrderList($search , $this->_length , $response['pageData']->offset);

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

        $orderInfo = $this->_model->getOrderList(array('store' => $this->storeId , 'id'=>$orderId));

        if(!isset($orderInfo[0])){
            return response()->json(Message::setResponseInfo('FAILED'));
        }
        if($orderInfo[0]->status == Config::get('orderstatus.refunding')['status']){
            if($status != Config::get('orderstatus.refunded')['status']){
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }

        //确认退款
        if($status == Config::get('orderstatus.refunded')['status']){

            if($orderInfo[0]->status != Config::get('orderstatus.refunding')['status']){
                return response()->json(Message::setResponseInfo('FAILED'));
            }


            $refundNo = time() . $this->getSalt(6 , 1);

            $payTotal   = $orderInfo[0]->pay_total;
            //$payTotal   = 0.01;
            $orderNo    = $orderInfo[0]->out_trade_no;

            if($orderInfo[0]->pay_type_id == 1 || $orderInfo[0]->pay_type_id == 4) {
                $type = 1;
                if($orderInfo[0]->pay_type_id == 4){
                    $type = 2;
                }
                if ($this->_wechatRefund($orderNo, $refundNo, $payTotal * 100 , $type)) {
                    if ($this->_model->refund($this->storeId, $orderId, $refundNo)) {
                        return response()->json(Message::setResponseInfo('SUCCESS'));
                    } else {
                        return response()->json(Message::setResponseInfo('FAILED'));
                    }
                }
            }elseif($orderInfo[0]->pay_type_id == 2){
                $this->_aliPayRefund($orderNo, $refundNo, $payTotal);
            }

        }else {

            if ($this->_model->changeStatus($this->storeId, $orderInfo[0]->user, $orderId, $status)) {
                return response()->json(Message::setResponseInfo('SUCCESS'));
            } else {
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }
    }

    public function _wechatRefund($orderNo , $refundNo , $payTotal , $type=1){

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
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode(111111111111111111));
        //构造要请求的参数数组，无需改动
        $parameter = array(
//            "service" => trim(Config::get('alipay.service')),
//            "partner" => trim(Config::get('alipay.partner')),
//            "notify_url"	=> trim(Config::get('alipay.notify_url')),
//            "seller_user_id"	=> trim(Config::get('alipay.seller_user_id')),
//            "refund_date"	=> trim(Config::get('alipay.refund_date')),
//            "batch_no"	=> date('YmdHis' , time()) . $this->getSalt(4, 1),
//            "batch_num"	=> 1,
//            "detail_data"	=> '2016062021001004120267447870'.'^'.$payTotal.'^'.'正常退款',
//            "_input_charset"	=> trim(strtolower(Config::get('alipay.input_charset')))
            "service"           => trim(Config::get('alipay.service')),
            "partner"           => trim(Config::get('alipay.partner')),
            "charset"	        => trim(Config::get('alipay.input_charset')),
            "timestamp"         => date('Y-m-d H:i:s' , time()),
            "version"           => "1.0",
            "out_trade_no"      => $orderNo,
            "refund_amount"     => $payTotal,
            "refund_reason"     => "正常退款"
        );
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($parameter);
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(Config::get('alipay'));


        $alipay = new Alipay(Config::get('alipay'));

        $result = $alipay->buildRequestHttp($parameter);

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($result);
    }





    public function orderCount(){

            return view('order_count');
    }

    public function ajaxOrderCount(Request $request){
        $year   = date('Y');
        $month  = date('m');
        $day    = date('d');

        $type = $request->get('type');

        $response = array();
        if($type == 1){
            $count = $this->_model->orderCountDay($this->storeId, $year, $month, $day);
            $todayTime = array(
                '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'
            );

            $orderNum = 0;
            $orderCompleteNum = 0;
            $orderAccidentNum = 0;

            $orderCount = array();

            for ($i = 0; $i < count($todayTime); $i++) {
                $orderCount[$i] = 0;
                foreach ($count as $c) {
                    if ($c->hour == $todayTime[$i]) {
                        $orderCount[$i] += $c->num;
                        $orderNum += $c->num;
                        if($c->status == Config::get('orderstatus.completd')['status']){
                            $orderCompleteNum += $c->num;
                        }elseif($c->status == Config::get('orderstatus.cancel')['status'] || $c->status = Config::get('orderstatus.refunding')['status'] || $c->status = Config::get('orderstatus.refunded')['status']){
                            $orderAccidentNum += $c->num;
                        }
                    }
                }
            }

            $response = array(
                'time'                  => $todayTime,
                'orderNum'              => $orderNum,
                'orderCompleteNum'      => $orderCompleteNum,
                'orderAccidentNum'      => $orderAccidentNum,
                'orderCount'            => $orderCount
            );

        }elseif($type == 2){
            //获取本周日期
            $sdefaultDate = date("Y-m-d");
            $first = 1;
            $w = date('w', strtotime($sdefaultDate));

            $day = array();
            $weekTime = array(
                '周一',
                '周二',
                '周三',
                '周四',
                '周五',
                '周六',
                '周日',
            );
            $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));

            $day[] = explode('-', $week_start)[2];

            for ($i = 1; $i < 7; $i++) {
                $weektemp = date('Y-m-d', strtotime("$week_start + $i days"));
                $day[] = explode('-', $weektemp)[2];
            }

            $count = $this->_model->orderCountWeek($this->storeId, $year, $month, implode(',', $day));

            $orderNum = 0;
            $orderCompleteNum = 0;
            $orderAccidentNum = 0;

            $orderCount = array();

            for ($i = 0; $i < count($weekTime); $i++) {
                $orderCount[$i] = 0;
                foreach ($count as $c) {
                    if ($c->day == $day[$i]) {
                        $orderCount[$i] += $c->num;
                        $orderNum += $c->num;
                        if($c->status == Config::get('orderstatus.completd')['status']){
                            $orderCompleteNum += $c->num;
                        }elseif($c->status == Config::get('orderstatus.cancel')['status'] || $c->status = Config::get('orderstatus.refunding')['status'] || $c->status = Config::get('orderstatus.refunded')['status']){
                            $orderAccidentNum += $c->num;
                        }
                    }
                }
            }

            $response = array(
                'time'                  => $weekTime,
                'orderNum'              => $orderNum,
                'orderCompleteNum'      => $orderCompleteNum,
                'orderAccidentNum'      => $orderAccidentNum,
                'orderCount'            => $orderCount
            );

        }elseif($type == 3) {
            $dayTimes = date('j', mktime(0, 0, 1, ($month == 12 ? 1 : $month + 1), 1, ($month == 12 ? $year + 1 : $year)) - 24 * 3600);

            $count = $this->_model->orderCountMonth($this->storeId, $year, $month);

            $orderNum = 0;
            $orderCompleteNum = 0;
            $orderAccidentNum = 0;

            $orderCount = array();

            for ($i = 1,$j = 0; $i <= $dayTimes , $j <= $dayTimes; $i++ , $j++) {
                $monthTime[] = $i;
                $orderCount[$j] = 0;
                foreach ($count as $c) {
                    if ($c->day == $i) {
                        $orderCount[$j] += $c->num;
                        $orderNum += $c->num;
                        if ($c->status == Config::get('orderstatus.completd')['status']) {
                            $orderCompleteNum += $c->num;
                        } elseif ($c->status == Config::get('orderstatus.cancel')['status'] || $c->status = Config::get('orderstatus.refunding')['status'] || $c->status = Config::get('orderstatus.refunded')['status']) {
                            $orderAccidentNum += $c->num;
                        }
                    }
                }
            }

            $response = array(
                'time' => $monthTime,
                'orderNum' => $orderNum,
                'orderCompleteNum' => $orderCompleteNum,
                'orderAccidentNum' => $orderAccidentNum,
                'orderCount' => $orderCount
            );
        }
        return response()->json(Message::setResponseInfo('SUCCESS' , $response));
    }

}