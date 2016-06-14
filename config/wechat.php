<?php
return [
    'use_alias'     => env('WECHAT_USE_ALIAS', false),
    'app_id'        => env('WECHAT_APPID', 'WECHAT_APPID'), // 必填
    'secret'        => env('WECHAT_SECRET', 'WECHAT_SECRET'), // 必填
    'token'         => env('WECHAT_TOKEN', 'WECHAT_TOKEN'),  // 必填
    'encoding_key'  => env('WECHAT_ENCODING_KEY', 'WECHAT_ENCODING_KEY'), // 加密模式需要，其它模式不需要
    'merchant_id'   => env('WECHAT_MCH_ID' , 'WECHAT_MCH_ID'),  //商户号
    'key'           => env('WECHAT_KEY' , 'WECHAT_KEY'),         //商户密钥
    'cert_path'     => env('WECHAT_CERT_PATH' , 'WECHAT_CERT_PATH'),
    'key_path'      => env('WECHAT_KET_PATH' , 'WECHAT_KET_PATH'),
    'notify_url'    => 'http://preview.jisxu.com/sigma/wechat/notify',


    'open_use_alias'     => env('WECHAT_OPEN_USE_ALIAS', false),
    'open_app_id'        => env('WECHAT_OPEN_APPID', 'WECHAT_APPID'), // 必填
    'open_secret'        => env('WECHAT_OPEN_SECRET', 'WECHAT_SECRET'), // 必填
    'open_merchant_id'   => env('WECHAT_OPEN_MCH_ID' , 'WECHAT_MCH_ID'),  //商户号
    'open_key'           => env('WECHAT_OPEN_KEY' , 'WECHAT_KEY'),         //商户密钥
    'open_cert_path'     => env('WECHAT_OPEN_CERT_PATH' , 'WECHAT_CERT_PATH'),
    'open_key_path'      => env('WECHAT_OPEN_KET_PATH' , 'WECHAT_KET_PATH'),
    'open_notify_url'    => 'http://preview.jisxu.com/sigma/wechat/notify',
];