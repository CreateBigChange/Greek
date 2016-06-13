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
use Session , Cookie , Config;

//use App\Libs\Jpush;
use App\Jobs\Jpush;

use App\Http\Controllers\ApiController;

use App\Libs\Message;

class ToolController extends ApiController
{
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_length		= 20;
    }

    /**
     * @api {POST} /gamma/store/orders/{type}[?page=1] 获取订单列表
     * @apiName orders
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取订单列表
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/orders/1?page=1
     *
     * @apiParam {number} type 订单类型 1获取新订单 2获取配送中的订单 3获取完成的订单 4获取意外订单
     * @apiParam {string} search 搜索条件
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/orders/1?page=1
     *      {
     *          search : 18401586654
     *      }
     * @apiUse CODE_200
     *
     */
    public function push(){

        //消息推送队列
        $this->dispatch(new Jpush(
            "急所需有新订单啦,请及时处理",
            "急所需新订单",
            array('ios' , 'android'),
            "21",
            array(),
            "default"
        ));

//        $jpush = new Jpush();
//        return $jpush->push('急所需新订单' , '急所需新订单来了' , array('ios' , 'android') , '21');
    }

}