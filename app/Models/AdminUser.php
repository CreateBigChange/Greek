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

class AdminUser extends Model{

    protected $table = 'admin_users';

    /**
     * 获取用户权限列表
     */
    public function getAdminUserPermissions($userId){
        $permissions = DB::table('admin_user_roles as ur')
            ->join('admin_permission_roles as pr' , 'pr.role_id' , '=' , 'ur.role_id')
            ->join('admin_permissions as p' , 'p.id' , '=' , 'pr.permission_id')
            ->where('admin_user_id' , $userId)
            ->get();

        $data = array();
        foreach($permissions as $p){
            if(!empty($p->name)){
                $data = array_merge($data , explode(',' , $p->url));
            }
        }

        return $data;

    }


    /**
     * @param $tel
     * @return mixed
     */
    public function getAdminUserSalt($account){
        return DB::table($this->table)
            ->select('salt')
            ->where('account' , $account)
            ->first();
    }


    /**
     * @param $account
     * @param $password
     * @return mixed
     */
    public function checkLogin( $account , $password ){

        $isLogin = DB::table($this->table)
            ->where('account' , $account)
            ->where('password' , $password)
            ->first();

        return $isLogin;
    }

    /**
     * 用户列表
     * @author		wuhui
     * @time		2016/01/18
     * @params
     * @response
     *				管理员列表或错误信息
     */
    public function getAdminUserList(){
        $list = DB::table($this->table)
            ->orderBy('admin_users.created_at' , 'desc')
            ->where('is_del' , 0)
            ->get();

        $userIds = array();

        foreach($list as $l){
            $userIds[] = $l->id;
        }

        $roles = DB::table('admin_user_roles')
            ->whereIN('admin_user_id' , $userIds)
            ->leftJoin('admin_roles' , 'admin_roles.id' , '=' , 'admin_user_roles.role_id')
            ->get();

        foreach($list as $l){
            $l->role = array();
            foreach($roles as $r){
                if($l->id == $r->admin_user_id){
                    $l->role[] = $r;
                }
            }
        }

        return $list;
    }

    /**
     * 用户总数
     * @author		wuhui
     * @time		2016/01/18
     * @params
     * @response
     *				管理员列表或错误信息
     */
    public function getAdminUserTotalNum(){
        return DB::table('admin_users')
            ->count();
    }


    /**
     * 添加管理员角色
     * @author		wuhui
     * @time		2016/01/31
     * @params
     * @response
     *				成功或错误信息
     */
    public function addAdminUserRole( $userId , $roles ){
        $data = array();
        foreach($roles as $r){
            $data[] = array('role_id'=>$r , 'admin_user_id'=>$userId);
        }
        return DB::table('admin_user_roles')->insert($data);
    }

    /**
     * 添加管理员
     */
    public function addAdminUser($data){
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     * 编辑管理员信息
     * @author		wuhui
     * @time		2016/01/18
     * @params
     * @response
     *				成功或错误信息
     */
    public function editAdminUserInfo( $userId , $data ){
        return DB::table('admin_users')
            ->where('id' , $userId)
            ->update($data);
    }

    /**
     * 删除管理员角色
     * @author		wuhui
     * @time		2016/01/31
     * @params
     * @response
     *				成功或错误信息
     */
    public function delAdminUserRole( $userId ){
        return	DB::table('admin_user_roles')->where('admin_user_id' , $userId)->delete();
    }

}
