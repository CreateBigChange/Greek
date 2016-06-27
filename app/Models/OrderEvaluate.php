<?php
/**
 * bannerModel
 * @author  wuhui
 * @time    2016/06-08
 * @email   wuhui904107775@qq.com
 */
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Order;

class OrderEvaluate extends Model{

    protected $table         = 'order_evaluates';

    /**
     * @param $data
     * @return mixed
     * 评价
     */
    public function evaluate($data){
        
        $orderModel = new Order();
        
        if(DB::table($this->table)->insert($data)){
            DB::table($orderModel->getTable())->where('id' , $data['order_id'])->update(array('is_evaluate' => 1 , 'status' => 1));
            $this->createOrderLog($data['order_id'], $data['user_id'], '普通用户', '用户端APP', '订单评价完成' , Config::get('orderstatus.completd')['status']);
            return true;
        }else{
            return false;
        }
    }

}
