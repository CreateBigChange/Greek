<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Alipay;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\CountValidator\Exception;
use Validator , Input;
use Session , Cookie , Config , Log;

use App\Http\Controllers\ApiController;

use App\Models\Order;
use App\Models\StoreInfo;


use App\Libs\Message;
use App\Libs\BLogger;

use Omnipay\Omnipay;

use App\Jobs\Jpush;

use App\Libs\Alipay\AlipayNotify;

class AlipayController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Order;
        $this->_length		= 20;

    }

    /**
     * @api {POST} /sigma/alipay/order/{orderId} 确认订单-支付宝(生成支付宝订单)
     * @apiName ordersAlipayOrder
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 确认订单-微信
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/alipay/order/1
     *
     * @apiParam {int} out_points 使用积分
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/alipay/order/1
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function aliPay($orderId , Request $request)
    {

        $userId = $this->userId;

//        if (!$request->has('out_points')) {
//            $outPoints = 0;
//        } else {
//            $outPoints = $request->get('out_points');
//        }

        //更新订单
        $payNum = $this->_model->confirmOrder($userId, $orderId, 2);

        if ($payNum['code'] != 0000) {
            return $payNum;
        }

        $info = $this->_model->getOrderList(array('user' => $this->userId , 'id' => $orderId), 1, 0);

        if (count($info) == 0) {
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        $body = $info[0]->sname;
        $detail = $info[0]->sname . $info[0]->order_num;
        foreach ($info[0]->goods as $g) {
            $detail .= $g->name . '/';
        }


        $gateway = Omnipay::create('Alipay_MobileExpress');
        $gateway->setPartner(Config::get('alipay.partner'));
        $gateway->setKey(Config::get('alipay.key'));
        $gateway->setSellerEmail(Config::get('alipay.seller_user_id'));
        $gateway->setNotifyUrl(Config::get('alipay.notify_pay_url'));

        //For 'Alipay_MobileExpress', 'Alipay_WapExpress'
        $gateway->setPrivateKey(realpath(Config::get('alipay.private_key_path')));

        $options = [
            'out_trade_no'      => date('YmdHis') . mt_rand(1000, 9999),
            'subject'           => $detail,
            //'total_fee'         => '0.01',
            'body'              => $body,
            'total_fee'         => $payNum['data']
        ];

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($options);

        $response = $gateway->purchase($options)->send();

        if ($response->isSuccessful()) {
            $aliOrderString = $response->getOrderString();
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($aliOrderString);

        if($aliOrderString) {
            if ($this->_model->updateOrderOutTradeNo($orderId, $options['out_trade_no'])) {
                return response()->json(Message::setResponseInfo('SUCCESS', $aliOrderString));
            } else {
                return response()->json(Message::setResponseInfo('FAILED'));
            }
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }


    public function notify(){
        $gateway = Omnipay::create('Alipay_MobileExpress');
        $gateway->setPartner(Config::get('alipay.partner'));
        $gateway->setKey(Config::get('alipay.key'));
        $gateway->setSellerEmail(Config::get('alipay.seller_user_id'));

        //For 'Alipay_MobileExpress', 'Alipay_WapExpress'
        $gateway->setAlipayPublicKey(realpath(Config::get('alipay.ali_public_key_path')));
        
        $outTradeNo = $_POST['out_trade_no'];
        $order = $this->_model->getOrderByOutTradeNo($outTradeNo);
        if(!$order){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode('Order not exist.'));
            die('Order not exist.');
        }

        //已经支付了
        if($order->pay_time){
            die("success");
        }

        $options = [
            'request_params'=> array_merge($_POST, $_GET),
        ];

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($options);

        try {
            $response = $gateway->completePurchase($options)->send();
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($response->isPaid());

        }catch (Exception $e){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($e);
        }

        if ($response->isPaid()) {
//        if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){

            //更新支付时间和订单状态
            $this->_model->pay($order->id , $_POST['total_fee']  , 2 , $_POST['gmt_payment'] , '' , $options['request_params']['trade_no']);

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

            die("success");

        } else {

            die('fail');
        }
    }


    public function refundNotify(){
        $AlipayNotify = new AlipayNotify(Config::get('alipay'));

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($_POST);

        if($AlipayNotify->verifyNotify()){
            $trade_no = explode('^' , $_POST['result_details'])[0];

            $orderInfo = $this->_model->getOrderByTradeNo($trade_no);
            if(!$orderInfo){
                die('success');
            }

            if ($_POST['success_num'] && $this->_model->refund($orderInfo->store_id, $orderInfo->id, $_POST['batch_no'])) {
                //消息推送队列
                $this->dispatch(new Jpush(
                    "你有一个退款成功的订单",
                    "急所需",
                    array('ios', 'android'),
                    "{$orderInfo[0]->user}",
                    array(),
                    "default",
                    "refunded_success",
                    "user"
                ));
                die('success');
            } else {
                die('fail');
            }
        }
    }

}