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

use Session , Cookie , Config;

class IndexController extends AdminController
{

    public function __construct(){
        parent::__construct();
        $this->response['title']		= '急所需平台管理系统';
        $this->response['menuactive']	= 'index';
    }

    public function index() {
        return view('alpha.index.index' , $this->response);
    }

}
