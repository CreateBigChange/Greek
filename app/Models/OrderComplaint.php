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

class OrderComplaint extends Model{

    protected $table        = 'order_complaints';

    /**
     * @param $data
     * @return mixed
     * 投诉
     */
    public function complaint($data){
        return DB::table($this->table)->insert($data);
    }

}
