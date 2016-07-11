<?php

return [
    'partner'               => env('ALIPAY_PARTNER' , 'ALIPAY_PARTNER'),
    'seller_user_id'        => env('ALIPAY_SELLER_USER_ID' , 'ALIPAY_SELLER_USER_ID'),
    'private_key_path'      => env('ALIPAY_PRIVATE_KEY_PATH' , 'ALIPAY_PRIVATE_KEY_PATH'),
    'ali_public_key_path'   => env('ALIPAY_PUBLIC_KEY_PATH' , 'ALIPAY_PUBLIC_KEY_PATH'),
    'notify_url'            => env('ALIPAY_NOTIFY_REFUND_URL' , 'ALIPAY_NOTIFY_REFUND_URL'),
    'notify_pay_url'        => env('ALIPAY_NOTIFY_URL' , 'ALIPAY_NOTIFY_URL'),
    'sign_type'             => strtoupper('RSA'),
    'refund_date'           => date("Y-m-d H:i:s",time()),
    'input_charset'         => strtolower('utf-8'),
    'cacert'                => env('ALIPAY_CACERT' , 'ALIPAY_CACERT'),
    'transport'             => 'http',
    'service'               => 'refund_fastpay_by_platform_nopwd',
    'key'                   => env('ALIPAY_KEY' , 'ALIPAY_KEY')
];