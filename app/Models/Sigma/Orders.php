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
use App\Libs\BLogger;

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
                    o.consignee_id,
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

            $o->payTotal            = $o->total + $o->deliver - ($o->in_points / 100);
            $o->inPointsToMoney     = $o->in_points / 100;

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


        //店铺是否休息
        if($storeInfo->is_close){
            return false;
        }

        //计算订单总价
        $total = 0;
        $outPoints = 0;
        foreach ($goodsList as $g){
            $total += (float) $g->out_price * $nums[$g->id];
            $outPoints += (int) $g->give_points * $nums[$g->id];
        }

        //店铺积分是否充足
        if($storeInfo->point < $outPoints){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice('店铺积分不足');
            return false;
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
            'out_points'            => $outPoints,
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
            $order['consignee_street']      = $address[0]->street;
            $order['pay_total']             = $total + $storeInfo->deliver;
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
    public function confirmOrder($userId , $orderId , $payType , $inPoints){
        /**
         * 查看是否有选择的支付方式
         */
        $payType = DB::table($this->_pay_type_table)->where('id' , $payType)->first();

        if(!$payType){
            return Message::setResponseInfo('FAILED');
        }

        $userModel = new Users;

        /**
         * 判断用户余额是否充足
         */
        $isAmplePoint =$userModel->isAmplePoint($userId , $inPoints);
        if($isAmplePoint === false){
            return Message::setResponseInfo('POINT_NOT_AMPLE');
        }

        $update = array(
            'in_points'     => $inPoints,
            'pay_type_id'   => $payType->id,
            'pay_type_name' => $payType->name,
            'updated_at'    => date('Y-m-d H:i:s' , time())
        );

        $order = DB::table($this->_orders_table)->where('id' , $orderId)->first();
        if(!$order){
            return Message::setResponseInfo('FAILED');
        }

        $storeModel = new Stores;
        $storeInfo  = $storeModel->getStoreInfo($order->store_id);
        //店铺积分是否充足
        if($storeInfo->point < $order->out_points){
            return Message::setResponseInfo('FAILED');
        }

        /**
         * 订单是否填写了收货地址
         */
        if(!$order->consignee_id){
            return Message::setResponseInfo('EMPTY_CONSIGNEE');
        }

        //计算需要支付的数量
        $payNum = $order->total + $order->deliver - ($inPoints / 100);

        if($payNum < 0){
            return Message::setResponseInfo('FAILED');
        }

        $update['pay_total']    = $payNum;

        if(DB::table($this->_orders_table)->where('user' , $userId)->where('id' , $orderId)->update($update)){
            return Message::setResponseInfo('SUCCESS' , $payNum);
        }else{
            return Message::setResponseInfo('FAILED');
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
    public function pay($orderId , $payMoney=0 , $payType=1 , $payTime=0){

        $payType = DB::table($this->_pay_type_table)->where('id' , $payType)->first();

        if(!$payType){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----支付订单失败-支付方式不对');
            $this->createOrderLog($orderId, 0 , '普通用户', '用户端APP', '支付订单失败-支付方式不对');
            return Message::setResponseInfo('FAILED');
        }

        $order = DB::table($this->_orders_table)->where('id' , $orderId)->first();
        if(!$order){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----没有此订单');
            return Message::setResponseInfo('FAILED');
        }

        //如果订单状态不是未支付状态,就不能再支付了
        if( $order->status != Config::get('orderstatus.no_pay')['status']){
            return Message::setResponseInfo('FAILED');
        }

        if(!$order->consignee_id){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----没有添加收货地址');
            return Message::setResponseInfo('EMPTY_CONSIGNEE');
        }

        $userId = $order->user;

        $userModel = new Users;

        //用户积分是否充足
        $isAmplePoint =$userModel->isAmplePoint($userId , $order->in_points);

        if($isAmplePoint === false){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----用户积分不足');
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-积分不足');
            return Message::setResponseInfo('POINT_NOT_AMPLE');
        }

        //计算需要支付的数量
        $payNum = $order->total + $order->deliver - ($order->in_points / 100);

//        if ($payMoney != $payNum) {
//            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-支付的金额与需要支付的金额不等');
//            return Message::setResponseInfo('MONEY_NOT_EQUAL');
//        }

        //如果是余额支付
        if($payType->id == 3) {
            //用户余额是否充足
            $isAmpleMoney = $userModel->isAmpleMoney($userId, $payNum);
            if ($isAmpleMoney === false) {
                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----用户余额不足');
                $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-余额不足');
                return Message::setResponseInfo('MONEY_NOT_AMPLE');
            }
        }

        $storeModel = new Stores;
        $storeInfo = $storeModel->getStoreInfo($order->store_id);

        //店铺积分是否充足
        if($storeInfo->point < $order->out_points){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----店铺积分不足');
            return Message::setResponseInfo('FAILED');
        }

        DB::beginTransaction();

        try {
            //加上本次订单赠送的积分
            $isAmplePoint += $order->out_points;

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice('###################');
            //更新用户积分
            $userModel->updatePoint($userId, $isAmplePoint);
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----更新用户积分成功,当前积分为' . $isAmplePoint);

            //更新店铺积分
            $storeModel->updatePoint($order->store_id, ($storeInfo->point - $order->out_points));

            //如果是余额支付
            if($payType->id == 3) {
                //更新用户余额
                $userModel->updateMoney($userId, $isAmpleMoney);
                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----更新用户余额成功,当前余额为' . $isAmpleMoney);
                $payTime = date('Y-m-d H:i:s' , time());
            }

            //需要更新的订单信息
            $update = array(
                'status'        => Config::get('orderstatus.paid')['status'],
                'updated_at'    => date('Y-m-d H:i:s' , time()),
                'pay_time'      => $payTime
            );

            //更新订单状态
            DB::table($this->_orders_table)->where('user', $userId)->where('id', $orderId)->update($update);
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----支付成功' );
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单成功' , $update['status']);

            DB::commit();

            return Message::setResponseInfo('SUCCESS' , array('points'=>$isAmplePoint , 'money'=>isset($isAmpleMoney)? $isAmpleMoney : 0));

        }catch (Exception $e){
            DB::rollBack();

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----支付失败');
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($e);
            $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败');
            return Message::setResponseInfo('FAILED');
        }

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
    public function createOrderLog($orderId , $userId , $identity , $platform , $log , $status = 0 ){

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
    public function getOrderLog( $orderId ){

        $logs = DB::table($this->_order_logs_table)->where('order_id' , $orderId)->orderBy('created_at' , 'asc')->get();

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
            DB::table($this->_orders_table)->where('id' , $data['order_id'])->update(array('is_evaluate' => 1 , 'status' => 1));
            $this->createOrderLog($data['order_id'], $data['user_id'], '普通用户', '用户端APP', '订单评价完成' , Config::get('orderstatus.completd')['status']);
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
    public function refund($userId , $orderId , $content ){
        $this->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '订单申请退款' , Config::get('orderstatus.refunding')['status']);
        return DB::table($this->_orders_table)->where('user' , $userId)->where('id' , $orderId)->update(
            array(
                'refund_reason' => $content,
                'updated_at'    => date('Y-m-d H:i:s' , time()),
                'status'        => Config::get('orderstatus.refunding')['status']
            )
        );
    }

    /**
     * @param $orderId
     * @param $outTradeNo
     * @return mixed
     * 更新微信订单ID
     */
    public function updateOrderOutTradeNo($orderId , $outTradeNo){
        return DB::table($this->_orders_table)->where('id' , $orderId)->update(array('out_trade_no' => $outTradeNo));
    }

//    /**
//     * @param $orderId
//     * @param $outTradeNo
//     * @return mixed
//     * 更新支付状态
//     */
//    public function updateOrderHandle($orderId , $payTime){
//        if(DB::table($this->_orders_table)->where('id' , $orderId)->update(array('pay_time' => $payTime , 'status' => Config::get('orderstatus.paid')['status']))){
//            $log = array(
//                'order_id'      => $orderId,
//                'user'          => '',
//                'identity'      => '微信',
//                'platform'      => '手机端',
//                'log'           => '已支付',
//                'status'        => Config::get('orderstatus.paid')['status'],
//                'created_at'    => date('Y-m-d H:i:s' , time())
//            );
//            if(DB::table($this->_order_logs_table)->insert($log)){
//                return true;
//            }else{
//                return false;
//            }
//        }else{
//            return false;
//        }
//    }

    /**
     * @param $outTradeNo
     * @return mixed
     * 根据微信订单ID获取订单信息
     */
    public function getOrderByOutTradeNo($outTradeNo){
        return DB::table($this->_orders_table)->where('out_trade_no' , $outTradeNo)->first();
    }

}
