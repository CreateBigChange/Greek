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

class PayType extends Model{

    protected $table = 'pay_type';

    static function getTable(){
        return self::table;
    }
}
