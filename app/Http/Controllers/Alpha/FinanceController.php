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

    /*
    *提现拒绝
    */
    public function withdrawReject(Request $request)
    {

        $cashModel = new StoreWithdrawCashLog;
        $id=$request->get('id');
        $reason = $request->get('reason');
        $affected= $cashModel->withdrawRject($id,$reason);
        if($affected==1)
            {
                
            }
        else
            {

            }
    
    }
    
    public function withdrawAgree(Request $request)
    {
       var_dump($request);
    }
}
