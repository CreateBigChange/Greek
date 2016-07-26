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
use Validator , Input;
use Session , Cookie , Config;

use App\Http\Controllers\AdminController;

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
                $search['status'] = array(
                    $status['cancel']['status'] ,
                    $status['refunding']['status'] ,
                    $status['refunded']['status'] ,
                    $status['arrive']['status'],
                    $status['on_the_way']['status'],
                    $status['paid']['status'],
                    $status['completd']['status'],
                    $status['withdrawMoney']['status']
                );
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

        /**
         * 如果登录的用户是代理商
         */
        if($this->userInfo->is_agent){
            $search['agent_id'] = $this->userInfo->id;
        }

        $orderNum   = $this->_model->getOrderTotalNum($search);
        $orderMoney = $this->_model->getOrderTotalMony($search);
      
        $pageData = $this->getPageData($page  , $this->length, $orderNum);
       
        $this->response['totalMoney'] = $orderMoney;
        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/order/list?' . $param);
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['status']       = $status;
        
       // dump($this->response);
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
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/delivery?' );
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['status']       = $status;
        return view('alpha.order.list' , $this->response);
    }

}