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
use Mockery\CountValidator\Exception;
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

    public function aliPay($orderId , Request $request)
    {

        $userId = $this->userId;

        if (!$request->has('out_points')) {
            $outPoints = 0;
        } else {
            $outPoints = $request->get('out_points');
        }

        //更新订单
        $payNum = $this->_model->confirmOrder($userId, $orderId, 2, $outPoints);

        if ($payNum['code'] != 0000) {
            return $payNum;
        }

        $info = $this->_model->getOrderList($this->userId, array('id' => $orderId), 1, 0);

        if (count($info) == 0) {
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        $body = $info[0]->sname;
        $detail = '';
        foreach ($info[0]->goods as $g) {
            $detail .= $g->name . ' ';
        }


        $gateway = Omnipay::create('Alipay_MobileExpress');
        $gateway->setPartner('2088121058783821');
        $gateway->setKey('2016060201471049');
        $gateway->setSellerEmail('zxhy201510@163.com');
        $gateway->setNotifyUrl('http://preview.jisxu.com/sigma/alipay/notify');

        //For 'Alipay_MobileExpress', 'Alipay_WapExpress'
        $gateway->setPrivateKey(public_path() . '/alipay/rsa_private_key.pem');

        $options = [
            'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
            'subject' => $detail,
            'total_fee' => '0.01',
            'body' => $body,
            //'total_fee'     => (int)($payNum['data'] * 100)
        ];

        $response = $gateway->purchase($options)->send();

        if ($response->isSuccessful()) {
            $aliOrderString = $response->getOrderString();
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($aliOrderString);

        //$wechatPayLogModel = new WechatPayLog();
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
        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(111111111);

        $gateway = Omnipay::create('Alipay_MobileExpress');
        $gateway->setPartner('2088121058783821');
        $gateway->setKey('2016060201471049');
        $gateway->setSellerEmail('zxhy201510@163.com');

        //For 'Alipay_MobileExpress', 'Alipay_WapExpress'
        $gateway->setAlipayPublicKey(public_path().'/alipay/alipay_rsa_public_key.pem');
        
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

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice(json_encode($options));

        try {
            $response = $gateway->completePurchase($options)->send();
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($response->isPaid());

        }catch (Exception $e){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($e);
        }

        //if ($response->isPaid()) {
        if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){

            //更新支付时间和订单状态
            $this->_model->pay($order->id , ($_POST['total_fee'] / 100) , 2 , $_POST['gmt_payment']);

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

            die("success");

        } else {

            die('fail');
        }
    }


}