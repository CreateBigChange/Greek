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

class Permissions extends Model
{
    protected $_table = 'admin_permissions';

	/**
	 * 获取登录用户可访问的菜单
	 */
	public function getAdminUserMenu( $userId ) {
		$permissions = DB::table('admin_user_roles as ur')
			->join('admin_permission_roles as pr' , 'pr.role_id' , '=' , 'ur.role_id')
			->join('admin_permissions as p' , 'p.id' , '=' , 'pr.permission_id')
			->orderBy('p.sort' , 'asc')
			->where('is_menu' , '1')
			->where('admin_user_id' , $userId)
			->get();

		$parent = array();
		$child = array();
		foreach($permissions as $p){
			if($p->fid == 0){
				$parent[] = $p;
			}else{
				$child[] = $p;
			}
				
		}

		foreach($parent as $p){
			$p->child = array();
			foreach($child as $c){
				if($c->fid == $p->id){
					$p->child[] = $c;
				}
			}
		}

		return $parent;
	}

	/**
	 * 获取节点列表
	 */
	public function getPermissionsList() {
		$parent = DB::table($this->_table)->where('fid' , 0)->get();	
		$child	= DB::table($this->_table)->where('fid' , '<>' , 0)->get();	

		foreach($parent as $p){
			$p->child = array();
			foreach($child as $c){
				if($c->fid == $p->id){
					$p->child[] = $c;
				}
			}
		}

		return $parent;
	}

	/**
	 * 获取节点信息
	 */
	public function getPermissionInfo($id) {
		$child = DB::table($this->_table)->where('id' , $id)->get();
		if(!empty($child)){
			$child[0]->parent = '';
			$parent	= DB::table($this->_table)->where('id' , $child[0]->fid)->get();
			if(!empty($parent)){
				$child[0]->parent = $parent[0];
			}
			return $child[0];
		}

		return false;

	}

}
