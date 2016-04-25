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

class AdminRoles extends Model
{
    protected $_table = 'admin_roles';

	public function getRoleList(){
		return DB::table($this->_table)->get();
	}

}
