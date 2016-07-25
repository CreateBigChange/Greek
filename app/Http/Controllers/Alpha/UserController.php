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
use App\Libs\Message;

class UserController extends AdminController
{

    private $length;
    private $_model;

    public function __construct(){
        parent::__construct();
        $this->_model = new User();
        $this->response['title']		= '用户管理';
        $this->length = 10;
    }

    public function getUserList(Request $request){

        $search = array();
        $page = 1;
        if($request->has('page'))
            $page = $request->get('page');


        $totleNum = $this->_model->getUserNum();

        $pageData = $this->getPageData($page  , $this->length, $totleNum);

        $this->response['userList'] = $this->_model->getUserList($search,$this->length,$pageData->offset);

        $this->response['pageHtml'] = $this->getPageHtml($page , $pageData->totalPage , '/alpha/user/list?');

        //dump($this->response);
        return view('alpha.user.list',$this->response);

    }

}
