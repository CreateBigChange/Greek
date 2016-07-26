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
use App\Http\Controllers\AdminController;

use Session , Cookie , Config;

use App\Models\AdminUser;

class AdminUsersController extends AdminController
{
	private $_model;
	private $_length;

    public function __construct(){
        parent::__construct();
		$this->_model = new AdminUser;
		$this->_length		= 20;
        $this->response['title']		= '登录';
        $this->response['menuactive']	= 'dl';
    }

    public function showLoginView() {
        return view('alpha.auth.login' , $this->response);
    }

    /**
     * 登录
     * @param Request $request
     */
    public function login(Request $request) {

        if( !$request->has('account') ) {
            return view('errors.503');
        }
        if( !$request->has('password') ) {
            return view('errors.503');
        }

        $adminUserModel     = new AdminUser;

        $account            = $request->get('account');
        $password           = $request->get('password');

        $salt               = $adminUserModel->getAdminUserSalt($account);
        $encrypt_password   = $this->encrypt($password , $salt->salt);

        $this->userInfo           = $adminUserModel->checkLogin($account , $encrypt_password);

        if($this->userInfo){
			//获取登录用户的权限

			$this->userInfo->permissions = $adminUserModel->getAdminUserPermissions($this->userInfo->id);

            $sessionKey = $this->getSalt(16);
            Session::put($sessionKey , $this->userInfo);

			$this->_writeLogForSql('登录');
            return redirect('alpha/index')-> withCookie(cookie(Config::get('session.web_login_cookie') , $sessionKey , Config::get('session.lifetime')));
        }else{
            return redirect('alpha/login');
        }
    }

	/**
	 * 登出
	 */
	public function logout(Request $request){
	
		$sessionKey = cookie::get(config::get('session.web_login_cookie'));

		session::forget($sessionKey);

		return redirect('alpha/login')->withCookie(cookie(Config::get('session.web_login_cookie') , '' , Config::get('session.lifetime'))) ;

	}


    /**
     * 获取管理员列表
     */
    public function getAdminUserList(Request $request){
        $this->response['title']		= '管理员列表';
        $this->response['menuactive']	= 'qxgl';

		$page			= $request->get('page' , 1);

		$userTotalNum	= $this->_model->getAdminUserTotalNum();

		if( !$userTotalNum ){
            return view('errors.503');
		}

		$pageData		= $this->getPageData( $page , $this->_length , $userTotalNum );

		$userList		= $this->_model->getAdminUserList( $pageData->offset , $this->_length);

		if( empty($userList) ){
            return view('errors.503');
		}

		$this->response['user']	= $userList;
		$this->response['page']	= $pageData;

        return view('alpha.auth.users.list' , $this->response);
    }

	/**
	 * 删除管理用户
	 */
	public function delAdminUser($id){
		AdminUser::where('id' , $id)->update(['is_del' => 1]);
		return redirect('alpha/admin/users');
	}

	/**
	 * 获取管理用户信息
	 */
	public function getAdminUserInfo($id){
		$info = AdminUser::where('id' , $id)->get();
		if(!empty($info)){
			$this->response['info'] = $info[0];
			$this->response['code'] = '0000';
		}else{
			$this->response['code'] = '0001';
		}

		return $this->response;
	}
	
	/*
	 * 添加管理用户
	 */
	public function addAdminUser(Request $request){
		if(!$request->has('real_name')){
            return view('errors.503');
		}	
		if(!$request->has('account')){
            return view('errors.503');
		}
		if(!$request->has('password')){
            return view('errors.503');
		}
		if(!$request->has('email')){
            return view('errors.503');
		}

		if(!$request->has('roles[]')){
			$roles = $request->get('roles');
		}

		$salt = $this->getSalt(8);
		
		$realName		= $request->input('real_name');
		$email			= $request->input('email');
		$account			= $request->input('account');
        $password		= $this->encrypt($request->input('password') , $salt);

		$sqlData	= array(
			'real_name'		=> $realName,
			'account'		=> $account,
			'password'		=> $password,
			'salt'			=> $salt,
			'email'			=> $email,
			'created_at'	=> date('Y-m-d H:i:s' , time()),
			'updated_at'	=> date('Y-m-d H:i:s' , time())
		);

		$userId = $this->_model->addAdminUser($sqlData);

		if($userId && !empty($roles)){
			$this->_model->addAdminUserRole($userId , $roles);
		}

		return redirect('alpha/admin/users');

	}

	/**
	 * 更新管理用户信息
	 */
	public function updateAdminUser(Request $request){
	

		if(!$request->has('id')){
            return view('errors.503');
		}

		$data = array();
		$id	= $request->get('id');
		if( $request->has('account') ){
			$data['account']	= $request->get('account');		
		}

		if( $request->has('real_name') ){
			$data['real_name']	= $request->get('real_name');
		}

		if( $request->has('password') && $request->get('password') ){
            $salt = $this->getSalt(8);
            $password		= $this->encrypt($request->get('password') , $salt);

            $data['salt']=$salt;
			$data['password']	=  $password;
		}

		if( $request->has('email')){
			$data['email']	= $request->get('email');
		}

		if( $request->has('roles') ){
			$this->_model->delAdminUserRole($id);
			$this->_model->addAdminUserRole($id , $request->get('roles'));
		}

		$this->_model->editAdminUserInfo( $id , $data );

		return redirect('/alpha/admin/users');
	}

}
