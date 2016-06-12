<?php

namespace App\Libs;

use App\Libs\BLogger;

class Message {
	/*
	 * 定义通用报错列表
	*
	* @return	array
	*/
	static function setResponseInfo($errorkey , $data = '' , $code = '0001' , $msg = '未知错误' ){

			$error =  array(

				/**
				 *	通用错误
				 */
				'FAILED'	=> array('code'=>'0001' , "msg"=>'操作失败'),
				'SUCCESS'	=> array('code'=>'0000' , "msg"=>'操作成功'),


				'NO_USER'						=> array('code'=>'4001' , 'msg'=>'没有此用户'),
				'PASSWORD_ERROR'				=> array('code'=>'4002' , 'msg'=>'密码或帐号错误'),
				'VERTIFY_CODE_ERROR'			=> array('code'=>'4003' , 'msg'=>'验证码错误'),
				'VERTIFY_CODE_EXPIRE'			=> array('code'=>'4004' , 'msg'=>'验证码已过期'),
				"PARAMETER_ERROR"				=> array('code'=>'4005' , 'msg'=>'参数错误'),
				"PASSWORD_IS_NOT_CONSISTENT"	=> array('code'=>'4006' , 'msg'=>'两次密码不一致'),
				'REGISTERED'					=> array('code'=>'4007' , 'msg'=>'账户已被注册'),
				'REGISTERED'					=> array('code'=>'4008' , 'msg'=>'账户没有注册'),
				'NO_PHONE'						=> array('code'=>'4009' , 'msg'=>'手机号格式不对'),
				'NO_EMAIL'						=> array('code'=>'4010' , 'msg'=>'邮箱格式不对'),
				'TOKEN_FAILURE'					=> array('code'=>'4011' , 'msg'=>'令牌无效或过期'),
				'MOBILE_BIND'					=> array('code'=>'4012' , 'msg'=>'手机号已绑定其他帐号'),
				'HAVE_MOBILE'					=> array('code'=>'4013' , 'msg'=>'需要验证旧手机号'),
				'MOBILE_NOT_BIND'				=> array('code'=>'4014' , 'msg'=>'该手机号不是绑定的手机号'),
				'WX_TOKEN_FAILED'				=> array('code'=>'4015' , 'msg'=>'获取微信token失败'),
				'NOT_UPDATE_ADDRESS'			=> array('code'=>'4016' , 'msg'=>'订单状态显示您现在不能修改订单地址'),
				'OLD_PASSWORD_ERROR'			=> array('code'=>'4017' , 'msg'=>'旧密码错误'),
				'NO__HAVE_PAY_PASSWORD'			=> array('code'=>'4018' , 'msg'=>'没有设置支付密码'),
				'PAY_PASSWORD_ERROR'			=> array('code'=>'4019' , 'msg'=>'支付密码错误'),
				'MOBILE_NO_BIND'				=> array('code'=>'4020' , 'msg'=>'请先绑定手机号'),




				'MYSQL_CONN_FAILED'				=> array('code'=>'5001' , 'msg'=>'数据库连接失败'),
				'NOT_DELETE'					=> array('code'=>'5002' , 'msg'=>'该栏目下还有商品'),
				'RELOGIN'						=> array('code'=>'5003' , 'msg'=>'请重新登录'),

				'POINT_NOT_AMPLE'				=> array('code'=>'5004' , 'msg'=>'积分不足'),
				'MONEY_NOT_AMPLE'				=> array('code'=>'5005' , 'msg'=>'余额不足'),
				'MONEY_NOT_EQUAL'				=> array('code'=>'5006' , 'msg'=>'支付的金额与需要支付的金额不等'),
				'EMPTY_CONSIGNEE'				=> array('code'=>'5007' , 'msg'=>'请填写收货地址'),

			);

			if(isset($error[strtoupper($errorkey)])){
				$response = $error[strtoupper($errorkey)];
				$response['data'] = $data;
			}else{
				$response = array('code' => $code , 'msg' => $msg , 'data' => $data);
			}

			BLogger::getInOutLogger(BLogger::LOG_RESPONSE)->info(json_encode($response));
			return $response;

	}

	protected function writeOutPutLog($response){
		$response['time'] = date('Y-m-d H:i:s' , time());

	}
	
}
