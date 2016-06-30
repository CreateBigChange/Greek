<?php
namespace App\Libs;

use JPush as JpushLib;

class Jpush
{
    private $app_key = '6bab168dd725bcff4c83e6f6';
    private $master_secret = '9973f83c178d57b8ccc67943';
    private $jpushObj;

    public function __construct(){
        $this->jpushObj = new JpushLib($this->app_key, $this->master_secret);

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

        if('' != ($alias)){
            $push->addAlias($alias);
        }
        if(!empty($tag)){
            $push->addTag($tag);
        }

        $push->setNotificationAlert('急所需商家版')
            //->addAllAudience()
            ->addAndroidNotification($content, $title, 1, array("type"=>$type))
            ->addIosNotification($content, $sound, "+1" , true, 'iOS ORDER NEW', array("type"=>$type))
            ->setMessage($content, $title, 'type', array("type"=>$type))
            ->setOptions(100000, 3600, null, false);

        $result = $push->send();

        return json_encode($result);
    }

}