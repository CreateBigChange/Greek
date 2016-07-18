<?php
/**
 * CouponModel
 * @author  yangxiansheng
 * @time    2016/06-08
 * @email   31479274@qq.com
 */
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class coupon extends Model
{

    protected $table = 'coupon';

    /*
     * 获取轮波图列表
     * is_open      是否上线
     */
    public function getCouponList(){
        $sql = "select * from coupon";

        return DB::select($sql);
    }

    public function addCoupon($data){



       return  DB::table('coupon')->insertGetId(
        array(
            'name' => $data->get('name'),
            'content' => $data->get('content'),
            'type' => $data->get('type'),
            'effective_time' => $data->get('effective_time'),
            'value' => $data->get('value'),
            'prerequisite' => $data->get('prerequisite'),
            'total_num' => $data->get('total_num'),
            'in_num' => $data->get('in_num'),
            'out_num' => $data->get('out_num'),
            'stop_out' => $data->get('stop_out'),
            'num' => $data->get('num')
            )
        );

    }


public function couponDelete($id)
{
    DB::table('coupon')->where('id', '=', $id)->delete();
}
    public function updateCoupon($data)
    {

        return DB::table('coupon')
            ->where('id', $data->get('coupon_id'))
            ->update(array(
            'name' => $data->get('name'),
            'content' => $data->get('content'),
            'type' => $data->get('type'),
            'effective_time' => $data->get('effective_time'),
            'value' => $data->get('value'),
            'prerequisite' => $data->get('prerequisite'),
            'total_num' => $data->get('total_num'),
            'in_num' => $data->get('in_num'),
            'out_num' => $data->get('out_num'),
            'stop_out' => $data->get('stop_out'),
            'num' => $data->get('num')
            ));

        
    }

    public function couponTotalNum()
    {
        $sql = "select  count(*) as num from coupon";
        $num = DB::select($sql);
        return $num[0]->num;
    }

}