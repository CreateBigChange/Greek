<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models\Alpha;

use DB;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $_users_table            = 'users';

    /**
     * @return mixed
     *
     * 获取用户列表
     */
    public function getUserList(){
        return DB::table($this->_users_table)
            ->select(
                'id',
                'account',
                'nick_name',
                'true_name',
                'sex',
                'mobile',
                'avatar',
                'points',
                'money',
                'login_type',
                'created_at'
            )
            ->where('is_del' , 0)->get();
    }
}
