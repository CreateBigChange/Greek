<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB , Config;
use App\Models\Order;
use App\Models\StoreConfig;
use Mockery\CountValidator\Exception;

class WithdrawMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'WithdrawMoney';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '计算可提现金额';

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
        $status = array(
            Config::get('orderstatus.arrive')['status'],
            Config::get('orderstatus.completd')['status']
        );

        /**
         * 获取几天前的订单
         */
        //$day  = date("Y-m-d",strtotime("-1 day"));
        $day  = date("Y-m-d H:i:s", time());

        $order = DB::table($orderModel->getTable())
            ->where('updated_at' , '<' , $day)
            ->whereIn('status' , $status)
            ->get();

        $orderIds       = array();
        $storeMoney     = array();
        $storeIds       = array();
        foreach ($order as $o){
            $orderIds[] = $o->id;

            $storeIds[] = $o->store_id;
            /**
             * 店铺收入
             */
            if(isset($storeMoney[$o->store_id])) {
                $storeMoney[$o->store_id] += $o->pay_total;
            }else{
                $storeMoney[$o->store_id] = $o->pay_total;
            }
        }

        var_dump($storeIds);die;

        foreach ($storeIds as $s){
            DB::beginTransaction();
            try {

                $storeConfig = DB::table($storeConfigModel->getTable())->where('store_id' , $s)->first();

                if(!$storeConfig){
                    return false;
                }

                $balanceMoney = $storeConfig->balance - $storeMoney[$s];

                if($balanceMoney < 0){
                    return false;
                }

                /**
                 * 更新店铺可提现金额
                 */
                DB::table($storeConfigModel->getTable())->where('store_id' , $s)->update(array('money' => $storeMoney[$s] , 'balance' => $balanceMoney));
                /**
                 * 更新订单状态
                 */
                DB::table($orderModel->getTable())->where('store_id', $s)->whereIn('id' , $orderIds)->update(array('status' => Config::get('orderstatus.withdrawMoney')['status']));

                DB::commit();

            }catch (Exception $e){
                var_dump($e);
                DB::rollBack();
            }
        }

    }
}
