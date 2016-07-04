<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB , Config;
use App\Models\Order;
use App\Models\StoreConfig;
use Mockery\CountValidator\Exception;

class OrderComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OrderComplete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单完成';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orderModel         = new Order();
        $storeConfigModel   = new StoreConfig();

        /**
         * 状态为已送达的
         */
        $status = Config::get('orderstatus.arrive')['status'];

        /**
         * 获取几天前的订单
         */
        $day  = date("Y-m-d H:i:s",strtotime("-3 day"));

        $order = DB::table($orderModel->getTable())
            ->where('updated_at' , '<' , $day)
            ->where('status' , $status)
            ->get();

        $orderIds       = array();
        $storeMoney     = array();
//        $storePoint     = array();
        $storeIds       = array();
        foreach ($order as $o){
            $orderIds[] = $o->id;

            $storeIds[] = $o->store_id;
            /**
             * 店铺收入
             */
            $storeMoney[$o->store_id] += $o->pay_total;
//            $storePoint[$o->store_id] += $o->in_point;
        }


        foreach ($storeIds as $s){
            DB::beginTransaction();
            try {

                $storeConfig = DB::table($storeConfigModel->getTable())->where('store_id' , $s)->first();

                if(!$storeConfig){
                    return false;
                }

//                /**
//                 * 计算店铺剩余的积分
//                 */
//                $balancePoint = $storeConfig->balance_point - $storePoint[$s];
//
//                /**
//                 * 如果店铺剩余的积分小于0
//                 */
//                if($balancePoint < 0){
//                    $storeMoney[$s] = $storeMoney[$s] + $balancePoint / 100;
//                    $balancePoint = 0;
//                }

                $balanceMoney = $storeConfig->balance - $storeMoney[$s];

                if($balanceMoney < 0){
                    return false;
                }

                /**
                 * 更新店铺可提现金额
                 */
                DB::table($storeConfigModel->getTable())->where('store_id' , $s)->update(array('money' => $storeMoney[$s]));
                /**
                 * 更新订单状态
                 */
                DB::table($orderModel->getTable())->where('store_id', $s)->update(array('status' => Config::get('orderstatus.completd')['status']));

                DB::commit();

            }catch (Exception $e){
                DB::rollBack();
            }
        }

        var_dump($order);die;
    }
}
