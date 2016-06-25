<?php

return [
    'partner'               => '2088121058783821',
    'seller_user_id'        => '2088121058783821',
    'private_key_path'      => public_path() . '/alipay/rsa_private_key.pem',
    'ali_public_key_path'   => public_path().'/alipay/alipay_public_key.pem',
    'notify_url'            => 'http://preview.jisxu.com/sigma/alipay/notify',
    'sign_type'             => strtoupper('RSA'),
    'refund_date'           => date("Y-m-d H:i:s",time()),
    'input_charset'         => strtolower('utf-8'),
    'cacert'                => public_path().'/alipay/cacert.pem',
    'transport'             => 'http',
    'refund_service'        => 'alipay.trade.refund'
];