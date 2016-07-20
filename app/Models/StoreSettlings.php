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

    /**
     * 完成入驻
     */
    public function delSettlings($id){
        return DB::table($this->table)->where('id' , $id)->delete();
    }

    /**
     * 申请入驻列表
     */
    public function getSettlings(){
        return DB::table($this->table)
            ->select(
                'store_settlings.id',
                'store_settlings.name',
                'store_settlings.contact',
                'store_settlings.address',
                'store_settlings.status',
                'store_settlings.created_at',
                'p.id AS province_id',
                'p.name AS province',
                'ci.id AS city_id',
                'ci.name AS city',
                'co.id AS county_id',
                'co.name AS county'
            )
//            ->leftJoin('areas as p' , 'p.id' , '=' , 'store_settlings.province')
//            ->leftJoin('areas as ci' , 'ci.id' , '=' , 'store_settlings.city')
//            ->leftJoin('areas as co' , 'co.id' , '=' , 'store_settlings.county')
            ->orderBy('status' , 'ASC')
            ->orderBy('created_at' , 'DESC')
            ->get();
    }
}