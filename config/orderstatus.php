<?php

return [

    'completd' => [
        'status'    => 1,
        'msg'       => "已完成",
        'next'      => array()
    ],

    'paid' => [
        'status'    => 2,
        'msg'       => "已付款",
        'next'      => array(4 , 13)
    ] ,

//    'accepted' => [
//        'status'    => 3,
//        'msg'       => "已接单",
//        'next'      => array(14)
//    ] ,

    'on_the_way' => [
        'status'    => 4,
        'msg'       => "配送中",
        'next'      => array(5 , 13)
    ] ,

    'arrive' => [
        'status'    => 5,
        'msg'       => "已送达",
        'next'      => array(1 , 13)
    ] ,

    'no_pay' => [
        'status'    => 11,
        'msg'       => "未付款",
        'next'      => array()
    ] ,

    'cancel' => [
        'status'    => 12,
        'msg'       => "已取消",
        'next'      => array()
    ] ,

    'refunding' => [
        'status'    => 13,
        'msg'       => "退款中",
        'next'      => array(14)
    ] ,

    'refunded' => [
        'status'    => 14,
        'msg'       => "已退款",
        'next'      => array()
    ] ,

];
