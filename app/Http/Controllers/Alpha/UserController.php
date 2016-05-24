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

use App\Models\Alpha\Users;
use App\Libs\Message;

class UserController extends AdminController
{

    private $length;
    private $_model;

    public function __construct(){
        parent::__construct();
        $this->_model = new Users();
        $this->response['title']		= '用户管理';
        $this->length = 10;
    }

    public function getUserList(){
        $this->response['userList'] = $this->_model->getUserList();

        return view('alpha.user.list',$this->response);
    }

}
