<?php
namespace App\Libs;

use JPush as JpushLib;
use Config;

class Jpush
{
    private $commercial_app_key         = '6bab168dd725bcff4c83e6f6';
    private $commercial_master_secret   = '9973f83c178d57b8ccc67943';

    private $user_app_key       = 'e208cdd0f07101f41bf56a0a';
    private $user_master_secret = 'ded59edde98cfc62f0acb00e';
    private $jpushObj;

    public function __construct($appType){
        if($appType == 'commercial') {
            $this->jpushObj = new JpushLib($this->commercial_app_key, $this->commercial_master_secret);
        }elseif($appType == 'user'){
            $this->jpushObj = new JpushLib($this->user_app_key, $this->user_master_secret);
        }

    }

    public function pushAll(){
        $result = $this->jpushObj->push()
            ->setPlatform('all')
            ->addAllAudience()
            ->setNotificationAlert('wuhui')
            ->send();

        return json_encode($result);
    }

    public function push( $content , $title ,$platform='all' , $alias='' , $tag=array() , $sound='default' , $type="new"){
        // 完整的推送示例,包含指定Platform,指定Alias,Tag,指定iOS,Android notification,指定Message等
        $push = $this->jpushObj->push();

        $push->setPlatform($platform);

        if('' != $alias){
            $push->addAlias($alias);
        }
        if(!empty($tag)){
            $push->addTag($tag);
        }

        $push->setNotificationAlert('急所需')
            //->addAllAudience()
            ->addAndroidNotification($content, $title, 1, array("type"=>$type))
            ->addIosNotification($content, $sound, "+1" , true, 'notice', array("type"=>$type))
            ->setMessage($content, $title, 'type', array("type"=>$type));
        if(Config::get('app.debug')) {
            $push->setOptions(null, 86400, null, false);
        }else{
            $push->setOptions(null, 86400, null, true);
        }

        $result = $push->send();

        return json_encode($result);
    }

}