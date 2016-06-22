<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models\Gamma;

use DB , Config;
use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;

use App\Models\Gamma\Stores;
use Symfony\Component\HttpKernel\HttpCache\Store;

class Orders extends Model
{
    protected $_orders_table            = 'orders';
    protected $_order_infos_table       = 'order_infos';
    protected $_order_logs_table        = 'order_logs';

    /**
     * 获取订单总数
     * @param storeId   number
     * @param search    array
     */
    public function getOrderTotalNum($storeId , $search){
        $sql = DB::table($this->_orders_table)
            ->where('store_id' , $storeId);
        if(isset($search['status'])){
            $sql->whereIn('status' , $search['status']);
        }
        return $sql->count();
    }

    /**
     * 获取订单列表
     * @param storeId   number
     * @param search    array
     * @param length    number
     * @param offset    number
     */
    public function getOrderList($storeId , $search = array() , $length = 20 , $offset = 0){
        $sql = "SELECT
                    o.id,
                    o.order_num,
                    o.total,
                    o.deliver,
                    o.in_points,
                    o.out_points,
                    o.status,
                    o.store_id,
                    o.user,
                    o.consignee,
                    o.consignee_id,
                    o.consignee_tel,
                    o.consignee_province as province,
                    o.consignee_city as city,
                    o.consignee_county as county,
                    o.consignee_street as street,
                    o.consignee_address,
                    o.remark,
                    o.refund_reason,
                    o.created_at,
                    o.pay_total,
                    o.pay_type_id,
                    o.out_trade_no
                    
                FROM $this->_orders_table AS o";

        $sql .= ' WHERE 1=1';
        if(isset($search['status'])){
            $sql .= " AND o.status IN (".implode(',' , $search['status']).")";
        }
        if(isset($search['search']) && !empty($search['search'])){
            $sql .= " AND o.consignee LIKE '%" . $search['search'] . "%'" . " OR  o.consignee_tel LIKE '%" . $search['search'] . "%'" . " OR  o.order_num LIKE '%" . $search['search'] . "%'";
        }
        if(isset($search['id'])){
            $sql .= " AND o.id =" . $search['id'];
        }

        $sql .= " AND store_id = $storeId";

        $sql .= " GROUP BY o.id";
        $sql .= " ORDER BY created_at DESC";
        $sql .= " LIMIT $offset , $length";

        $orders = DB::select($sql);

        $orderIds = array();
        foreach ($orders as $o){
            $orderIds[] = $o->id;
        }

        $goods = DB::table($this->_order_infos_table)->whereIn('order_id' , $orderIds)->get();

        foreach ($orders as $o){
            $o->goods = array();
            $o->goodsNum = 0;

//            if(!$o->consignee_id){
//                unset($orders[$i]);
//                continue;
//            }
            foreach ($goods as $g){
                if($g->order_id == $o->id){
                    $o->goods[] = $g;
                    $o->goodsNum += $g->num;
                }
            }
        }

        return $orders;

    }

    /**
     * 更改订单状态
     * @param storeId   number
     * @param id        number
     * @param status    number
     */
    public function changeStatus($storeId , $userId , $id , $status){

        DB::beginTransaction();
        try {

            DB::table($this->_orders_table)->where('store_id', $storeId)->where('id', $id)->update(array('status' => $status));


            $log = array(
                'order_id' => $id,
                'user' => $userId,
                'identity' => '商家管理员',
                'platform' => '手机端',
                'log' => '将订单' . $id . '的状态改为' . $status,
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );
            DB::table($this->_order_logs_table)->insert($log);

            //如果是已送达,才修改店铺的余额
            if($status == Config::get('orderstatus.arrive')['status']){
                $orderInfo = DB::table($this->_orders_table)->where('store_id' , $storeId)->where('id' , $id)->first();
                if(!$orderInfo){
                    return false;
                }

                $storeModel = new Stores;
                $storeInfo = $storeModel->getStoreInfo($storeId);
                if(!$storeInfo){
                    return false;
                }

                $money = $storeInfo->money + $orderInfo->pay_total;

                $storeModel->updateMoney($storeId, $money);
            }

            DB::commit();
            return true;
        }catch (Exception $e){
            DB::rollBack();

            return false;
        }


    }


