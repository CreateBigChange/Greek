<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models\Sigma;

use DB , Config;
use Illuminate\Database\Eloquent\Model;
use App\Libs\Message;

use App\Models\Sigma\Stores;
use App\Models\Sigma\Users;
use Mockery\Exception;

class Orders extends Model
{
    protected $_orders_table            = 'orders';
    protected $_order_infos_table       = 'order_infos';
    protected $_order_logs_table        = 'order_logs';
    protected $_pay_type_table          = 'pay_type';
    protected $_order_complaints        = 'order_complaints';
    protected $_order_evaluates         = 'order_evaluates';


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
     * 获取订单状态
     * @param orderId   number
     */
    public function getOrderStatus($orderId){
        return DB::table($this->_orders_table)->select('status')->where('id' , $orderId)->first();

    }

    /**
     * 获取订单列表
     * @param storeId   number
     * @param search    array
     * @param length    number
     * @param offset    number
     */
    public function getOrderList($userId , $search = array() , $length = 20 , $offset = 0){
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
                    u.points,
                    u.money,
                    o.is_evaluate,
                    
                    si.name as sname,
                    si.contact_phone as smobile,
                    sc.store_logo as slogo
                    
                FROM $this->_orders_table AS o";

        $sql .= " LEFT JOIN areas as ap ON ap.id = o.consignee_province";
        $sql .= " LEFT JOIN areas as aci ON aci.id = o.consignee_city";
        $sql .= " LEFT JOIN areas as aco ON aco.id = o.consignee_county";
        $sql .= " LEFT JOIN store_infos as si ON si.id = o.store_id";
        $sql .= " LEFT JOIN store_configs as sc ON sc.store_id = o.store_id";
        $sql .= " LEFT JOIN users as u ON u.id = o.user";

        $sql .= " WHERE user = $userId";

        if(isset($search['status'])){
            $sql .= " AND o.status IN (".implode(',' , $search['status']).")";
        }
        if(isset($search['id'])){
            $sql .= " AND o.id = " . $search['id'];
        }

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
    public function changeStatus($storeId , $userId , $id , $status){

        $isChange = DB::table($this->_orders_table)->where('store_id' , $storeId)->where('id' , $id)->update(array('status' => $status));

        if($isChange){

            $log = array(
                'order_id'      => $id,
                'user'          => $userId,
                'identity'      => '商家管理员',
                'platform'      => '手机端',
                'log'           => '将订单'. $id . '的状态改为'.$status,
                'created_at'    => date('Y-m-d H:i:s' , time())
            );
            DB::table($this->_order_logs_table)->insert($log);
            return true;
        }else{
            return false;
        }

    }

    /**
     * @param $storeId
     * @param $userId
     * @param $goods
     * @return bool
     *
     * 初始化订单
     */

    public function initOrder($storeId , $userId , $goods){

        $goodsIds = array();
        $nums = array();

        //分开goods_id 和 数量
        foreach ($goods as $g){
            $goodsIds[] = $g->goods_id;

            //有可能一个ID写了两个数量!正常情况下这个是不会发生!
            if(isset($nums[$g->goods_id])) {
                $nums[$g->goods_id] += (int) $g->num;
            }else{
                $nums[$g->goods_id] = (int) $g->num;
            }
        }

        $storeModel = new Stores;

        $goodsList  = $storeModel->getStoreGoodsList(array('store_id'=>$storeId , 'ids' => $goodsIds));
        $storeInfo  = $storeModel->getStoreInfo($storeId);

        //计算订单总价
        $total = 0;
        $inPoints = 0;
        foreach ($goodsList as $g){
            $total += (int) $g->out_price * $nums[$g->id];
            $inPoints += (int) $g->give_points * $nums[$g->id];
        }

        $userModel = new Users;


        //生成订单基本信息的数据
        $order = array(
            'order_num'             => time() . mt_rand(1000 , 9999),
            'total'                 => $total,
            'store_id'              => $storeId,
            'user'                  => $userId,
            'deliver'               => $storeInfo->deliver,
            'status'                => Config::get('orderstatus.no_pay')['status'],
            'in_points'             => $inPoints,

            'updated_at'            => date('Y-m-d H:i:s' , time()),
            'created_at'            => date('Y-m-d H:i:s' , time())

        );

        /*
         * 地址要根据距离来算
         *
         */
        $address = $userModel->getConsigneeAddressByUserId($userId);

        if(!empty($address)){
            $order['consignee']             = $address[0]->consignee;
            $order['consignee_id']          = $address[0]->id;
            $order['consignee_tel']         = $address[0]->mobile;
            $order['consignee_province']    = $address[0]->province;
            $order['consignee_city']        = $address[0]->city;
            $order['consignee_county']      = $address[0]->county;
            $order['consignee_address']     = $address[0]->address;
        }

        //开始事物
        DB::beginTransaction();

        try {

            $orderId = DB::table($this->_orders_table)->insertGetId($order);

            if (!$orderId) {
                return false;
            }

            //生成订单详细信息的数据
            $orderInfo = array();
            $i = 0;
            foreach ($goodsList as $g) {
                $orderInfo[$i]['order_id'] = $orderId;
                $orderInfo[$i]['goods_id'] = $g->id;
                $orderInfo[$i]['c_id'] = $g->category_id;
                $orderInfo[$i]['b_id'] = $g->brand_id;
                $orderInfo[$i]['c_name'] = $g->category_name;
                $orderInfo[$i]['b_name'] = $g->brand_name;
                $orderInfo[$i]['name'] = $g->name;
                $orderInfo[$i]['img'] = $g->img;
                $orderInfo[$i]['out_price'] = $g->out_price;
                $orderInfo[$i]['give_points'] = $g->give_points;
                $orderInfo[$i]['spec'] = $g->spec;
                $orderInfo[$i]['num'] = $nums[$g->id];
                $orderInfo[$i]['created_at'] = date('Y-m-d H:i:s', time());
                $orderInfo[$i]['updated_at'] = date('Y-m-d H:i:s', time());

                $i++;
            }

            DB::table($this->_order_infos_table)->insert($orderInfo);
            DB::commit();

            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '创建订单' , Config::get('orderstatus.no_pay')['status']);
            return $orderId;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }
//        //提交事物
//        if() {
//
//        }else{
//            //失败事物回滚
//
//            return false;
//        }




    }

