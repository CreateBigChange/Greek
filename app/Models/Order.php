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

use Config , Log , RedisClass as Redis;

use Mockery\Exception;
use App\Libs\BLogger;
use App\Libs\Message;

use App\Models\OrderInfo;
use App\Models\OrderLog;
use App\Models\PayType;
use App\Models\StoreInfo;
use App\Models\StoreConfig;
use App\Models\StoreGoods;
use App\Models\ConsigneeAddress;
use App\Models\User;

class Order extends Model{

    protected $table = 'orders';

    /**
     * 获取订单总数
     * @param storeId   number
     * @param search    array
     */
    public function getOrderTotalNum($search){
        $sql = DB::table($this->table);
        if(isset($search['user'])){
            $sql->where('user' , $search['user']);
        }
        if(isset($search['store'])){
            $sql->where('store_id' , $search['store']);
        }
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
    public function getOrderList( $search = array() , $length = 20 , $offset = 0){
        $sql = "SELECT
                    o.id,
                    o.order_num,
                    o.total,
                    o.deliver,
                    o.status,
                    o.store_id,
                    o.user,
                    o.consignee,
                    o.consignee_id,
                    o.consignee_tel,
                    o.consignee_province as province ,
                    o.consignee_city as city,
                    o.consignee_county as county,
                    o.consignee_street as street,
                    o.consignee_address,
                    o.remark,
                    o.refund_reason,
                    o.created_at,
                    o.updated_at,
                    u.points,
                    u.money,
                    u.nick_name,
                    u.true_name,
                    u.mobile,
                    o.is_evaluate,
                    o.pay_total,
                    o.out_trade_no,
                    o.pay_type_id,
                    o.pay_type_name,
                    o.trade_no,
                    o.transaction_id,
                    
                    si.name as sname,
                    si.contact_phone as smobile,
                    sc.store_logo as slogo
                    
                FROM $this->table AS o";

//        $sql .= " LEFT JOIN areas as ap ON ap.id = o.consignee_province";
//        $sql .= " LEFT JOIN areas as aci ON aci.id = o.consignee_city";
//        $sql .= " LEFT JOIN areas as aco ON aco.id = o.consignee_county";
        $sql .= " LEFT JOIN store_infos as si ON si.id = o.store_id";
        $sql .= " LEFT JOIN store_configs as sc ON sc.store_id = o.store_id";
        $sql .= " LEFT JOIN users as u ON u.id = o.user";

        $sql .= " WHERE 1 = 1";

        if(isset($search['user'])){
            $sql .= " AND o.user = ".$search['user'];
        }
        if(isset($search['store'])){
            $sql .= " AND o.store_id = ".$search['store'];
        }

        if(isset($search['status'])){
            $sql .= " AND o.status IN (".implode(',' , $search['status']).")";
        }
        if(isset($search['id'])){
            $sql .= " AND o.id = " . $search['id'];
        }

        if(isset($search['search']) && !empty($search['search'])){
            $sql .= " AND o.consignee LIKE '%" . $search['search'] . "%'" . " OR  o.consignee_tel LIKE '%" . $search['search'] . "%'" . " OR  o.order_num LIKE '%" . $search['search'] . "%'";
        }

        $sql .= " GROUP BY o.id";
        $sql .= " ORDER BY o.updated_at DESC , o.created_at DESC";
        $sql .= " LIMIT $offset , $length";

        $orders = DB::select($sql);

        $orderIds = array();
        foreach ($orders as $o){
            $orderIds[] = $o->id;
        }

        $goods = OrderInfo::whereIn('order_id' , $orderIds)->get();

        foreach ($orders as $o){
            $o->goods = array();
            $o->goodsNum = 0;

            //实际需要支付的数目
//            $o->payTotal            = round($o->total + $o->deliver - ($o->in_points / 100) , 2);
            $o->payTotal            = round($o->total + $o->deliver);

            //不算积分需要支付的数目
            $o->total               = $o->total + $o->deliver;

            //$o->inPointsToMoney     = $o->in_points / 100;

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
     * 获取订单状态
     * @param orderId   number
     */
    public function getOrderStatus($orderId){
        return DB::table($this->table)->select('status')->where('id' , $orderId)->first();

    }

    /**
     * 更改订单状态
     * @param storeId   number
     * @param id        number
     * @param status    number
     */
    public function changeStatus($storeId , $userId , $orderId , $status){

//        $isUpdateUserPoint  = 0;
        $isUpdateStoreMoney = 0;
//        $isUpdateStorePoint = 0;

        DB::beginTransaction();
        try {

            if ($status == Config::get('orderstatus.arrive')['status']) {

                $orderInfo = DB::table($this->table)->where('id', $orderId)->first();

                /**
                 * ****************************************************
                 * 将此订单店铺应得的钱增加到商户账号上
                 * ****************************************************
                 */
                $storeModel         = new StoreInfo();
                $storeConfigModel   = new StoreConfig();
                $storeInfo = $storeModel->getStoreInfo($storeId);
                if(!$storeInfo){
                    return false;
                }
                $balance = $storeInfo->balance + $orderInfo->pay_total;
                $storeConfigModel->updateBalance($storeId, $balance);

                /**
                 * ************************************************a****
                 * 将此订单店铺应得的积分增加到商户账号上
                 * ****************************************************
                 */
//                $storeConfigModel->updateBalancePoint($storeId, ($storeInfo->balance_point + $orderInfo->in_points));

//                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice('店铺余额更新成功' . $storeId . '----'.$balance);

                /**
                 * ****************************************************
                 * 将订单赠送的积分增加到用户账号上
                 * ****************************************************
                 */
//                $userModel = new User;
//                $userInfo = $userModel->getUserInfoById($userId);
//
//                if (!$orderInfo || !$userInfo) {
//                    return false;
//                }
//                $point = $userInfo->points + $orderInfo->out_points;
//                $userModel->updatePoint($userId, $point);

                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice('用户积分发放成功' . $userId . '----'.$point);

//                $isUpdateUserPoint  = 1;
                $isUpdateStoreMoney = 1;
//                $isUpdateStorePoint = 1;

            }

            /**
             * ****************************************************
             * 更新订单信息
             * ****************************************************
             */
            DB::table($this->table)->where('store_id', $storeId)->where('id', $orderId)->update(
                array(
                    'status'                    => $status,
//                    'is_update_user_point'      => $isUpdateUserPoint,
                    'is_update_store_money'     => $isUpdateStoreMoney,
//                    'is_update_store_point'     => $isUpdateStorePoint
                )
            );



            $orderLog = new OrderLog;

            $orderLog->order_id     = $orderId;
            $orderLog->user         = $userId;
            $orderLog->identity     = '商家管理员';
            $orderLog->platform     = '手机端';
            $orderLog->log          = '将订单' . $orderId . '的状态改为' . $status;
            $orderLog->status       = $status;

            $orderLog->save();

            DB::commit();
            return true;
        }catch (Exception $e){
            DB::rollBack();

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

        $storeGoodsModel = new StoreGoods();
        $storeInfoModel = new StoreInfo();

        $goodsList  = $storeGoodsModel->getStoreGoodsList(array('store_id'=>$storeId , 'ids' => $goodsIds));
        $storeInfo  = $storeInfoModel->getStoreInfo($storeId);


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

//        //店铺积分是否充足
//        if($storeInfo->point < $outPoints){
//            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice('店铺积分不足');
//            return false;
//        }

        $consigneeAddressModel  = new ConsigneeAddress();

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
            'created_at'            => date('Y-m-d H:i:s' , time()),
            'year'                  => date('Y' , time()),
            'month'                 => date('m' , time()),
            'day'                   => date('d' , time()),
            'hour'                  => date('H' , time()),
            'minutes'               => date('i' , time()),
            'second'                => date('s' , time())

        );

        /*
         * 地址要根据距离来算
         *
         */
        $address = $consigneeAddressModel->getConsigneeAddressByUserId($userId);

        if(!empty($address)){
            $order['consignee']             = $address[0]->consignee;
            $order['consignee_id']          = $address[0]->id;
            $order['consignee_tel']         = $address[0]->mobile;
            $order['consignee_province']    = $address[0]->province;
            $order['consignee_city']        = $address[0]->city;
            $order['consignee_county']      = $address[0]->county;
            $order['consignee_street']      = $address[0]->street;
            $order['consignee_address']     = $address[0]->address;
            $order['consignee_street']      = $address[0]->street;
            $order['pay_total']             = $total + $storeInfo->deliver;
        }

        //开始事物
        DB::beginTransaction();

        try {

            $orderId = DB::table($this->table)->insertGetId($order);

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

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderInfo);
            $orderInfoMode = new OrderInfo();
            DB::table($orderInfoMode->getTable())->insert($orderInfo);
            //OrderInfo::create($orderInfo);
            //DB::table($this->_order_infos_table)->insert($orderInfo);
            DB::commit();

            $orderLogMode = new OrderLog();
            $orderLogMode->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '创建订单' , Config::get('orderstatus.no_pay')['status']);
            return $orderId;
        }catch(Exception $e){

            DB::rollBack();
            return false;
        }

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
    public function confirmOrder($userId , $orderId , $payType ){
        /**
         * 查看是否有选择的支付方式
         */
        //$payType = DB::table($this->_pay_type_table)->where('id' , $payType)->first();
        $payType = PayType::where('id' , $payType)->first();

        if(!$payType){
            return Message::setResponseInfo('FAILED');
        }

//        $userModel = new User;

//        /**
//         * 判断用户余额是否充足
//         */
//        $isAmplePoint =$userModel->isAmplePoint($userId , $inPoints);
//        if($isAmplePoint === false){
//            return Message::setResponseInfo('POINT_NOT_AMPLE');
//        }

        $update = array(
            //'in_points'     => $inPoints,
            'pay_type_id'   => $payType->id,
            'pay_type_name' => $payType->name,
            'updated_at'    => date('Y-m-d H:i:s' , time())
        );

        $order = DB::table($this->table)->where('id' , $orderId)->first();
        if(!$order){
            return Message::setResponseInfo('FAILED');
        }

//        $storeModel = new StoreInfo;
//        $storeInfo  = $storeModel->getStoreInfo($order->store_id);
//        //店铺积分是否充足
//        if($storeInfo->point < $order->out_points){
//            return Message::setResponseInfo('FAILED');
//        }

        /**
         * 订单是否填写了收货地址
         */
        if(!$order->consignee_id){
            return Message::setResponseInfo('EMPTY_CONSIGNEE');
        }

        //计算需要支付的数量
        //$payNum = $order->total + $order->deliver - ($inPoints / 100);
        $payNum = $order->total + $order->deliver;

        $payNum = round($payNum , 2);

        if($payNum < 0){
            return Message::setResponseInfo('FAILED');
        }

        $update['pay_total']    = $payNum;

        if(DB::table($this->table)->where('user' , $userId)->where('id' , $orderId)->update($update)){
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
    public function pay($orderId , $payMoney=0 , $payType=1 , $payTime=0 , $wechatPayNum='' ,  $alipayOrderNum=''){

        $payType = PayType::where('id' , $payType)->first();
        $orderLogMode = new OrderLog();

        if(!$payType){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----支付订单失败-支付方式不对');
            $orderLogMode->createOrderLog($orderId, 0 , '普通用户', '用户端APP', '支付订单失败-支付方式不对');
            return Message::setResponseInfo('FAILED');
        }

        $order = DB::table($this->table)->where('id' , $orderId)->first();
        if(!$order){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----没有此订单');
            return Message::setResponseInfo('FAILED');
        }

        /**
         * *********************************
         * 如果订单状态不是未支付状态,就不能再支付了
         * *********************************
         */
        if( $order->status != Config::get('orderstatus.no_pay')['status']){
            return Message::setResponseInfo('FAILED');
        }

        if(!$order->consignee_id){
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----没有添加收货地址');
            return Message::setResponseInfo('EMPTY_CONSIGNEE');
        }

        $userId = $order->user;

        $userModel = new User;

        /**
         * *********************************
         * 用户积分是否充足
         * *********************************
         */
//        $isAmplePoint =$userModel->isAmplePoint($userId , $order->in_points);
//
//        if($isAmplePoint === false){
//            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----用户积分不足');
//            $orderLogMode->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-积分不足');
//            return Message::setResponseInfo('POINT_NOT_AMPLE');
//        }

        /**
         * *********************************
         * 计算需要支付的数量
         * *********************************
         */
        $payNum = $order->total + $order->deliver - ($order->in_points / 100);
        $payNum = round($payNum , 2);

        if ($payMoney != $payNum) {
            $orderLogMode->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-支付的金额与需要支付的金额不等');
            return Message::setResponseInfo('MONEY_NOT_EQUAL');
        }

        /**
         * *********************************
         * 如果是余额支付
         * *********************************
         */
//        if($payType->id == Config::get('paytype.money')) {
//            /**
//             * *********************************
//             * 用户余额是否充足
//             * *********************************
//             */
//            $isAmpleMoney = $userModel->isAmpleMoney($userId, $payNum);
//            if ($isAmpleMoney === false) {
//                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----用户余额不足');
//                $orderLogMode->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败-余额不足');
//                return Message::setResponseInfo('MONEY_NOT_AMPLE');
//            }
//        }

        $storeModel = new StoreInfo();
        $storeConfigModel = new StoreConfig();
        $storeInfo = $storeModel->getStoreInfo($order->store_id);

        /**
         * *********************************
         * 店铺积分是否充足
         * *********************************
         */
//        if($storeInfo->point < $order->out_points){
//            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----店铺积分不足');
//            return Message::setResponseInfo('FAILED');
//        }

        DB::beginTransaction();

        try {
            /**
             * *********************************
             * 扣除用户积分
             * *********************************
             */
//            $userModel->updatePoint($userId, $isAmplePoint);
//            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----扣除用户'.$userId.'积分成功,当前积分为' . $isAmplePoint);

            /**
             * *********************************
             * 扣除店铺积分
             * *********************************
             */
//            $storeConfigModel->updateBalancePoint($order->store_id, ($storeInfo->balance_point - $order->out_points));
//            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----扣除店铺'.$order->store_id.'积分成功,当前积分为' . ($storeInfo->point - $order->out_points));

            /**
             * *********************************
             * 如果是余额支付
             * *********************************
             */
//            if($payType->id == Config::get('paytype.money')) {
//                /**
//                 * *********************************
//                 * 更新用户余额
//                 * *********************************
//                 */
//                $userModel->updateMoney($userId, $isAmpleMoney);
//                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----更新用户余额成功,当前余额为' . $isAmpleMoney);
//                $payTime = date('Y-m-d H:i:s' , time());
//            }

            /**
             * *********************************
             * 需要更新的订单信息
             * *********************************
             */
            $update = array(
                'status'            => Config::get('orderstatus.paid')['status'],
                'updated_at'        => date('Y-m-d H:i:s' , time()),
                'pay_time'          => $payTime,
                'transaction_id'    => $wechatPayNum,
                'trade_no'          => $alipayOrderNum
            );

            /**
             * *********************************
             * 更新订单状态
             * *********************************
             */
            DB::table($this->table)->where('user', $userId)->where('id', $orderId)->update($update);
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----支付成功' );
            $orderLogMode->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单成功' , $update['status']);

            DB::commit();

            $storeId = $order->store_id;

//            /**
//             * **************************
//             * 添加新订单通知的数量
//             * **************************
//             */
//            $new =  Redis::get("store:$storeId:new") == null ? 0 : Redis::get("store:$storeId:new");
//            $new = $new + 1;
//            Redis::set("store:$order->store_id:new"  , $new );

            /**
             * *************************************
             * 增加商品销售数量以及减少库存
             * *************************************
             */
            $orderInfoModel = new OrderInfo();
            $orderInfo = DB::table($orderInfoModel->getTable())->where('order_id' , $orderId)->get();

            $storeGoodsModel = new StoreGoods();
            foreach ($orderInfo as $oi){
                DB::table($storeGoodsModel->getTable())->where('id' , $oi->goods_id)->increment('out_num' , $oi->num);
                DB::table($storeGoodsModel->getTable())->where('id' , $oi->goods_id)->decrement('stock' , $oi->num);
            }


//            return Message::setResponseInfo('SUCCESS' , array('points'=>$isAmplePoint , 'money'=>isset($isAmpleMoney)? $isAmpleMoney : 0));
            return Message::setResponseInfo('SUCCESS' , array('money'=>isset($isAmpleMoney)? $isAmpleMoney : 0));

        }catch (Exception $e){
            DB::rollBack();

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----支付失败');
            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($e);
            $orderLogMode->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '支付订单失败');
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
        $consigneeAddressModel  = new ConsigneeAddress();
        $address = $consigneeAddressModel->getConsigneeAddressById($addressId);

        if(!$address){
            return false;
        }

        $data = array(
            'consignee'                     => $address->consignee,
            'consignee_id'                  => $address->id,
            'consignee_tel'                 => $address->mobile,
            'consignee_province'            => $address->province,
            'consignee_city'                => $address->city,
            'consignee_county'              => $address->county,
            'consignee_address'             => $address->address,
            'consignee_street'              => $address->street,
            'updated_at'                    => date('Y-m-d H:i:s' , time())
        );

        return DB::table($this->table)->where('user' , $userId)->where('id' , $orderId)->update($data);
    }


    /**
     * @param $userId
     * @param $orderId
     * @param $content
     * @return mixed
     * 退款原因
     */
    public function refundReson($userId , $orderId , $content ){
        $orderLogMode = new OrderLog();
        $orderLogMode->createOrderLog($orderId, $userId, '普通用户', '用户端APP', '订单申请退款' , Config::get('orderstatus.refunding')['status']);
        return DB::table($this->table)->where('user' , $userId)->where('id' , $orderId)->update(
            array(
                'refund_reason' => $content,
                'updated_at'    => date('Y-m-d H:i:s' , time()),
                'status'        => Config::get('orderstatus.refunding')['status']
            )
        );
    }

    /**
     * 退款
     * @param storeId   number
     * @param id        number
     * @param status    number
     */
    public function refund($storeId , $orderId , $refundNo){

        $orderLogModel = new OrderLog();

        DB::beginTransaction();

        try {
            $order = DB::table($this->table)->where('store_id' , $storeId)->where('id' , $orderId)->first();
            if(!$order){
                return false;
            }

            /**
             * ***************************************************************************
             * 更新店铺积分
             * ***************************************************************************
             */
            $storeModel         = new StoreInfo();
            $storeConfigModel   = new StoreConfig();
            $storeInfo = $storeModel->getStoreInfo($storeId);
            if(!$storeInfo){
                return false;
            }

            if($order->out_points != 0) {
                $point = $storeInfo->balance_point + $order->out_points;

                $storeConfigModel->updateBalancePoint($storeId, $point);
                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----更新店铺积分 积分为' . $point);
            }

            /**
             * ***************************************************************************
             * 如果订单状态是已送达和已完成再退款的,需要返还用户积分和扣除店铺的余额
             * ***************************************************************************
             */
            //$orderLog = DB::table($orderLogModel->getTable())->where('order_id' , $orderId)->where('status' , Config::get('orderstatus.arrive')['status'])->orWhere('status' ,  Config::get('orderstatus.completd')['status'])->get();

            /**
             * ***************************************************************************
             * 添加用户积分
             * ***************************************************************************
             */
            $userInfo = DB::table('users')->where('id', $order->user)->first();
            /**
             * 加上用户消耗的积分
             */
//            $userPoint = $userInfo->points + $order->in_points;
//            if($userPoint < 0){
//                return false;
//            }

            /**
             * ***************************************************************************
             * 是否给用户发放了积分
             * ***************************************************************************
             */
//            if($order->is_update_user_point ) {
//                $userPoint = $userPoint - $order->out_points;
//            }
//            if ($userPoint != $userInfo->points) {
//                $userModel = new User();
//                DB::table($userModel->getTable())->where('id', $order->user)->update(array('points' => $userPoint));
//                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice('更新用户积分,积分为' . $userPoint);
//            }


            if($order->is_update_store_money){
                /**
                 * ***************************************************************************
                 * 扣除店铺余额
                 * ***************************************************************************
                 */
                $balance = $storeInfo->balance - $order->pay_total;
                $storeConfigModel->updateBalance($storeId, $balance);
                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----更新店铺余额 余额为'.$balance);
            }
//            if($order->is_update_store_point){
//
//                /**
//                 * ***************************************************************************
//                 * 扣除店铺积分
//                 * ***************************************************************************
//                 */
//                $point = $storeInfo->balance_point - $order->in_points + $order->out_points;
//                $storeConfigModel->updateBalancePoint($storeId, $point);
//                BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----更新店铺余额 余额为'.$point);
//            }


            DB::table($this->table)->where('id', $orderId)->update(array(
                'status' => Config::get('orderstatus.refunded')['status'],
                'refund_no' => $refundNo
            ));

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----更改订单状态'.Config::get('orderstatus.refunded')['status']);

            $log = array(
                'order_id' => $orderId,
                'user' => '',
                'identity' => '商家管理员',
                'platform' => '手机端',
                'log' => '将订单' . $orderId . '的状态改为' . Config::get('orderstatus.refunded')['status'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => Config::get('orderstatus.refunded')['status']
            );
            DB::table($orderLogModel->getTable())->insert($log);

            DB::commit();

            /**
             * *************************************
             * 增加减少销售数量以及增加库存
             * *************************************
             */
            $orderInfoModel = new OrderInfo();
            $orderInfo = DB::table($orderInfoModel->getTable())->where('order_id' , $orderId)->get();

            $storeGoodsModel = new StoreGoods();
            foreach ($orderInfo as $oi){
                DB::table($storeGoodsModel->getTable())->where('id' , $oi->goods_id)->decrement('out_num' , $oi->num);
                DB::table($storeGoodsModel->getTable())->where('id' , $oi->goods_id)->increment('stock' , $oi->num);
            }


            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($orderId . '----退款成功');
            return true;
        }catch (Exception $e){
            DB::rollBack();

            BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($e);
            return false;
        }

    }



    /**
     * @param $orderId
     * @param $outTradeNo
     * @return mixed
     * 更新微信订单ID
     */
    public function updateOrderOutTradeNo($orderId , $outTradeNo){
        return DB::table($this->table)->where('id' , $orderId)->update(array('out_trade_no' => $outTradeNo));
    }

    /**
     * @param $outTradeNo
     * @return mixed
     * 根据微信订单ID获取订单信息
     */
    public function getOrderByOutTradeNo($outTradeNo){
        return DB::table($this->table)->where('out_trade_no' , $outTradeNo)->first();
    }

    /**
     * @param $outTradeNo
     * @return mixed
     * 根据订单ID获取订单信息
     */
    public function getOrderById($userId , $orderId){
        return DB::table($this->table)->where('user' , $userId)->where('id' , $orderId)->first();
    }

    /**
     * @param $outTradeNo
     * @return mixed
     * 根据订单ID获取订单信息
     */
    public function getOrderByTradeNo($tradeNo){
        return DB::table($this->table)->where('trade_no' , $tradeNo)->first();
    }



    /**
     * 订单统计
     */
    /**
     * 订单统计数据(本天)
     */
    public function orderCountDay($storeId , $year , $month , $day){
        $sql = "SELECT 
                    count('id') as num ,
                    `hour` ,
                    `status`
               FROM $this->table";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] . ')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " AND day IN (" . $day .")";
        $sql .= " GROUP BY hour , status";
        $sql .= " ORDER BY hour ASC ";

        $count = DB::select($sql);

        return $count;

    }

    /**
     * 订单统计
     */
    /**
     * 订单统计数据(本周)
     */
    public function orderCountWeek($storeId , $year , $month , $day){
        $sql = "SELECT 
                    count('id') as num ,
                    `day` ,
                    `status`
               FROM $this->table";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] . ')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " AND day IN (" . $day .")";
        $sql .= " GROUP BY day , status";
        $sql .= " ORDER BY day ASC ";

        $count = DB::select($sql);

        return $count;

    }

    /**
     * 订单统计
     */
    /**
     * 订单统计数据(本月)
     */
    public function orderCountMonth($storeId , $year , $month){
        $sql = "SELECT 
                    count('id') as num ,
                    `day` ,
                    `status`
               FROM $this->table";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] . ')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " GROUP BY month , status";
        $sql .= " ORDER BY month ASC ";

        $count = DB::select($sql);

        return $count;

    }

    /**
     * 获取今日订单营业额统计
     */
    public function getOrderTodayCounts($storeId , $date=0){
        $sql = "SELECT 
                    count(`id`) as order_num , 
                    sum(`total`) as turnover
                FROM $this->table ";
        $sql .= " WHERE `store_id` = $storeId";
        $sql .= " AND `created_at` LIKE '" .$date . "%'";
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] . ',' . Config::get('orderstatus.refunded')['status'] .')';

        return DB::select($sql);

    }

    /**
     * 获取订单统计数据
     */
    public function getOrderCounts($storeId){
        $sql = "SELECT 
                    count(`id`) as order_num , 
                    sum(`total`) as turnover
                FROM $this->table ";
        $sql .= " WHERE `store_id` = $storeId";
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] . ',' . Config::get('orderstatus.refunded')['status'] .')';

        $user = DB::table($this->table)->select("user")->where('store_id' , $storeId)->whereNotIn('status' , array(
            Config::get('orderstatus.no_pay')['status'],
            Config::get('orderstatus.cancel')['status'],
            Config::get('orderstatus.refunded')['status']
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
                FROM $this->table ";
        $sql .= " WHERE `store_id` = $storeId";
        $sql .= " AND `created_at` LIKE '" .$date . "%'";
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] . ',' . Config::get('orderstatus.refunded')['status'] .')';

        return DB::select($sql);

    }

    /**
     * 店铺统计数据(本月)
     */
    public function financeCountByMonth($storeId , $year , $month){
        $sql = "SELECT 
                    sum(`total`) as turnover,
                    sum(`out_points`) as outPoint,
                    sum(`in_points`) as inPoint,
                    `day`
               FROM $this->table";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status']. ',' . Config::get('orderstatus.refunded')['status'] .')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " GROUP BY day ORDER BY day ASC ";

        $count = DB::select($sql);

        return $count;

    }

    /**
     * 店铺统计数据(本周)
     */
    public function financeCountByWeek($storeId , $year , $month , $day){
        $sql = "SELECT 
                    sum(`total`) as turnover,
                    sum(`out_points`) as outPoint,
                    sum(`in_points`) as inPoint,
                    `day`
               FROM $this->table ";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status'] . ',' . Config::get('orderstatus.refunded')['status'] .')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " AND day IN (" . $day .")";
        $sql .= "  GROUP BY day ORDER BY day ASC";

        $count = DB::select($sql);

        return $count;

    }

    /**
     * 店铺统计数据(本天)
     */
    public function financeCountByDay($storeId , $year , $month , $day){
        $sql = "SELECT 
                    `total` as turnover,
                    `out_points` as outPoint,
                    `in_points` as inPoint,
                    `hour`                    
               FROM $this->table";
        $sql .= " WHERE store_id = " . $storeId;
        $sql .= " AND status NOT IN (" . Config::get('orderstatus.no_pay')['status'] .',' . Config::get('orderstatus.cancel')['status']. ',' . Config::get('orderstatus.refunded')['status'] .')';
        $sql .= " AND year = " . $year;
        $sql .= " AND month IN (" . $month .")";
        $sql .= " AND day IN (" . $day .")";
        $sql .= " ORDER BY hour ASC ";

        $count = DB::select($sql);

        return $count;

    }

}
