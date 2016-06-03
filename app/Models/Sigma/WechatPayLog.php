<?php

namespace App\Models\Sigma;

use DB;
use Illuminate\Database\Eloquent\Model;

class WechatPayLog extends Model
{
    protected $_table = 'wechat_pay_logs';

    public function addLog($data){
        return DB::table($this->_table)->insert($data);
    }

    public function updateWechatLog($outTradeNo , $data){
        return DB::table($this->_table)->where('out_trade_no' , $outTradeNo)->update($data);
    }
}
