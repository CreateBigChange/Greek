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
        for ($i = 0; $i < count($data); $i++) {
            $cellData[$i] = get_object_vars($data[$i]);
            $cellData[$i]['goods'] = "";
        }
        $title = [
            "id",
            "订单号",
            "总价",
            "配送费",
            "订单状态;",
            "商店ID",
            "用户ID",
            "收货人",
            "收货地址ID",
            "收货电话",
            "省",
            "城市",
            "县区",
            "街道",
            "收货地址",
            "备注",
            "退款原因",
            "创建时间",
            "更新时间",
            "是否评价",
            "支付总价",
            "交易号",
            "支付类型ID",
            "支付类型名",
            "支付订单ID",
            "微信订单ID",
            "优惠券类型",
            "优惠券价值",
            "优惠券ID",
            "优惠券实际优惠的价格",
            "优惠券名",
            "优惠券用户ID",
            "优惠券平台",
            "这个订单店铺的收入",
            "该订单扣点扣的钱数",
            "用户点数",
            "用户余额",
            "用户昵称",
            "用户真实姓名",
            "用户手机号码",
            "商店名称",
            "代理ID",
            "商铺手机号",
            "商铺log",
            "该种类型订单总数",
            "该种类型订单总额",
            "商品",
            "商品总数",
            "支付总额"
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