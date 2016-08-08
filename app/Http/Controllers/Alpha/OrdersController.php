<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Alpha;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Validator, Input, File;
use Session, Cookie, Config;


use App\Http\Controllers\AdminController;

use App\Models\MyExcel;
use App\Models\Order;
use App\Libs\Message;
use App\Models\FileUpload;

class OrdersController extends AdminController
{
    private $_model;
    private $length;

    public function __construct()
    {
        parent::__construct();
        $this->_model = new Order;
        $this->response['title'] = '订单管理';
        $this->length = 10;
    }

    public function getOrderList(Request $request)
    {

        $search = array();

        $param = '';

        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        $status = Config::get('orderstatus');

        $search['status'] = array($status['cancel']['status'], $status['refunding']['status'], $status['refunded']['status'], $status['arrive']['status'], $status['on_the_way']['status'], $status['paid']['status'], $status['completd']['status'], $status['withdrawMoney']['status']);

        if ($request->has('consignee')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $param .= '&type=' . $_GET['type'];
        }

        /**
         * 如果登录的用户是代理商
         */
        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }

        $orderNum = $this->_model->getOrderTotalNum($search);
        $orderMoney = $this->_model->getOrderTotalMony($search);
        $pageData = $this->getPageData($page, $this->length, $orderNum);

        $this->response['totalMoney'] = $orderMoney;
        $this->response['page'] = $pageData->page;
        $this->response['pageData'] = $pageData;
        $this->response['pageHtml'] = $this->getPageHtml($pageData->page, $pageData->totalPage, '/alpha/order/list?' . $param);
        $this->response['orders'] = $this->_model->getOrderList($search, $this->length, $pageData->offset);
        $this->response['search'] = $search;
        $this->response['status'] = $status;
        $this->response["url"] = "list";

