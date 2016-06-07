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
        $jpush = new Jpush();
        return $jpush->push($this->content , $this->title , $this->platform , $this->alias);
    }

    public function failed(){
        var_dump(111);
    }
}
