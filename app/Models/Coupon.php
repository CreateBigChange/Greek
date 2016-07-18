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

class Coupon extends Model
{

    protected $table = 'coupon';

    public function addCoupon($data){

        return DB::table($this->table)->insertGetId($data);
    }

    public function getCouponList($search = array() , $length=10 , $offset=0){
        $sql = DB::table($this->table);

        if(isset($search['id'])){
            $sql->where('id' , $search['id']);
        }

        if(isset($search['store_id'])){
            $sql->where('store_id' , $search['store_id']);
        }

        return $sql->skip($offset)
            ->take($length)
            ->get();
    }

    public function getCouponTotalNum($search = array()){
        $sql = DB::table($this->table);

        if(isset($search['id'])){
            $sql->where('id' , $search['id']);
        }

        if(isset($search['store_id'])){
            $sql->where('store_id' , $search['store_id']);
        }

        return $sql->count();
    }


    public function stopCoupon($couponId , $data){
        return DB::table($this->table)->where('id' , $couponId)->update($data);

    }
}