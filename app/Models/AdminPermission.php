<?php
/**
 * bannerModel
 * @author  wuhui
 * @time    2016/06-08
 * @email   wuhui904107775@qq.com
 */
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model{

    protected $table = 'admin_permissions';

    /**
     * 获取登录用户可访问的菜单
     */
    public function getAdminUserMenu( $userId ) {
        $permissions = DB::table('admin_user_roles as ur')
            ->join('admin_permission_roles as pr' , 'pr.role_id' , '=' , 'ur.role_id')
            ->join('admin_permissions as p' , 'p.id' , '=' , 'pr.permission_id')
            ->orderBy('p.sort' , 'asc')
            ->where('p.is_menu' , '1')
            ->where('ur.admin_user_id' , $userId)
            ->get();

        $parent = array();
        $child = array();

        $permissionIds = array();

        foreach($permissions as $p){
            $permissionIds[] = $p->permission_id;
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
        $parent = DB::table($this->table)->where('level' , 0)->orderBy('sort' , 'asc')->get();
        $child	= DB::table($this->table)->where('level' , 1)->get();
        $cchild	= DB::table($this->table)->where('level' , 2)->get();

        foreach($child as $c){
            $c->child = array();
            foreach ($cchild as $cc){
                if($cc->fid == $c->id){
                    $c->child[] = $cc;
                }
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
     * 获取节点信息
     */
    public function getPermissionInfo($id) {
        $child = DB::table($this->table)->where('id' , $id)->get();
        if(!empty($child)){
            $child[0]->parent = '';
            $parent	= DB::table($this->table)->where('id' , $child[0]->fid)->get();
            if(!empty($parent)){
                $child[0]->parent = $parent[0];
            }
            return $child[0];
        }

        return false;

    }


}
