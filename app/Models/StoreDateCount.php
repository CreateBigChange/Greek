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

    /**
     * 获取店铺统计数据
     */
    public function getTodayStoreCount($storeId , $date ){

        return DB::table($this->_store_date_counts_table)->where('store_id' , $storeId)->where('date' ,'like' , $date.'%')->get();

    }



    /**
     * 店铺统计数据(本月)
     */
    public function financeCountByMonth($storeId , $year , $month){
        $sql = "SELECT 
                    sum(`total`) as turnover,
                    sum(`out_points`) as outPoint,
                    sum(`in_points`) as inPoint,
                    `day`
               FROM orders";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] .')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " GROUP BY day ORDER BY day ASC ";

        $count = DB::select($sql);

        return $count;

    }

    /**
     * 店铺统计数据(本周)
     */
    public function financeCountByWeek($storeId , $year , $month , $day){
        $sql = "SELECT 
                    sum(`total`) as turnover,
                    sum(`out_points`) as outPoint,
                    sum(`in_points`) as inPoint,
                    `day`
               FROM orders ";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] .')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " AND day IN (" . $day .")";
        $sql .= "  GROUP BY day ORDER BY day ASC";

        $count = DB::select($sql);

        return $count;

    }

    /**
     * 店铺统计数据(本天)
     */
    public function financeCountByDay($storeId , $year , $month , $day){
        $sql = "SELECT 
                    `total` as turnover,
                    `out_points` as outPoint,
                    `in_points` as inPoint,
                    `hour`                    
               FROM orders";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] .')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " AND day IN (" . $day .")";
        $sql .= " ORDER BY hour ASC ";

        $count = DB::select($sql);

        return $count;

    }
}