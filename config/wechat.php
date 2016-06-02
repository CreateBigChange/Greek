<?php
return [
    'use_alias'    => env('WECHAT_USE_ALIAS', false),
    'app_id'       => env('WECHAT_APPID', 'wx40bf86f9bf3f1c1e'), // 必填
    'secret'       => env('WECHAT_SECRET', 'be3cf5a36a3797484968d7976c9d5465'), // 必填
    'token'        => env('WECHAT_TOKEN', 'p5p3luQ13QZv5E3q5l1z3k1h3M3iZk5H'),  // 必填
    'encoding_key' => env('WECHAT_ENCODING_KEY', 'Re7Sv7Rsyi4r3VvVRNCseRq7qeqNEar7lr733ZsSc3N') // 加密模式需要，其它模式不需要
];