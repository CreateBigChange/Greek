<?php

namespace App\Libs\Smsrest;

use App\Libs\Smsrest\CCPRestSDK;
use Config;
use App\Libs\BLogger;

class Sms {

    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id
     */
    public function sendTemplateSMS($to,$datas,$tempId){

        $log = array(
            'phone'     => $to,
            'tempId'    => $tempId,
            'datas'     => $datas,
            'result'    => '',
            'time'      => date('Y-m-d H:i:s' , time())
        );

        // 初始化REST SDK
        $accountSid     = Config::get('sms.accountSid');
        $accountToken   = Config::get('sms.accountToken');
        $appId          = Config::get('sms.appId');
        $serverIP       = Config::get('sms.serverIP');
        $serverPort     = Config::get('sms.serverPort');
        $softVersion    = Config::get('sms.softVersion');

        $rest = new CCPRestSDK($serverIP,$serverPort,$softVersion);
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($to,$datas,$tempId);

        if($result == NULL ) {
            BLogger::getLogger(BLogger::LOG_SMS)->notice(json_encode($log));
        }
        if($result->statusCode!=0) {
            $log['result'] = $result;
            BLogger::getLogger(BLogger::LOG_SMS)->notice(json_encode($log));
            //TODO 添加错误处理逻辑
        }else{
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
            $log['result'] = $smsmessage;
            BLogger::getLogger(BLogger::LOG_SMS)->notice(json_encode($log));
            //TODO 添加成功处理逻辑
        }

        return $result;
    }
}
