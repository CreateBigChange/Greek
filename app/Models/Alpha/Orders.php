<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models\Alpha;

use DB;
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
    public function getOrderTotalNum($search = array()){
        $sql = DB::table($this->_orders_table);
//            ->where('store_id' , $storeId);
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
    public function getOrderList($search = array() , $length = 20 , $offset = 0){
        $sql = "SELECT
                    o.id,
                    o.order_num,
                    o.total,
                    o.deliver,
                    o.out_points,
                    o.in_points,
                    o.status,
                    o.store_id,
                    o.user,
                    o.consignee,
                    o.consignee_tel,
                    ap.id as province_id,
                    ap.name as province,
                    aci.id as city_id,
                    aci.name as city,
                    aco.id as county_id,
                    aco.name as county,
                    o.consignee_address,
                    o.remark,
                    o.refund_reason,
                    o.created_at,
                    o.pay_type_name,
                    
                    u.nick_name,
                    u.true_name,
                    u.mobile,
                    u.avatar
                    
                FROM $this->_orders_table AS o";

        $sql .= " LEFT JOIN areas as ap ON ap.id = o.consignee_province";
        $sql .= " LEFT JOIN areas as aci ON aci.id = o.consignee_city";
        $sql .= " LEFT JOIN areas as aco ON aco.id = o.consignee_county";
        $sql .= " LEFT JOIN users as u ON u.id = o.user";

        $sql .= " WHERE 1=1";

        if(isset($search['status'])){
            $sql .= " AND o.status IN (".implode(',' , $search['status']).")";
        }
        if(isset($search['search']) && !empty($search['search'])){
            $sql .= " AND o.consignee LIKE '%" . $search['search'] . "%'" . " OR  o.consignee_tel LIKE '%" . $search['search'] . "%'" . " OR  o.order_num LIKE '%" . $search['search'] . "%'";
        }

//        $sql .= " AND store_id = $storeId ";

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
    public function changeStatus($userId , $id , $status){

        $isChange = DB::table($this->_orders_table)->where('id' , $id)->update(array('status' => $status));

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

}
