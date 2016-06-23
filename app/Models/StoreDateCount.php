<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/6/23
 * Time: 上午11:40
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class StoreDateCount extends Model
{

    protected $table  = 'store_date_counts';

    /**
     * 添加今日到店人数统计数据
     */
    public function addStoreCount($storeId){
        $y = date('Y' , time());
        $m = date('m' , time());
        $d = date('d' , time());
        $h = date('H' , time());

        if(DB::table($this->table)
            ->where('store_id' , $storeId)
            ->where('year' , $y)
            ->where('month' , $m)
            ->where('day' , $d)
            ->where('hour' , $h)
            ->first()
        ){
            return DB::table($this->table)->increment('visiting_number');
        }else{
            return DB::table($this->table)->insert(array(
                'date'                  => date('Y-m-d H:i:s' , time()),
                'year'                  => $y,
                'month'                 => $m,
                'day'                   => $d,
                'hour'                  => $h,
                'store_id'              => $storeId,
                'visiting_number'       => 1
            ));
        }
    }
}