        return view('alpha.order.list', $this->response);
    }

    public function getOrderNotDelivery(Request $request)
    {
        $search = array();
        $param = '';

        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        if ($request->has('consignee')) {
            $param .= '&consignee=' . $_GET['consignee'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {
            $param .= '&order_num=' . $_GET['order_num'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {
            $param .= '&consignee_num=' . $_GET['consignee_num'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $param .= '&type=' . $_GET['type'];
        }
        $status = Config::get('orderstatus');

        $search['status'] = array($status['paid']['status']);

        if ($request->has('search')) {
            $search['search'] = trim($request->get('search'));
        }

        /**
         * 如果登录的用户是代理商
         */
        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page, $this->length, $orderNum);

        $this->response['page'] = $pageData->page;
        $this->response['pageData'] = $pageData;
        $this->response['pageHtml'] = $this->getPageHtml($pageData->page, $pageData->totalPage, '/alpha/order/notdelivery?' . $param);
        $this->response['orders'] = $this->_model->getOrderList($search, $this->length, $pageData->offset);
        $this->response['search'] = $search;
        $this->response['status'] = $status;
        $this->response["url"] = "notdelivery";
        return view('alpha.order.list', $this->response);
    }

    public function getOrderDelivery(Request $request)
    {
        $search = array();
        $param = '';
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        if ($request->has('consignee')) {
            $param .= '&consignee=' . $_GET['consignee'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {
            $param .= '&order_num=' . $_GET['order_num'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {
            $param .= '&consignee_num=' . $_GET['consignee_num'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $param .= '&type=' . $_GET['type'];
        }
        $status = Config::get('orderstatus');

        $search['status'] = array($status['on_the_way']['status'], $status['arrive']['status'], $status['completd']['status']);

        if ($request->has('search')) {
            $search['search'] = trim($request->get('search'));
        }

        /**
         * 如果登录的用户是代理商
         */
        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page, $this->length, $orderNum);

        $this->response['page'] = $pageData->page;
        $this->response['pageData'] = $pageData;
        $this->response['pageHtml'] = $this->getPageHtml($pageData->page, $pageData->totalPage, '/alpha/order/delivery?' . $param);
        $this->response['orders'] = $this->_model->getOrderList($search, $this->length, $pageData->offset);
        $this->response['search'] = $search;
        $this->response['status'] = $status;
        $this->response['url'] = "delivery";
        return view('alpha.order.list', $this->response);
    }

    public function getOrderAccident(Request $request)
    {
        $search = array();
        $param = '';
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        if ($request->has('consignee')) {
            $param .= '&consignee=' . $_GET['consignee'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {
            $param .= '&order_num=' . $_GET['order_num'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {
            $param .= '&consignee_num=' . $_GET['consignee_num'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $param .= '&type=' . $_GET['type'];
        }
        $status = Config::get('orderstatus');

        $search['status'] = array($status['cancel']['status'], $status['refunding']['status'], $status['refunded']['status']);

        if ($request->has('search')) {
            $search['search'] = trim($request->get('search'));
        }

        /**
         * 如果登录的用户是代理商
         */
        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }


        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page, $this->length, $orderNum);

        $this->response['page'] = $pageData->page;
        $this->response['pageData'] = $pageData;
        $this->response['pageHtml'] = $this->getPageHtml($pageData->page, $pageData->totalPage, '/alpha/order/accident?' . $param);
        $this->response['orders'] = $this->_model->getOrderList($search, $this->length, $pageData->offset);
        $this->response['search'] = $search;
        $this->response['status'] = $status;
        $this->response["url"] = "accident";
        return view('alpha.order.list', $this->response);
    }

    public function changeStatus($id, Request $request)
    {

        $validation = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $status = $request->get('status');

        if ($this->_model->changeStatus($this->userInfo->id, $id, $status)) {
            return response()->json(Message::setResponseInfo('SUCCESS'));
        } else {
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /*
    *   得到配送中的订单
    */
    public function getOrderDispatching(Request $request)
    {

        $search = array();
        $param = '';
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        if ($request->has('consignee')) {
            $param .= '&consignee=' . $_GET['consignee'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {
            $param .= '&order_num=' . $_GET['order_num'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {
            $param .= '&consignee_num=' . $_GET['consignee_num'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $param .= '&type=' . $_GET['type'];
        }
        $status = Config::get('orderstatus');

        $search['status'] = array($status['on_the_way']['status']);

        if ($request->has('search')) {
            $search['search'] = trim($request->get('search'));
        }

        /**
         * 如果登录的用户是代理商
         */
        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }


        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page, $this->length, $orderNum);

        $this->response['page'] = $pageData->page;
        $this->response['pageData'] = $pageData;
        $this->response['pageHtml'] = $this->getPageHtml($pageData->page, $pageData->totalPage, '/alpha/order/dispatching?' . $param);
        $this->response['orders'] = $this->_model->getOrderList($search, $this->length, $pageData->offset);
        $this->response['status'] = $status;
        $this->response['search'] = $search;
        $this->response["url"] = "dispatching";
        return view('alpha.order.list', $this->response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 得到结算后的订单
     */

    public function getOrderBalence(Request $request)
    {

        $search = array();
        $param = '';
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        if ($request->has('consignee')) {
            $param .= '&consignee=' . $_GET['consignee'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {
            $param .= '&order_num=' . $_GET['order_num'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {
            $param .= '&consignee_num=' . $_GET['consignee_num'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $param .= '&type=' . $_GET['type'];
        }
        $status = Config::get('orderstatus');
        $search['status'] = array($status['withdrawMoney']['status']);

        /**
         * 如果登录的用户是代理商
         */
        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum = $this->_model->getOrderTotalNum($search);
        $pageData = $this->getPageData($page, $this->length, $orderNum);

        $this->response['page'] = $pageData->page;
        $this->response['pageData'] = $pageData;
        $this->response['pageHtml'] = $this->getPageHtml($pageData->page, $pageData->totalPage, '/alpha/order/balance?' . $param);
        $this->response['orders'] = $this->_model->getOrderList($search, $this->length, $pageData->offset);
        $this->response['status'] = $status;
        $this->response['search'] = $search;
        $this->response["url"] = "balance";
        return view('alpha.order.list', $this->response);
    }

    public function getOrderCompletd(Request $request)
    {

        $search = array();
        $param = '';
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        if ($request->has('consignee')) {
            $param .= '&consignee=' . $_GET['consignee'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {
            $param .= '&order_num=' . $_GET['order_num'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {
            $param .= '&consignee_num=' . $_GET['consignee_num'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {
            $param .= '&store_name=' . $_GET['store_name'];
            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $param .= '&type=' . $_GET['type'];
        }
        $status = Config::get('orderstatus');
        $search['status'] = array($status['completd']['status']);

        /**
         * 如果登录的用户是代理商
         */
        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum = $this->_model->getOrderTotalNum($search);
        $pageData = $this->getPageData($page, $this->length, $orderNum);

        $this->response['page'] = $pageData->page;
        $this->response['pageData'] = $pageData;
        $this->response['pageHtml'] = $this->getPageHtml($pageData->page, $pageData->totalPage, '/alpha/order/completd?' . $param);
        $this->response['orders'] = $this->_model->getOrderList($search, $this->length, $pageData->offset);
        $this->response['status'] = $status;
        $this->response['search'] = $search;
        $this->response["url"] = "completd";
        return view('alpha.order.list', $this->response);
    }



    /**
     * @param Request $request
     *
     */
    //Excel文件导出功能
    public function getOrderExport(Request $request)
    {

        $status = Config::get('orderstatus');

        if ($request->has('consignee')) {

            $search['consignee'] = trim($request->get('consignee'));
        }
        if ($request->has('order_num')) {

            $search['order_num'] = trim($request->get('order_num'));
        }
        if ($request->has('consignee_num')) {

            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if ($request->has('store_name')) {

            $search['store_name'] = trim($request->get('store_name'));
        }
        if ($request->has('type')) {
            $type = $request->get("type");
            switch ($type) {
                case "list":
                    $search['status'] = array($status['cancel']['status'], $status['refunding']['status'], $status['refunded']['status'], $status['arrive']['status'], $status['on_the_way']['status'], $status['paid']['status'], $status['completd']['status'], $status['withdrawMoney']['status']);
                    break;
                case "completd":
                    $search['status'] = array($status['completd']['status']);
                    break;
                case "accident":
                    $search['status'] = array($status['cancel']['status'], $status['refunding']['status'], $status['refunded']['status']);
                    break;
                case "delivery":
                    $search['status'] = array($status['on_the_way']['status'], $status['arrive']['status'], $status['completd']['status']);
                    break;
                case "notdelivery":
                    $search['status'] = array($status['paid']['status']);
                    break;
                case "dispatching":
                    $search['status'] = array($status['on_the_way']['status']);
                    break;
                case "balance":
                    $search['status'] = array($status['withdrawMoney']['status']);
                    break;
            }
        }


        if ($this->userInfo->is_agent) {
            $search['agent_id'] = $this->userInfo->id;
        }

        $excelModel = new MyExcel;
        $name = "订单";
        $orderNum = $this->_model->getOrderTotalNum($search);
        $offset = 0;
        $data = $this->_model->getOrderList($search, $orderNum, $offset);


        $cellData = array();


        /**
        +"id": 47
        +"order_num": "14682865878829"
        +"total": 57.22
        +"deliver": 1.0
        +"status": 14
        +"store_id": 24
        +"user": 5
        +"consignee": "卡卡"
        +"consignee_id": 3
        +"consignee_tel": "13728389359"
        +"province": "湖南省"
        +"city": "长沙市"
        +"county": "岳麓区"
        +"street": "绿地中央广场(银杉路)"
        +"consignee_address": "5栋904"
        +"remark": null
        +"refund_reason": "质量问题"
        +"created_at": "2016-07-12 09:23:07"
        +"updated_at": "2016-07-12 10:04:54"
        +"is_evaluate": 0
        +"pay_total": 58.22
        +"out_trade_no": "14682865924776089723"
        +"pay_type_id": 1
        +"pay_type_name": "微信支付"
        +"trade_no": ""
        +"transaction_id": "4009932001201607128805976191"
        +"nick_name": "原地狂奔的骚年"
        +"true_name": null
        +"mobile": "13728389359"
        +"sname": "正兴宏业测试二店"
        +"agent_id": 24
        +"smobile": "18401586654"
        +"slogo": "http://7xt4zt.com2.z0.glb.clouddn.com/1467973273.jpg"
         *
         */
        for ($i = 0; $i < count($data); $i++) {
            $cellData[$i]['id']=$data[$i]->id;
            $cellData[$i]['order_num']=$data[$i]->order_num;
            $cellData[$i]['total']=$data[$i]->total;
            $cellData[$i]['deliver']=$data[$i]->deliver;
            $cellData[$i]['status']=$data[$i]->status;
            $cellData[$i]['store_id']=$data[$i]->store_id;
            $cellData[$i]['user']=$data[$i]->user;
            $cellData[$i]['consignee']=$data[$i]->consignee;
            $cellData[$i]['consignee_id']=$data[$i]->consignee_id;
            $cellData[$i]['consignee_tel']=$data[$i]->consignee_tel;
            $cellData[$i]['province']=$data[$i]->province;
            $cellData[$i]['city']=$data[$i]->city;
            $cellData[$i]['county']=$data[$i]->county;
            $cellData[$i]['street']=$data[$i]->street;
            $cellData[$i]['consignee_address']=$data[$i]->consignee_address;
            $cellData[$i]['refund_reason']=$data[$i]->refund_reason;
            $cellData[$i]['created_at']=$data[$i]->created_at;
            $cellData[$i]['updated_at']=$data[$i]->updated_at;
            $cellData[$i]['is_evaluate']=$data[$i]->is_evaluate;
            $cellData[$i]['pay_total']=$data[$i]->pay_total;
            $cellData[$i]['out_trade_no']=$data[$i]->out_trade_no;
            $cellData[$i]['pay_type_id']=$data[$i]->pay_type_id;
            $cellData[$i]['pay_type_name']=$data[$i]->pay_type_name;
            $cellData[$i]['trade_no']=$data[$i]->trade_no;
            $cellData[$i]['transaction_id']=$data[$i]->transaction_id;
            $cellData[$i]['nick_name']=$data[$i]->nick_name;
            $cellData[$i]['mobile']=$data[$i]->mobile;
            $cellData[$i]['sname']=$data[$i]->sname;
            $cellData[$i]['agent_id']=$data[$i]->agent_id;
            $cellData[$i]['smobile']=$data[$i]->smobile;
            $cellData[$i]['slogo']=$data[$i]->slogo;
        }

        $title = [
            "id", "订单号", "总价", "配送费", "订单状态;", "商店ID", "用户ID", "收货人", "收货地址ID", "收货电话",
            "省", "城市", "县区", "街道", "收货地址",  "退款原因", "创建时间", "更新时间", "是否评价", "支付总价",
            "交易号", "支付类型ID", "支付类型名", "支付订单ID", "微信订单ID", "用户昵称", "用户手机号码", "商店名称",
            "代理ID", "商铺手机号", "商铺log"
        ];
        $excelModel->export($name, $cellData, $title);
    }

    /**
     * 订单的导出
     * @param Request $request
     */

    public function Orderimport(Request $request)
    {

        $file = $request->file('file');

        $FieUpload = new FieUpload;
        $excelModel = new MyExcel;


        $Fielpath = $FieUpload->upload($file);
        $table = "";
        $excelModel->import($Fielpath, $table);

    }
}