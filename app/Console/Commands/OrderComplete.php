<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB , Config;
use App\Models\Order;
use Mockery\CountValidator\Exception;

use App\Libs\BLogger;

class OrderComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将过了24小时的已送达的订单状态置为完成状态';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orderModel         = new Order();

        BLogger::getLogger(BLogger::LOG_SCRIPT)->info("准备将已满24小时并且还是已到达状态的订单改为完成状态");

        $status = array(
            Config::get('orderstatus.arrive')['status'],
        );

        /**
         * 获取24小时的订单
         */
        $day  = date("Y-m-d H:i:s", time() - (24 * 3600));

        $order = DB::table($orderModel->getTable())
            ->where('updated_at' , '<' , $day)
            ->whereIn('status' , $status)
            ->get();

        $orderIds = array();
        foreach ($order as $o){
            $orderIds[] = $o->id;
        }

        if(empty($orderIds)){
            BLogger::getLogger(BLogger::LOG_SCRIPT)->info("没有已满24小时并且还是已到达状态的订单");
        }
        /**
         * 更新订单状态
         */
        if(DB::table($orderModel->getTable())->whereIn('id', $orderIds)->update(array('status' => Config::get('orderstatus.completd')['status']))){
            BLogger::getLogger(BLogger::LOG_SCRIPT)->info("将订单改为".implode(' , ' ,$orderIds)."完成状态".Config::get('orderstatus.completd')['status'] );
        }

    }
}
