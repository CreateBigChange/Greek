<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/16
 * Time: 下午6:06
 */

namespace App\Http\Controllers\Alpha;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AdminController;
use App\Models\Order;
use App\Models\User;
use Session , Cookie , Config;

class IndexController extends AdminController
{
    protected $order;
    protected $user;
    public function __construct(){
        parent::__construct();
        $this->response['title']		= '急所需平台管理系统';
        $this->response['menuactive']	= 'index';
        $this->user= new User;
        $this->Order= new Order;
    }

    public function index() {
        $year =  Date('Y');
        $month=  Date('m');
        $day =  Date('d');

        $this->response['orderNum']=$this->Order->getOrderTotle($year,$month,$day);
        $this->response['userNum']=$this->user->getUserNum();
        $search['status']=array(1,2,3,4,5);
        $this->response['orderTotle'] =$this->Order->getOrderTotalNum($search);
        $this->response['profit'] = $this->Order->getOrderTotalMony($search);

        return view('alpha.index.index' , $this->response);
    }

}
