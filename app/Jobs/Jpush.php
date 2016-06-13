<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Libs\Jpush as JpushLib;
use App\Libs\BLogger;

class Jpush extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $platform;
    private $alias;
    private $tag;
    private $content;
    private $title;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $content , $title ,$platform='all' , $alias='' , $tag=array() , $sound='default')
    {
        $this->platform         = $platform;
        $this->alias            = $alias;
        $this->tag              = $tag;
        $this->content          = $content;
        $this->title            = $title;
        $this->sound            = $sound;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jpush = new JpushLib();

        BLogger::getLogger(BLogger::LOG_WECHAT_PAY)->notice($jpush);

        return $jpush->push($this->content , $this->title , $this->platform , $this->alias);
    }

    public function failed(){
        var_dump(111);
    }
}
