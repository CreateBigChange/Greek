<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class WechatPayLog extends Model
{
    protected $table = 'wechat_pay_logs';

    public function addLog($data){
        return DB::table($this->table)->insert($data);
    }

    public function updateWechatLog($outTradeNo , $data){
        return DB::table($this->table)->where('out_trade_no' , $outTradeNo)->update($data);
    }
}
