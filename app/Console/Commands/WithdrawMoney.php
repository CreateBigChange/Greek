<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB , Config , Mail;
use App\Models\Order;
use App\Models\StoreConfig;
use App\Models\StoreInfo;
use Mockery\CountValidator\Exception;

use App\Libs\BLogger;
use NoahBuscher\Macaw\Macaw;

class WithdrawMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdraw-money';

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
        //$day  = date("Y-m-d",strtotime("-3 day"));
        $day  = date("Y-m-d H:i:s", time());

        $order = DB::table($orderModel->getTable())
            ->where('updated_at' , '<' , $day)
            ->whereIn('status' , $status)
            ->get();

        $orderIds       = array();
        $storeMoney     = array();
        $storeIds       = array();
        $storeOrderId   = array();
        foreach ($order as $o){
            $orderIds[] = $o->id;

            $storeIds[] = $o->store_id;

            if(isset($storeOrderId[$o->store_id])){
                $storeOrderId[$o->store_id][] = $o->id;
            }else{
                $storeOrderId[$o->store_id] = array();
                $storeOrderId[$o->store_id][] = $o->id;
            }
            /**
             * 店铺收入
             */
            if(isset($storeMoney[$o->store_id])) {
                $storeMoney[$o->store_id] += $o->pay_total;
            }else{
                $storeMoney[$o->store_id] = $o->pay_total;
            }
        }

        $storeIds = array_unique($storeIds);

        foreach ($storeIds as $s){
            $storeInfoModel = new StoreInfo();
            $storeInfo = DB::table($storeInfoModel->getTable())->where('id' , $s)->first();

            DB::beginTransaction();
            try {

                $storeConfig = DB::table($storeConfigModel->getTable())->where('store_id' , $s)->first();

                if(!$storeConfig){
                    continue;
                }

                $balanceMoney = bcsub($storeConfig->balance , $storeMoney[$s] , 2);

                if($balanceMoney < 0){
                    
                    $emailContent = "店铺余额  $balanceMoney "."店铺可提现金额" . $storeMoney[$s] . ", 本次处理的订单ID". implode(',' , $storeOrderId[$s]);
                    $email = "wuhui904107775@qq.com";
                    $name = "吴辉";
                    $storeName = $storeInfo->name;
                    $data = ['email'=>$email, 'name'=>$name , 'storeName' => $storeName];
                    Mail::raw($emailContent, function($message) use($data)
                    {
                        $message->from('zxhy201510@163.com', "正兴宏业");
                        $message->to($data['email'], $data['name'])->subject($data['storeName']);
                    });
                    continue;
                }

                /**
                 * 更新店铺可提现金额
                 */
                $storeMoney[$s] += $storeConfig->money;
                DB::table($storeConfigModel->getTable())->where('store_id' , $s)->update(array('money' => $storeMoney[$s] , 'balance' => $balanceMoney));
                /**
                 * 更新订单状态
                 */
                DB::table($orderModel->getTable())->where('store_id', $s)->whereIn('id' , $storeOrderId[$s])->update(array('status' => Config::get('orderstatus.withdrawMoney')['status']));



                $emailContent = "店铺余额  $balanceMoney "."店铺可提现金额" . $storeMoney[$s] . ", 本次处理的订单ID". implode(',' , $storeOrderId[$s]);

                BLogger::getLogger(BLogger::LOG_RESPONSE)->info($emailContent);

                $email = "wuhui904107775@qq.com";
                $name = "吴辉";
                $storeName = $storeInfo->name;
                $data = ['email'=>$email, 'name'=>$name , 'storeName' => $storeName];
                Mail::raw($emailContent, function($message) use($data)
                {
                    $message->from('zxhy201510@163.com', "正兴宏业");
                    $message->to($data['email'], $data['name'])->subject($data['storeName']);
                });

                DB::commit();

            }catch (Exception $e){
                DB::rollBack();
            }
        }

    }
}
