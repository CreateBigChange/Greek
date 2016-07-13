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

use App\Models\AdminPermission;

class AdminPermissionController extends AdminController
{

    public function __construct(){
        parent::__construct();
        $this->response['title']		= '权限管理';
        $this->response['menuactive']	= '权限管理';

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
        //dump( $this->response);
        return view('alpha.auth.permissions.list' , $this->response);
    }

    /**
     * 获取节点列表 ajax请求
     */
    public function ajaxPermissionsList(){
        $this->response['title']		= '角色列表';
        $this->response['menuactive']	= 'qxgl';

		$permissionModel = new AdminPermission;

		$permissionList = $permissionModel->getPermissionsList();

        $this->response['permissions'] = $permissionList;

		return  $this->response;

    }

	
    /**
     * 添加节点
     */
    public function addPermission(Request $request){
        $this->response['title']		= '添加节点';
        $this->response['menuactive']	= 'qxgl';


        if(!$request->has('fid')){
            return view('errors.503');
        }

        $permissions						= new AdminPermission;
        //$permissions->display_name          = $request->get('display_name');
        $permissions->fid					= $request->get('fid');

        //$permissions->description			= $request->get('description');

        $permissions->level = $request->get('level');
        if($request->has('url')) {
            $permissions->url = $request->get('url');
        }

        if($request->has('name')){
			$permissions->name					= $request->get('name');
        }
		if($request->has('is_menu')){
			$permissions->is_menu				= $request->get('is_menu');
			if($permissions->is_menu == 'on'){
				$permissions->is_menu = 1;
			}else{
				$permissions->is_menu = 0;
			}
		}
		if($request->has('sort')){
			$permissions->sort					= $request->get('sort');
		}
		if($request->has('icon')){
			$permissions->icon					= $request->get('icon');
		}

        if($permissions->save()){
            return redirect('alpha/permissions');
        }
    }

    /**
     * 删除节点
     */
    public function delPermission($id){

        AdminPermission::where('id' , $id)->delete();
   
        return redirect('alpha/permissions');

    }

    /**
     * 获取节点信息
     */
    public function getPermissionInfo($id){

        $permissionModel	= new AdminPermission;
         return $id;
		$info				= $permissionModel->getPermissionInfo($id);

		return response()->json($info);

    }

    /**
     * 更新节点
     */
    public function updatePermission(Request $request){

      
		if(!$request->has('id')){
           return view('errors.503');
		}

        $this->response['title']		= '更新节点';
        $this->response['menuactive']	= 'qxgl';

		$id = $request->get('id');

        $update = array();
        if($request->has('name')){
            $update['name'] = $request->get('name');
        }
//        if($request->has('description')){
//            $update['description'] = $request->get('description');
//        }
//        if($request->has('display_name')){
//            $update['display_name'] = $request->get('display_name');
//        }
        if($request->has('url')){
            $update['url'] = $request->get('url');
        }
        if($request->has('sort')){
            $update['sort'] = $request->get('sort');
        }
        if($request->has('fid')){
            $update['fid'] = $request->get('fid');
        }
        if($request->has('icon')){
            $update['icon'] = $request->get('icon');
        }
        if($request->has('is_menu')){
            $update['is_menu'] = $request->get('is_menu');
			if($update['is_menu'] == 'on'){
				$update['is_menu'] = 1;
			}else{
				$update['is_menu'] = 0;
			}
        }

        AdminPermission::where('id' , $id)->update($update);
        return redirect('alpha/permissions');
    }

	/**
	 * 获取层级节点
	 */
	public function getLevelPermission($level){
		return AdminPermission::where('level' , $level)->get();
	}

}
