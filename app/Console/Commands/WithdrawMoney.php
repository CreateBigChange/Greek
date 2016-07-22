<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB , Config , Mail;
use App\Models\Order;
use App\Models\StoreConfig;
use App\Models\StoreInfo;
use Mockery\CountValidator\Exception;

use App\Libs\BLogger;

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
            Config::get('orderstatus.completd')['status']
        );

        /**
         * 获取当前时间完成的订单
         */
        $day  = date("Y-m-d H:i:s",time());
        BLogger::getLogger(BLogger::LOG_SCRIPT)->info($day);
        //$day  = date("Y-m-d H:i:s", time());

        $order = DB::table($orderModel->getTable())
            ->where('updated_at' , '<' , $day)
            ->whereIn('status' , $status)
            ->get();

        $orderIds       = array();
        $storeMoney     = array();
        $storeIds       = array();
        $storeOrderId   = array();

        /**
         * 扣点数
         */
        $storePoint     = array();

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
                $storeMoney[$o->store_id]   += $o->store_income;
                $storePoint[$o->store_id]   += $o->money_reduce_points;
            }else{
                $storeMoney[$o->store_id]   = $o->store_income;
                $storePoint[$o->store_id]   = $o->money_reduce_points;
            }
        }

        $storeIds = array_unique($storeIds);
        $emailContent = '';

        DB::beginTransaction();
        try {
            foreach ($storeIds as $s){
                $storeInfoModel = new StoreInfo();
                $storeInfo = DB::table($storeInfoModel->getTable())->where('id' , $s)->first();

                $storeConfig = DB::table($storeConfigModel->getTable())->where('store_id' , $s)->first();

                if(!$storeConfig){
                    continue;
                }

                $balanceMoney = bcsub( $storeConfig->balance , $storeMoney[$s] , 2 );

                /**
                 * 更新店铺可提现金额
                 */
                //可提现需要减去每笔订单扣点扣的钱数
                $storeMoney[$s] = bcsub( $storeMoney[$s], $storePoint[$s] , 2);
                $storeMoney[$s] += $storeConfig->money;

                $emailContent .= $storeInfo->name . "余额:$storeConfig->balance ,"."可提现金额:" . $storeMoney[$s] . "扣点:".$storePoint[$s] . " , 本次处理的订单ID:". implode(',' , $storeOrderId[$s]) . "\n";

                if($balanceMoney < 0){
                    continue;
                }


                DB::table($storeConfigModel->getTable())->where('store_id' , $s)->update(array('money' => $storeMoney[$s] ));
                /**
                 * 更新订单状态
                 */
                DB::table($orderModel->getTable())->where('store_id', $s)->whereIn('id' , $storeOrderId[$s])->update(array('status' => Config::get('orderstatus.withdrawMoney')['status']));


            }

            DB::commit();

            if($emailContent == ''){
                $emailContent = "本次结算没有处理的订单";
            }
            $email = Config::get('mail.to');
            $name = 'operations';
            $data = ['email'=>$email, 'name'=>$name , 'subject' => "计算可提现金额"];
            Mail::raw($emailContent, function($message) use($data){
                $message->to($data['email'], $data['name'])->subject($data['subject']);
            });
        }catch (Exception $e){
            DB::rollBack();
        }

    }
}