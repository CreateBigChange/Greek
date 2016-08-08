<?php
/**
 * Created by PhpStorm.
 * User: DEL
 * Date: 2016/8/8
 * Time: 9:54
 */

namespace App\Http\Controllers\Alpha;
use App\Models\Franchisee;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
class FranchiseeController extends AdminController
{
    private $length;
    private $_model;
    public function __construct(){
        parent::__construct();
        $this->_model = new Franchisee();

        $this->response['title']		= '活动';
        $this->length=10;
    }
    public function  getFranchiseeList(Request $request){
        $page = 1;

        if($request->has("page"))
            $page = $request->get("page");

        $search=array();

        $param="";

        $totalNum                       = $this->_model->getFranchiseeListTotle();
        $pageData                       = $this->getPageData($page  , $this->length, $totalNum);
        $this->response['page']         = $pageData->page;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/franchisee/list?' . $param);
        $this->response['lists']        = $this->_model->getFranchiseeList( $this->length , $pageData->offset , $search);
        return view('alpha.Franchisee.list' , $this->response);
    }

}