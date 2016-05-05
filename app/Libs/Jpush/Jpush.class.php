<?php
namespace App\Libs\Jpush;

use App\Libs\Jpush\Src\Jpush as JpushLib;

class Jpush
{
    private $app_key = '6bab168dd725bcff4c83e6f6';
    private $master_secret = '9973f83c178d57b8ccc67943';

    public function push(){
        $client = new JpushLib($this->app_key, $this->master_secret);
        $result = $client->push()
            ->setPlatform('all')
            ->addAllAudience()
            ->setNotificationAlert('wuhui')
            ->send();

        return json_encode($result);
    }
}