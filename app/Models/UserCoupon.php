<?php
/**
 * bannerModel
 * @author  wuhui
 * @time    2016/06-08
 * @email   wuhui904107775@qq.com
 */
namespace App\Models;

use Config , DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Coupon;
use App\Models\StoreInfo;
use App\Models\Order;

class UserCoupon extends Model{

    protected $table = 'user_coupon';


    public function getUserCoupon($userId , $length = 0 , $offset = 0){

        $date = date('Y-m-d H:i:s' , time());

        $couponModel = new Coupon();

        $sql = DB::table($this->table . " as uCoupon")
            ->select(
                'uCoupon.id',
                'uCoupon.coupon_id',
                'uCoupon.expire_time',
                'uCoupon.created_at',
                'coupon.name',
                'coupon.type',
                'coupon.value',
                'coupon.content',
                'coupon.prerequisite',
                'coupon.store_id'
            )
            ->join($couponModel->getTable() . " as coupon" , "coupon.id" , "=" , "uCoupon.coupon_id")
            ->where('is_use' , 0)
            ->where('user_id' , $userId)
            ->where('expire_time' , '>=' , $date);
            if($length != 0) {
                $sql->skip($offset)
                    ->take($length);
            }
            $sql->orderBy('value' , 'desc');
            $coupon =  $sql->get();

        $storeId = array();
        foreach ($coupon as $c){
            $storeId[] = $c->store_id;
        }

        $storeModel = new StoreInfo();
        $store = DB::table($storeModel->getTable())->whereIn('id' , $storeId)->get();

        foreach ($coupon as $c) {
            $c->store_name = '';
            foreach ($store as $s) {
                if($c->store_id == $s->id){
                    $c->store_name = $s->name;
                }
            }
        }

        return $coupon;
    }

    public function getUserCouponTotalNum($userId){

        $date = date('Y-m-d H:i:s' , time());

        return $coupon = DB::table($this->table)
            ->where('is_use' , 0)
            ->where('user_id' , $userId)
            ->where('expire_time' , '>=' , $date)
            ->count();
    }

    public function getOrderCoupon($userId , $orderId , $length , $offset){
        $orderModel = new Order();
        $order  = DB::table($orderModel->getTable())->where('id' , $orderId)->where('status' , Config::get('orderstatus.no_pay')['status'])->first();

        if($order->user != $userId){
            return false;
        }
        $coupon = $this->getUserCoupon($userId , $length , $offset);

        if(!$order){
            return false;
        }

        $coupon  = $this->reckonCouponWithOrder($coupon, $order);

        $canUseCoupon = $coupon['canUseCoupon'];
        $notCanUseCoupon = $coupon['notCanUseCoupon'];

        /**
         * 给可用优惠券排序
         */
        for ($i = 0 ; $i < count($canUseCoupon) ; $i++ ){
            for ($j = 1 ; $j < count($canUseCoupon) ; $j++ ){
                if($canUseCoupon[$i]->value < $canUseCoupon[$j]->value){
                    $tmpCoupon = $canUseCoupon[$i];
                    $canUseCoupon[$i] = $canUseCoupon[$j];
                    $canUseCoupon[$i] = $tmpCoupon;
                }
            }
        }

        $canUseCoupon = array_merge($canUseCoupon , $notCanUseCoupon);

        return $canUseCoupon;
    }

    /**
     * @param $coupon
     * @param $order
     * @return array
     *
     * 计算优惠券与订单的关系
     */
    public function reckonCouponWithOrder($coupon , $order){

        /**
         * type = 1 为满减券
         */
        $canUseCoupon = array();
        $notCanUseCoupon = array();

        /**
         * 计算订单可用的优惠券
         */
        foreach ($coupon as $c){
            $c->canUse = 0;
            if ($c->type == 1 && $c->prerequisite <= $order->total) {
                if($c->store_id != 0) {
                    if( $c->store_id == $order->store_id) {
                        $c->canUse = 1;
                        $canUseCoupon[] = $c;
                    }else{
                        $notCanUseCoupon[] = $c;
                    }
                }else{
                    $c->canUse = 1;
                    $canUseCoupon[] = $c;
                }
            }else{
                $notCanUseCoupon[] = $c;
            }
        }

        return array('canUseCoupon'=>$canUseCoupon , 'notCanUseCoupon'=>$notCanUseCoupon);

    }

    /**
     * @param $coupon
     * @param $order
     * @return array
     *
     * 获取一个订单可以使用的优惠券
     */
    public function getCanUseCouponWithOrder($order){

        if(!$order){
            return false;
        }

        $coupon = $this->getUserCoupon($order->user);

        $coupon  = $this->reckonCouponWithOrder($coupon, $order);

        return $coupon['canUseCoupon'];

    }


    public function getCouponById($userId , $couponId){

        $date = date('Y-m-d H:i:s' , time());

        $couponModel = new Coupon();

        $coupon = DB::table($this->table . " as uCoupon")
            ->select(
                'uCoupon.id',
                'uCoupon.coupon_id',
                'uCoupon.expire_time',
                'uCoupon.created_at',
                'coupon.name',
                'coupon.type',
                'coupon.content',
                'coupon.value',
                'coupon.prerequisite',
                'coupon.store_id'
            )
            ->join($couponModel->getTable() . " as coupon" , "coupon.id" , "=" , "uCoupon.coupon_id")
            ->where('is_use' , 0)
            ->where('user_id' , $userId)
            ->where('uCoupon.id' , $couponId)
            ->where('expire_time' , '>=' , $date)
            ->first();

        return $coupon;
    }


    /**
     * 根据优惠券的类型计算价格
     */
    public function reckonDiscountMoney($type , $couponValue , $total){
        $discountMoney = 0;
        if($type == 1){
            $discountMoney = $couponValue;
        }

        return $discountMoney;
    }


    /**
     * 更新优惠
     */
    public function updateCouponIsuse($userId , $couponId  , $isUse){

        $couponModel = new Coupon();
        $isUpdate =  DB::table($this->table)->where('user_id' , $userId)->where('id' , $couponId)->update(array('is_use' => $isUse));
        if($isUpdate) {
            if ($isUse == 1) {
                DB::table($couponModel->getTable())->where('id' , $couponId)->increment('in_num');
                DB::table($couponModel->getTable())->where('id' , $couponId)->decrement('out_num');
                DB::table($couponModel->getTable())->where('id' , $couponId)->increment('num');
            }elseif($isUse == 0){
                DB::table($couponModel->getTable())->where('id' , $couponId)->increment('out_num');
                DB::table($couponModel->getTable())->where('id' , $couponId)->decrement('num');
            }
        }
    }


    /**
     * 给用户发放优惠卷
     */
    public function addUserCoupon($data){
        if(DB::table($this->table)->insert($data)){
            $couponModel = new Coupon();

            if(isset($data['coupon_id'])) {
                $couponId = $data['coupon_id'];
                $num = count($data);
            }else{
                $couponId = $data[0]['coupon_id'];
                $num = count($data);
            }

            DB::table($couponModel->getTable())->where('id', $couponId)->increment('out_num' , $num);
            DB::table($couponModel->getTable())->where('id', $couponId)->decrement('num' , $num);

            return true;
        }else{
            return false;
        }
    }

}