    /**
     * @param $userId
     * @param $orderId
     * @param $payType
     * @param $outPoints
     * @return array|bool|mixed
     *
     * 确认订单
     */
    public function confirmOrder($userId , $orderId , $payType , $outPoints){
        $payType = DB::table($this->_pay_type_table)->where('id' , $payType)->first();

        if(!$payType){
            return false;
        }

        $userModel = new Users;
        $isAmplePoint =$userModel->isAmplePoint($userId , $outPoints);

        if(!$isAmplePoint){
            return Message::setResponseInfo('POINT_NOT_AMPLE');
        }

        $update = array(
            'out_points'    => $outPoints,
            'pay_type_id'   => $payType->id,
            'pay_type_name' => $payType->name,
            'updated_at'    => date('Y-m-d H:i:s' , time())
        );

        $order = DB::table($this->_orders_table)->where('id' , $orderId)->first();

        //计算需要支付的数量
        $payNum = $order->total + $order->deliver - ($outPoints / 100);

        if(DB::table($this->_orders_table)->where('user' , $userId)->where('id' , $orderId)->update($update)){
            return $payNum;
        }else{
            return false;
        }
    }


    /**
     * @param $userId
     * @param $orderId
     * @param $payMoney
     * @param $payType
     * @return array|bool|mixed
     * 支付
     */
    public function pay($userId , $orderId , $payMoney , $payType){
        $payType = DB::table($this->_pay_type_table)->where('id' , $payType)->first();

        if(!$payType){
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-支付方式不对');
            return false;
        }

        $order = DB::table($this->_orders_table)->where('id' , $orderId)->first();

        $userModel = new Users;
        $isAmplePoint =$userModel->isAmplePoint($userId , $order->outPoints);

        if(!$isAmplePoint){
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-积分不足');
            return Message::setResponseInfo('POINT_NOT_AMPLE');
        }

        $update = array(
            'status'        => Config::get('orderstatus.paid'),
            'updated_at'    => date('Y-m-d H:i:s' , time())
        );

        //计算需要支付的数量
        $payNum = $order->total + $order->deliver - ($order->outPoints / 100);

        if($payMoney != $payNum){
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-支付的金额与需要支付的金额不等');
            return Message::setResponseInfo('MONEY_NOT_EQUAL');
        }

        $isAmpleMoney =$userModel->isAmpleMoney($userId , $payNum);
        if(!$isAmpleMoney){
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-余额不足');
            return Message::setResponseInfo('MONEY_NOT_AMPLE');
        }

        DB::beginTransaction();

        try {
            //更新用户积分和余额
            $userModel->updatePoint($userId, $order->outPoints);
            $userModel->updateMoney($userId, $isAmpleMoney);

            //更新订单状态
            DB::table($this->_orders_table)->where('user', $userId)->where('id', $orderId)->update($update);
            DB::commit();

            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单成功' , $update['status']);
            return Message::setResponseInfo('SUCCESS');
        }catch (Exception $e){
            DB::rollBack();
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败');
            return Message::setResponseInfo('FAILED');
        }

//        if(){
//            return Message::setResponseInfo('SUCCESS' , $payNum);
//        }else{
//            return Message::setResponseInfo('FAILED');
//        }
    }

