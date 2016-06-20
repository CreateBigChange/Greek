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

    protected $table = 'banners';

    /*
     * 获取轮波图列表
     * is_open      是否上线
     */
    public function getBannerList(){
        return DB::table($this->table)->where('is_open' , 1)->orderBy('sort' , 'desc')->get();
    }

}