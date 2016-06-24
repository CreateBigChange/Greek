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

class StoreSettlings extends Model
{

    protected $table = 'store_settlings';
    /**
     *
     * 申请入驻
     * @param data  array
     */
    public function setting($data)
    {
        return DB::table($this->table)->insert($data);
    }

}