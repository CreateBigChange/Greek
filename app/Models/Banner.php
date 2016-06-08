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

class Banner extends Model
{

    public function getBannerList(){
        return DB::table($this->_table)->where('is_open' , 1)->get();
    }

}