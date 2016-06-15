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

    public function aliPay($orderId , Request $request){
        $gateway = Omnipay::create('Alipay_MobileExpress');
        $gateway->setPartner('2088121058783821');
        $gateway->setKey('2016060201471049');
        $gateway->setSellerEmail('zxhy201510@163.com');
        $gateway->setReturnUrl('http://preview.jisxu.com/sigma/alipay/return');
        $gateway->setNotifyUrl('http://preview.jisxu.com/sigma/alipay/notify');

        //For 'Alipay_MobileExpress', 'Alipay_WapExpress'
        $gateway->setPrivateKey('./alipay/rsa_private_key.pem');



        $options = [
            'out_trade_no' => date('YmdHis') . mt_rand(1000,9999),
            'subject' => 'Alipay Test',
            'total_fee' => '0.01',
        ];



        $response = $gateway->purchase($options)->send();




        //For 'Alipay_MobileExpress'
        //Use the order string with iOS or Android SDK
        return $response->getOrderString();

    }


}