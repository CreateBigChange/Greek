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

use App\Models\AdminPermissionRole;

class AdminPermissionRoleController extends AdminController
{

    public function __construct(){
        parent::__construct();
        $this->response['title']		= '节点管理';
        $this->response['menuactive']	= 'qxgl';
    }

	/**
	 * 通过角色ID获取权限ID列表
	 */
	public function getPermissionIdsByRoleID($rid){
		return AdminPermissionRole::where('role_id' , $rid)->get();
	}

	/**
	 * 通过角色ID整个权限关系
	 */
	public function getPermissionRoleByID($rid){
		$PermissionRoleModel = new AdminPermissionRole;

		$this->response['permissions'] = $PermissionRoleModel->getPermissionRoleByID($rid);

		return $this->response;
	}

	/**
	 * 添加角色权限
	 */
	public function addPermissionRole(Request $request){
		if(!$request->has('permission_id')){
			return array('code' => '0001');
		}
		if(!$request->has('role_id')){
			return array('code' => '0001');
		}
		$PermissionRoleModel = new AdminPermissionRole;

		$data = array();
		$data['permission_id']	= $request->get("permission_id");
		$data['role_id']		= $request->get("role_id");

		if($PermissionRoleModel->addPermissionRole($data)){
			return array('code' => '0000');
		}else{
			return array('code' => '0001');
		}
	}

	/**
	 * 删除角色权限
	 */
	public function delPermissionRole(Request $request){
		if(!$request->has('permission_id')){
			return array('code' => '0001');
		}
		if(!$request->has('role_id')){
			return array('code' => '0001');
		}

		if(AdminPermissionRole::where('role_id' , $request->get('role_id'))->where('permission_id' , $request->get('permission_id'))->delete()){
			return array('code' => '0000');
		}else{
			return array('code' => '0001');
		}
	}

}
