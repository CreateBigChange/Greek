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
        if(isset($_GET['status'])){
            $type = $_GET['status'];
            $param .= 'status=' . $_GET['status'] . '&';
           
        }

        $status = Config::get('orderstatus');

        if ($type == 1){
            $search['status'] = array($status['paid']['status']);
        }elseif ($type == 2){
            $search['status'] = array($status['on_the_way']['status']);
        }elseif ($type == 3){
            $search['status'] = array($status['completd']['status']);
        }elseif ($type == 4){
            $search['status'] = array($status['cancel']['status'] , $status['refunding']['status'] , $status['refunded']['status']);
        }

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

        $orderNum   = $this->_model->getOrderTotalNum($search);

        $pageData = $this->getPageData($page  , $this->length, $orderNum);


        $this->response['page']         = $pageData->page;
        $this->response['pageData']     = $pageData;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/order/list?' . $param);
        $this->response['orders']       = $this->_model->getOrderList($search , $this->length , $pageData->offset);
        $this->response['status']       = $status;
        
        //dump($this->response);
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

        $search['status'] = array($status['accepted']['status'] , $status['paid']['status']);

        if($request->has('search')){
            $search['search'] = trim($request->get('search'));
        }

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

}