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





    public function updateCoupon($data,$id)
    {

        return DB::table('coupon')
            ->where('id', $id) 
            ->update($data);
    }

    /**
     * @return mixed
     * 获取优惠券总数目
     */
    public function couponTotalNum()
    {
        $sql = "select  count(*) as num from coupon";
        $num = DB::select($sql);
        return $num[0]->num;
    }

    /**
     * @param $data
     * @return mixed
     * 增加优惠券
     *
     */
    public function addCoupon($data){

        return DB::table($this->table)->insertGetId($data);
    }

    public function getCouponList($search = array() , $length=10 , $offset=0){
        $sql = DB::table($this->table);

        if(isset($search['id'])){
            $sql->where('coupon.id' , $search['id']);
        }

        if(isset($search['store_id'])){
            $sql->where('coupon.store_id' , $search['store_id']);
        }

        return $sql->skip($offset)
            ->take($length)
            ->leftJoin('store_infos' , 'coupon.store_id' , '=' , 'store_infos.id')
            ->select('coupon.id', 'coupon.name as name', 'coupon.content', 'coupon.type', 'coupon.effective_time','coupon.value', 'coupon.prerequisite', 'coupon.store_id', 'coupon.total_num', 'coupon.in_num', 'coupon.out_num', 'coupon.stop_out', 'coupon.num', 'coupon.created_at', 'coupon.updated_at', 'store_infos.name as store_name')
            ->orderBy('coupon.created_at' , 'desc')
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

    /**
     * @param int   $couponId
     * @param arry $data
     * @return mixed
     * 修改优惠券的状态
     */
    public function stopCoupon($couponId , $data){

        return DB::table($this->table)->where('id' , $couponId)->update($data);
    }

    public function getStoreCouponByLast($storeId){

        return DB::table($this->table)->where('store_id' , $storeId)->where('stop_out' , 0)->where('num' , '<>' , '0')->orderBy('created_at' , 'asc')->first();
    }

}