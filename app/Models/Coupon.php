<?php
/**

 * CouponModel
 * @author  yangxiansheng
 * @time    2016/06-08
 * @email   31479274@qq.com

 */
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;


class Coupon extends Model

{

    protected $table = 'coupon';

    public function getCouponListOther(){
        $sql = "select * from coupon";

        return DB::select($sql);
    }

    public function addCouponOther($data){

        return  DB::table('coupon')->insertGetId($data);

    }


    public function couponDelete($id)
    {
        DB::table('coupon')->where('id', '=', $id)->delete();
    }


    public function updateCoupon($data,$id)
    {

        return DB::table('coupon')
            ->where('id', $id)
            ->update($data);
    }

    public function couponTotalNum()
    {
        $sql = "select  count(*) as num from coupon";
        $num = DB::select($sql);
        return $num[0]->num;
    }


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
            ->orderBy('created_at desc')
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

    public function getStoreCouponByLast($storeId){
        return DB::table($this->table)->where('store_id' , $storeId)->where('num' , '<>' , '0')->orderBy('created_at  asc')->first();
    }

}