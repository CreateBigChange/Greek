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
use Validator , Input;
use Session , Cookie , Config;


use App\Http\Controllers\AdminController;

use App\Models\MyExcel;
use App\Models\Order;
use App\Libs\Message;

class OrdersController extends AdminController
{
    private $_model;
    private $length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Order;
        $this->response['title']		= '订单管理';
        $this->length = 10;
    }

    public function getOrderList(Request $request){

        $search = array();

        $param = '';

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $type = 0;

        $status = Config::get('orderstatus');

        switch ($type) {
            case '0':
                $search['status'] = array($status['cancel']['status'] , $status['refunding']['status'] , $status['refunded']['status'] , $status['arrive']['status'], $status['on_the_way']['status'], $status['paid']['status'], $status['completd']['status'], $status['withdrawMoney']['status']);
                break;
             case '1':
                $search['status'] = array($status['paid']['status']);
                break;
            case '2':
               $search['status'] = array($status['on_the_way']['status']);
                break;
            case '3':
                 $search['status'] = array($status['completd']['status']);
                break;
            case '4':
                 $search['status'] = array($status['cancel']['status'] , $status['refunding']['status'] , $status['refunded']['status']);
                break;                                                                          
            default:
                # code...
                break;
        }


        if($request->has('consignee')){
            $param .= '&consignee=' . $_GET['consignee'];
            $search['consignee'] = trim($request->get('consignee'));
        }
        if($request->has('order_num')){
            $param .= '&consignee_tel=' . $_GET['consignee_tel'];
            $search['order_num'] = trim($request->get('order_num'));
        }
        if($request->has('consignee_num')){
            $param .= '&consignee_tel=' . $_GET['consignee_tel'];
            $search['consignee_num'] = trim($request->get('consignee_num'));
        }
        if($request->has('store_name')){
            $param .= '&consignee_tel=' . $_GET['consignee_tel'];
            $search['store_name'] = trim($request->get('store_name'));
        }

        $orderNum   = $this->_model->getOrderTotalNum($search);
        $orderMoney = $this->_model->getOrderTotalMony($search);
      
        $pageData = $this->getPageData($page  , $this->length, $orderNum);
       
        $this->response['totalMoney'] = $orderMoney;
        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/order/list?' . $param);
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['search']       = $search['status'];
        $this->response['status']       = $status;
        

        return view('alpha.order.list' , $this->response);

    }

    public function getOrderNotDelivery(Request $request){

     

        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $status = Config::get('orderstatus');

       

        $search['status'] = array($status['paid']['status']);

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;


        $orderNum   = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page  , $this->length, $orderNum);

        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/goods?' );
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['search']       = $search['status'];
        $this->response['status']       = $status;

        return view('alpha.order.list' , $this->response);
    }

    public function getOrderDelivery(Request $request){
        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $status = Config::get('orderstatus');

        $search['status'] = array($status['on_the_way']['status'] , $status['arrive']['status'] , $status['completd']['status']);

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum   = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page  , $this->length, $orderNum);

        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/goods?' );
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['search']       = $search['status'];
        $this->response['status']       = $status;

        return view('alpha.order.list' , $this->response);

    }

    public function getOrderAccident(Request $request){
        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }
        $status = Config::get('orderstatus');

        $search['status'] = array($status['cancel']['status'] , $status['refunding']['status'] , $status['refunded']['status']);

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }


        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum   = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page  , $this->length, $orderNum);

        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/goods?' );
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['search']       = $search['status'];
        $this->response['status']       = $status;

        return view('alpha.order.list' , $this->response);
    }


    public function changeStatus($id , Request $request){

        $validation = Validator::make($request->all(), [
            'status'          => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $status = $request->get('status');

        if($this->_model->changeStatus($this->userInfo->id , $id , $status)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

/*
*   得到配送中的订单
*/
    public function getOrderDispatching(Request $request)
    {

        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $status = Config::get('orderstatus');

        $search['status'] = array($status['on_the_way']['status']);

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum   = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page , $this->length, $orderNum);

        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/goods?' );
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['status']       = $status;
        $this->response['search']       = $search['status'];
        return view('alpha.order.list' , $this->response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 得到结算后的订单
     */

    public function  getOrderBalence(Request $request){

        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $status = Config::get('orderstatus');
        $search['status'] = array($status['paid']['status']);

        $orderMoney = $this->_model->getOrderTotalMony($search);
        $this->response['totalMoney'] = $orderMoney;

        $orderNum   = $this->_model->getOrderTotalNum($search);
        $pageData = $this->getPageData($page , $this->length, $orderNum);

        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/order/balance?' );
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['status']       = $status;
        $this->response['search']       = $search['status'];

        return view('alpha.order.list' , $this->response);
    }
    /**
     * @param Request $request
     *
     */
    //Excel文件导出功能
    public function getOrderExport(Request $request){

        $discription = $_GET["status"];
        $array=explode('.',$discription);
        $excelModel =new MyExcel;
        $name ="订单";

        $status                         = Config::get('orderstatus');
        $search['status']               = $array;
        $orderNum                       = $this->_model->getOrderTotalNum($search);
        $offset                         =0;
        $data                           = $this->_model->getOrderList($search , $orderNum  , $offset);

        $cellData = array();
        for($i = 0;$i<count($data);$i++)
        {
            $cellData[$i]=get_object_vars($data[$i]);
            $cellData[$i]['goods']="";
        }
        $title=[
            "id",
            "订单数",
            "总价",
            "优惠券ID",
            "配送费",
            "总积分",
            "获得的积分",
            "订单状态;",
            "订单状态改变的log",
            "商店ID",
            "用户ID",
            "支付类型ID",
            "支付类型名",
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
            "退款订单号",
            "创建时间",
            "更新时间",
            "是否评价",
            "交易号",
            "支付时间",
            "支付总价",
            "年",
            "月",
            "日",
            "时",
            "分",
            "秒",
            "微信订单ID",
            "支付订单ID",
            "is_update_user_point",
            "is_update_store_point",
            "is_update_store_money",
            "优惠券类型",
            "优惠券价值",
            "优惠券条件",
            "优惠券平台",
            "优惠券实际优惠的价格",
            "优惠券名",
            "优惠券用户ID",
            "这个订单店铺的输入",
            "该订单扣点扣的钱数"
        ];
        $excelModel->export($name, $cellData,$title );
    }
    public function  Orderimport(){

        $file = Input::file('myfile');
        $file = Request::file('myfile');
        $realPath = $file -> getRealPath();
        return "xx";
    }
}