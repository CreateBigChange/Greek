<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Libs\Jpush as JpushLib;
use App\Libs\BLogger;
use Log;

class Jpush extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $platform;
    private $alias;
    private $tag;
    private $sound;
    private $type;
    private $sendFrom;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $platform='all' , $alias='' , $tag=array() , $sound='default' , $type = 'new' , $sendFrom = 'commercial')
    {
        $this->platform         = $platform;
        $this->alias            = $alias;
        $this->tag              = $tag;
        $this->sound            = $sound;
        $this->type             = $type;
        $this->sendFrom         = $sendFrom;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->attempts() > 3) {
            return true;
        }
        $jpush = new JpushLib($this->sendFrom);

        if($this->type == 'new'){
            $content = "急所需有新订单啦,请及时处理";
            $title = "急所需";
        }elseif($this->type == 'accident'){
            $content = "急所需有退款订单,请及时处理";
            $title = "急所需";
        }elseif($this->type == 'ontheway') {
            $content = "你有一个订单正在配送中";
            $title = "急所需";
        }elseif($this->type == 'refunded_success') {
            $content = "你有一个退款成功的订单";
            $title = "急所需";
        }elseif($this->type == 'withdraw') {
            $content = "您的提现申请状态已改变,请去提现列表查看";
            $title = "急所需";
        }


        return $jpush->push($content , $title , $this->platform , $this->alias , $this->tag , $this->sound , $this->type);
    }

    public function failed(){
        var_dump(111);
    }
}
