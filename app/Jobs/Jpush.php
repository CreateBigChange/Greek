<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use JPush as JpushLib;
use App\Libs\BLogger;

class Jpush extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $app_key;
    private $master_secret;
    private $jpushObj;

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
    public function __construct( $app_key , $master_secret , $platform , $alias , $tag , $content , $title , $sound)
    {
        $this->app_key          = $app_key;
        $this->master_secret    = $master_secret;
        $this->jpushObj         = new JpushLib($this->app_key, $this->master_secret);

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
        BLogger::getLogger(BLogger::LOG_JPUSH)->notice(json_encode(1111));
        // 完整的推送示例,包含指定Platform,指定Alias,Tag,指定iOS,Android notification,指定Message等
        $push = $this->jpushObj->push();

        $push->setPlatform($this->platform);
        if('' != $this->alias){
            $push->addAlias($this->alias);
        }
        if(!empty($this->tag)){
            $push->addTag($this->tag);
        }

        $push->setNotificationAlert('急所需商家版')
            //->addAllAudience()
            ->addAndroidNotification($this->content, $this->title, 1, array("type"=>"new"))
            ->addIosNotification($this->content, $this->sound, '+1' , true, 'iOS ORDER NEW', array("type"=>"new"))
            ->setMessage($this->content, $this->title, 'type', array("type"=>"new"))
            ->setOptions(100000, 3600, null, false);

        $push->send();


        BLogger::getLogger(BLogger::LOG_JPUSH)->notice(json_encode($push));

        return true;
    }

    public function failed(){
        var_dump(111);
    }
}