    /**
     * @param $userId
     * @param $orderId
     * @param $addressId
     * @return bool
     * 更新订单地址
     */
    public function updateOrderAddress($userId , $orderId , $addressId){
        $userModel = new Users;
        $address = $userModel->getConsigneeAddressById($addressId);

        if(!$address){
            return false;
        }

        $data = array(
            'consignee'             => $address->consignee,
            'consignee_id'          => $address->id,
            'consignee_tel'         => $address->mobile,
            'consignee_province'    => $address->province,
            'consignee_city'        => $address->city,
            'consignee_county'      => $address->county,
            'consignee_address'     => $address->address,
            'updated_at'            => date('Y-m-d H:i:s' , time())
        );

        return DB::table($this->_orders_table)->where('user' , $userId)->where('id' , $orderId)->update($data);
    }

    /**
     * @param $orderId
     * @param $userId
     * @param $identity
     * @param $platform
     * @param $log
     * @return bool
     * 创建订单log
     */
    public function createOrderLog($orderId , $userId , $identity , $platform , $log , $status){

        $statusChangeLog = Config::get('orderstatus.no_pay');
        $statusChangeLog['updated_at']      = date('Y-m-d H:i:s' , time());
        $statusChangeLog['created_at']      = date('Y-m-d H:i:s' , time());
        $statusChangeLog['user']            = $userId;
        $statusChangeLog['identity']        = $identity;
        $statusChangeLog['platform']        = $platform;
        $statusChangeLog['log']             = $log;
        $statusChangeLog['order_id']        = $orderId;
        $statusChangeLog['status']          = $status;

        if(DB::table($this->_order_logs_table)->insert($statusChangeLog)){

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
    public function getOrderLog($userId , $orderId ){

        $logs = DB::table($this->_order_logs_table)->where('user' , $userId)->where('order_id' , $orderId)->orderBy('created_at' , 'asc')->get();

        return $logs;

    }

    /**
     * @param $data
     * @return mixed
     * 投诉
     */
    public function complaint($data){
        return DB::table($this->_order_complaints)->insert($data);
    }

    /**
     * @param $data
     * @return mixed
     * 评价
     */
    public function evaluate($data){
        if(DB::table($this->_order_evaluates)->insert($data)){
            DB::table($this->_orders_table)->where('id' , $data['order_id'])->update(array('is_evaluate' => 1));
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $userId
     * @param $orderId
     * @param $content
     * @return mixed
     * 退款原因
     */
    public function refundReason($userId , $orderId , $content ){
        return DB::table($this->_orders_table)->where('user' , $userId)->where('id' , $orderId)->update(
            array(
                'refund_reason' => $content,
                'updated_at'    => date('Y-m-d H:i:s' , time())
            )
        );
    }
}
