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

class OrderLog extends Model{

    protected $table = 'order_logs';

    /**
     * @param $orderId
     * @param $userId
     * @param $identity
     * @param $platform
     * @param $log
     * @return bool
     * 创建订单log
     */
    static function createOrderLog($orderId , $userId , $identity , $platform , $log , $status = 0 ){

        //$statusChangeLog = Config::get('orderstatus.no_pay');
        $statusChangeLog['updated_at']      = date('Y-m-d H:i:s' , time());
        $statusChangeLog['created_at']      = date('Y-m-d H:i:s' , time());
        $statusChangeLog['user']            = $userId;
        $statusChangeLog['identity']        = $identity;
        $statusChangeLog['platform']        = $platform;
        $statusChangeLog['log']             = $log;
        $statusChangeLog['order_id']        = $orderId;

        if($status != 0) {
            $statusChangeLog['status'] = $status;
        }

        if(DB::table(self::table)->insert($statusChangeLog)){

            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $orderId
     * @param $userId
     * @return mixed
     *
     * 获取订单log
     */
    static function getOrderLog( $orderId ){

        $logs = DB::table(self::table)->where('order_id' , $orderId)->orderBy('created_at' , 'asc')->get();

        return $logs;

    }

}
