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

class Activity extends Model{

    protected $table = 'activity';

    public function getActivitiyById($id){
        return DB::table($this->table)->where('id' , $id)->where('is_open' , 1)->first();
    }

}
