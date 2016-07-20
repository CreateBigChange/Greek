<?php

return [

	// The default gateway to use
	'default' => 'alipay',

	// Add in each gateway here
	'gateways' => [
		'paypal' => [
			'driver'  => 'PayPal_Express',
			'options' => [
				'solutionType'   => '',
				'landingPage'    => '',
				'headerImageUrl' => ''
			]
		],
		'alipay' => [
			'driver' => 'Alipay_Express',
			'options' => [
				'partner' => '2088121058783821',
				'key' => '2016060201471049',
				'sellerEmail' =>'zxhy201510@163.com',
				'returnUrl' => 'http://preview.jisxu.com/sigma/alipay/return',
				'notifyUrl' => 'http://preview.jisxu.com/sigma/alipay/notify'
			]
		]
	]

];