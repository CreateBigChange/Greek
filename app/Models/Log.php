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

class Log extends Model{

    protected $table = 'logs';

    public function addActionLog($data){
        return DB::table($this->table)->insert($data);
    }

}
