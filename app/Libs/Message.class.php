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




				'MYSQL_CONN_FAILED'				=> array('code'=>'5001' , 'msg'=>'数据库连接失败'),
				'NOT_DELETE'					=> array('code'=>'5002' , 'msg'=>'该栏目下还有商品'),
				'RELOGIN'						=> array('code'=>'5003' , 'msg'=>'请重新登录'),

				'POINT_NOT_AMPLE'				=> array('code'=>'5004' , 'msg'=>'积分不足'),

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
