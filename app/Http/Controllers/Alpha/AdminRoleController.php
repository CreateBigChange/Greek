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
use App\Models\AdminUser;
use Session , Cookie , Config;

use App\Models\AdminRole;
use App\Models\AdminUserRole;
use App\Models\AdminPermission;

class AdminRoleController extends AdminController
{

    public function __construct(){
        parent::__construct();
        $this->response['title']		= '权限管理';
        $this->response['menuactive']	= 'qxgl';
    }

    /**
     * 获取角色列表
     */
    public function getRoleList(){
        $this->response['title']		= '角色列表';
        $this->response['menuactive']	= 'qxgl';

		$roleModel		= new AdminRole;
        $rolesList      = $roleModel->getRoleList();
		
		$permissionModel = new AdminPermission;

		$permissionList = $permissionModel->getPermissionsList();

        $this->response['permissions'] = $permissionList;

        $this->response['roles'] = $rolesList;

        return view('alpha.auth.roles.list' , $this->response);
    }

    /**
     * 获取用户与角色直接的关系
     */
    public function ajaxUserRole($userId){

        $rolesList      = AdminRole::all();

		$userRoles		= AdminUserRole::where('admin_user_id' , $userId)->get();

		foreach($rolesList as $r){
			$r->is_user = 0;
			foreach($userRoles as $u){
				if($r->id == $u->role_id){
					$r->is_user = 1;
				}
			}
		}

		$this->response['code'] = '0000';
        $this->response['roles'] = $rolesList;

        return $this->response;
    }

    /**
     * ajax角色列表
     */
    public function ajaxRoleList(){

        $rolesList      = AdminRole::all();

		$this->response['code'] = '0000';
        $this->response['roles'] = $rolesList;

        return $this->response;
    }

    /**
     * 添加角色
     */
    public function addRole(Request $request){
        $this->response['title']		= '添加角色';
        $this->response['menuactive']	= 'qxgl';

        if(!$request->has('name')){
            return view('errors.503');
        }
        if(!$request->has('description')){
            return view('errors.503');
        }

        $role                 = new AdminRole;
        $role->name           = $request->get('name');
        $role->description    = $request->get('description');

        if($role->save()){
            return redirect('alpha/roles');
        }
    }

    /**
     * 删除角色
     */
    public function delRole($id){

        AdminRole::where('id' , $id)->delete();
        return redirect('alpha/roles');

    }

    /**
     * 获取角色信息
     */
    public function getRoleInfo($id){

        $info = AdminRole::find($id);

		return response()->json($info);

    }

    /**
     * 更新角色
     */
    public function updateRole(Request $request){
		if(!$request->has('id')){
            return view('errors.503');
		}
        $this->response['title']		= '更新角色';
        $this->response['menuactive']	= 'qxgl';

		$id = $request->get('id');

        $update = array();
        if($request->has('name')){
            $update['name'] = $request->get('name');
        }
        if($request->has('description')){
            $update['description'] = $request->get('description');
        }

        AdminRole::where('id' , $id)->update($update);
        return redirect('alpha/roles');
    }

	
    /**
     * 获取节点列表
     */
    public function getPermissionsList(){
        $this->response['title']		= '角色列表';
        $this->response['menuactive']	= 'qxgl';

		$permissionModel = new AdminPermission;

		$permissionList = $permissionModel->getPermissionsList();

        $this->response['permissions'] = $permissionList;

        return view('alpha.auth.permissions.list' , $this->response);
    }
    public function  updateRolePassword(Request $request){

        $this->response['title']		= '密码修改';
        $this->response['menuactive']	= 'qxgl';

        return view('alpha.auth.roles.update_password', $this->response);
    }
    public function  verifyRolePassword(Request $request){




        $password           =$request->get("password");




        $salt               = $this->userInfo->salt;


        $encrypt_password   = $this->encrypt($password , $salt);

        $userPassword       =$this->userInfo->password;

        if($userPassword==$encrypt_password)
        {
            $code ="0";
            $msg="密码正确";
        }
        else{
            $code ="1";
            $msg="密码错误";
        }
        $info =array(
            "code"=>$code,
            "msg"=>$msg
        );

        return response()->json($info);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function  passwordChange(Request $request){

        $password        =$request->get("newPassword");
        $confirePassword =$request->get("confirePassword");


        if(($password ==$confirePassword)&&strlen($password)>=6){

            $salt               = $this->getSalt(8);
            $realpassword		    = $this->encrypt($password  , $salt);

            $data['salt']       =$salt;
            $data['password']	=$realpassword;
            $id                 = $this->userInfo->id;
            $AdminUser          = new AdminUser;



            $result =$AdminUser->editAdminUserInfo( $id , $data );
            if($result==1){
                $this->userInfo->salt     =  $salt ;
                $this->userInfo->password = $realpassword;
            }


        }

        return redirect("/alpha/role/password");
    }
}
