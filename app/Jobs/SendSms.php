<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Libs\Smsrest\Sms;
use App\Models\Sigma\Users;
use Session;

class SendSms extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $mobile;
    protected $code;
    protected $templateId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $mobile , $code  , $templateId)
    {
        $this->mobile           = $mobile;
        $this->code             = $code;
        $this->templateId       = $templateId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms = new Sms;
        $isSend = $sms->sendTemplateSMS($this->mobile , array($this->code , '1') , $this->templateId);
        if($isSend->statusCode != 0){
            $userModel = new Users;
            $userModel->addSmsErrorLog($this->mobile , $isSend->statusMsg);
            return false;
        }
        Session::put("jsx_sms_$this->mobile" , $this->code);
        return true;
    }

    public function failed(){
        var_dump(111);
    }
}
