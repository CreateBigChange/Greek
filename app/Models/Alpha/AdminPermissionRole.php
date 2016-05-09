<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/17
 * Time: 上午9:38
 */

namespace App\Models\Alpha;

use DB;
use Illuminate\Database\Eloquent\Model;

class AdminPermissionRole extends Model
{
    protected $_table = 'admin_permission_roles';

	/*
	 * 添加角色权限
	 */
	public function addPermissionRole($data){
		return DB::table($this->_table)->insert($data);
	}

	/*
	 * 通过角色ID整个权限关系
	 */
	public function getPermissionRoleByID($rid){
		$parent = DB::table('admin_permissions')->where('level' , 0)->orderBy('sort' , 'asc')->get();
		$child	= DB::table('admin_permissions')->where('level' , 1)->get();
		$cchild	= DB::table('admin_permissions')->where('level' , 2)->get();
		$rp		= DB::table($this->_table)->where('role_id' , $rid)->get();

		$permissionRoleIds = array();

		foreach($rp as $r){
			$permissionRoleIds[] = $r->permission_id;
		}

		foreach($child as $c){
			$c->child = array();
			foreach ($cchild as $cc){
				if(in_array($cc->id , $permissionRoleIds)){
					$cc->is_role = 1;
				}
				if($cc->fid == $c->id){
					$c->child[] = $cc;
				}
			}
		}

		foreach($parent as $p){
			$p->is_role = 0;
			if(in_array($p->id , $permissionRoleIds)){
				$p->is_role = 1;
			}
			$p->child = array();
			foreach($child as $c){
				$c->is_role = 0;
				if(in_array($c->id , $permissionRoleIds)){
					$c->is_role = 1;
				}
				//$c->url = '/' . implode('/' , explode('.' , $c->name));
				if($c->fid == $p->id){
					$p->child[] = $c;
				}
			}
		}

		return $parent;
	}

}
