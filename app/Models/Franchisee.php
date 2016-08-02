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

class Franchisee extends Model
{

    protected $table = 'franchisee';
    /**
     *
     * 申请入驻
     * @param data  array
     */
    public function franchisee($data)
    {
        return DB::table($this->table)->insert($data);
    }

}