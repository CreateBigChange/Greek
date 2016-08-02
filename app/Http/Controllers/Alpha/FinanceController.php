<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/30
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Alpha;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AdminController;

use App\Models\User;
use App\Models\StoreWithdrawCashLog;
use App\Libs\Message;
use App\Models\StoreInfo;
use App\Models\StoreConfig;

class FinanceController extends AdminController
{

    private $length;
    private $_model;

    public function __construct(){
        parent::__construct();
        $this->_model = new User();
        $this->response['title']		= '财务管理';
        $this->length = 10;
    }

    /**
     * @api {POST} /gamma/store/cash/log 提现记录
     * @apiName cashLog
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/cash/log
     *
     * @apiParamExample {json} Request Example
     * POST /gamma/store/cash/log
     * {
     * }
     * @apiUse CODE_200
     *
     */
    public function getWithdrawCashLog(Request $request){

        $page = 1;

        if($request->has('page')){
            $page = $request->get('page');
        }

        $cashModel = new StoreWithdrawCashLog;

        //获取相关的列表信息

        $totalNum = $cashModel->withdrawCashLogTotalNum();



        $pageData = $this->getPageData($page , $this->length , $totalNum);

        $this->response['pageHtml'] = $this->getPageHtml($page , $pageData->totalPage , '/alpha/finance/cash?');


        $this->response['log'] = $cashModel->getWithdrawCashLog(array('status' => 1) , $this->length , $pageData->offset);

        //$this->response['storeData'] = $cashModel ->getWithdrawCashLogByStoreId($page , $this->length , $totalNum);
       // dump($this->response);
        return view('alpha.finance.cash' , $this->response);
    
    }

    /**
     * @param Request $request
     * 提现拒绝
     */
    public function withdrawReject(Request $request)
    {

        $id = $request->get("id");
        $data = array(
            "reason"=>$request->get("reason"),
            'status' => 3
        );

        $cashModel = new StoreWithdrawCashLog;

        $affected= $cashModel->updateWithdraw($id,$data);

        return redirect('/alpha/finance/cash');
    }

    /**
     * @param Request $request
     *  提现申请
     */
    public function withdrawAgree($id)
    {

        $cashModel = new StoreWithdrawCashLog;
        $id=$id;
        $data = array("status"=>2);
        $cashModel->updateWithdraw($id,$data );
        return redirect('/alpha/finance/cash');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 获取提现申请后的记录列表
     */
    public function getCheckedWithdrawCashLog(Request $request){
        $page = 1;

        if($request->has('page')){
            $page = $request->get('page');
        }

        $cashModel = new StoreWithdrawCashLog;

        //获取相关的列表信息
        $search=array('status'=>2);
        $totalNum = $cashModel->withdrawCashLogTotalNum($search);



        $pageData = $this->getPageData($page , $this->length , $totalNum);

        $this->response['pageHtml'] = $this->getPageHtml($page , $pageData->totalPage , '/alpha/finance/cash/checked?');


        $this->response['log'] = $cashModel->getWithdrawCashLog(array('status' => 2) , $this->length , $pageData->offset);


        return view('alpha.finance.checked_withDraw' , $this->response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 提现状态的改变
     */
    public function finish_withdraw(Request $request){



        $cashModel = new StoreWithdrawCashLog;
        $StoreInfoModel = new StoreInfo;
        $StoreConfigs = new StoreConfig ;

        $with_draw_id = $request->get("with_draw_id");
        $with_draw_data=array(
                                "pay_time"=>date("Y-m-d H:m:s"),
                                 "status"=>'0'
                                    );


        $store_id = $request->get("store_id");

        $configs = $StoreConfigs->getStoreConfigs($store_id );

        $balance= $configs->balance-$request->get("withdraw_cash_num");

        $cashModel->updateWithdraw($with_draw_id, $with_draw_data);


        $StoreConfigs->updateBalance($store_id , $balance);


         return redirect('/alpha/finance/checked');
    }
    public function  getCashFinishList(Request $request){
        $page = 1;

        if($request->has('page')){
            $page = $request->get('page');
        }

        $cashModel = new StoreWithdrawCashLog;

        //获取相关的列表信息
        $search=array('status'=>0);
        $totalNum = $cashModel->withdrawCashLogTotalNum($search);



        $pageData = $this->getPageData($page , $this->length , $totalNum);

        $this->response['pageHtml'] = $this->getPageHtml($page , $pageData->totalPage , '/alpha/finance/cash/checked?');


        $this->response['log'] = $cashModel->getWithdrawCashLog(array('status' => 0) , $this->length , $pageData->offset);

        return view('alpha.finance.finish_cash_list' , $this->response);
    }
}
