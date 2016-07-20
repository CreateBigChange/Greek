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
    private $content;
    private $title;
    private $sound;
    private $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $content , $title , $platform='all' , $alias='' , $tag=array() , $sound='default' , $type = 'new')
    {
        $this->platform         = $platform;
        $this->alias            = $alias;
        $this->tag              = $tag;
        $this->content          = $content;
        $this->title            = $title;
        $this->sound            = $sound;
        $this->type             = $type;
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
        $jpush = new JpushLib();

        return $jpush->push($this->content , $this->title , $this->platform , $this->alias , $this->tag , $this->sound , $this->type);
    }

    public function failed(){
        var_dump(111);
    }
}