    /**
     * 获取今日订单统计数据
     */
    public function getOrderTodayCounts($storeId , $date=0){
        $sql = "SELECT 
                    count(`id`) as order_num , 
                    sum(`total`) as turnover
                FROM $this->_orders_table ";
        $sql .= " WHERE `store_id` = $storeId";
        $sql .= " AND `created_at` LIKE '" .$date . "%'";
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] .')';

        return DB::select($sql);

    }

    /**
     * 获取订单统计数据
     */
    public function getOrderCounts($storeId){
        $sql = "SELECT 
                    count(`id`) as order_num , 
                    sum(`total`) as turnover
                FROM $this->_orders_table ";
        $sql .= " WHERE `store_id` = $storeId";
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] .')';

        $user = DB::table($this->_orders_table)->select("user")->where('store_id' , $storeId)->whereNotIn('status' , array(
            Config::get('orderstatus.no_pay')['status'],
            Config::get('orderstatus.cancel')['status']
        ))->groupBy('user')->get();



        $count =  DB::select($sql);

        $count = $count[0];
        $count->user = count($user);

        if(!$count->turnover){
            $count->turnover = 0;
            $count->turnover_user = 0;
        }else {
            $count->turnover_user = round($count->turnover / $count->user, 2);
        }

        return $count;
    }

    /**
     * 获取本月收入的积分和使用的积分
     */
    public function getOrderMonthPoint($storeId , $date=0){
        $sql = "SELECT 
                    sum(`out_points`) as out_points , 
                    sum(`in_points`) as in_points
                FROM $this->_orders_table ";
        $sql .= " WHERE `store_id` = $storeId";
        $sql .= " AND `created_at` LIKE '" .$date . "%'";
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] . ',' . Config::get('orderstatus.refunded')['status'] .')';

        return DB::select($sql);

    }

    /**
     * 退款
     * @param storeId   number
     * @param id        number
     * @param status    number
     */
    public function refund($storeId , $orderId , $refundNo){

        DB::beginTransaction();

        try {
            $order = DB::table($this->_orders_table)->where('store_id' , $storeId)->where('id' , $orderId)->first();
            if(!$order){
                return false;
            }

            //更新店铺积分
            $storeModel = new Stores;
            $storeInfo = $storeModel->getStoreInfo($storeId);
            if(!$storeInfo){
                return false;
            }
            $point = $storeInfo->pint + $order->out_points;
            $storeModel->updatePoint($storeId, $point);

            //如果订单状态是已送达和已完成再退款的,需要返还用户积分和店铺的余额
            if($order->status == Config::get('orderstatus.arrive')['status'] || $order->status == Config::get('orderstatus.completd')['status']){
                $userInfo = DB::table('users')->where('id' , $order->user)->first();

                $userPoint = $userInfo->points + $order->out_points;

                DB::table('users')->where('id' , $order->user)->update(array('points'=>$userPoint));
                
                //更新店铺余额
                $money = $storeInfo->money - $order->pay_total;
                $storeModel->updateMoney($storeId, $money);
            }

            DB::table($this->_orders_table)->where('id', $orderId)->update(array(
                'status' => Config::get('orderstatus.refunded')['status'],
                'refund_no' => $refundNo
            ));

            $log = array(
                'order_id' => $orderId,
                'user' => '',
                'identity' => '商家管理员',
                'platform' => '手机端',
                'log' => '将订单' . $orderId . '的状态改为' . Config::get('orderstatus.refunded')['status'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => Config::get('orderstatus.refunded')['status']
            );
            DB::table($this->_order_logs_table)->insert($log);

            DB::commit();
            return true;
        }catch (Exception $e){
            DB::rollBack();

            return false;
        }


    }
}
