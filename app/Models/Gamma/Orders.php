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
                    o.pay_total
                    
                FROM $this->_orders_table AS o";

        $sql .= ' WHERE 1=1';
        if(isset($search['status'])){
            $sql .= " AND o.status IN (".implode(',' , $search['status']).")";
        }
        if(isset($search['search']) && !empty($search['search'])){
            $sql .= " AND o.consignee LIKE '%" . $search['search'] . "%'" . " OR  o.consignee_tel LIKE '%" . $search['search'] . "%'" . " OR  o.order_num LIKE '%" . $search['search'] . "%'";
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

        $isChange = DB::table($this->_orders_table)->where('store_id' , $storeId)->where('id' , $id)->update(array('status' => $status));

        if($isChange){

            $log = array(
                'order_id'      => $id,
                'user'          => $userId,
                'identity'      => '商家管理员',
                'platform'      => '手机端',
                'log'           => '将订单'. $id . '的状态改为'.$status,
                'created_at'    => date('Y-m-d H:i:s' , time()),
                'status'        => $status
            );
            DB::table($this->_order_logs_table)->insert($log);
            return true;
        }else{
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
}
