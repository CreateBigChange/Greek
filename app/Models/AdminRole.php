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

class AdminRole extends Model{

    protected $table = 'admin_roles';

    public function getRoleList(){
        return DB::table($this->table)->get();
    }

}
