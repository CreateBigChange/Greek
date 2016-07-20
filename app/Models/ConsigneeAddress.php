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

class ConsigneeAddress extends Model{

    protected $table = 'consignee_address';

    /**
     * @param $userId
     * @return mixed
     * 获取用户收货地址
     */
    public function getConsigneeAddressByUserId($userId , $length = 20 , $offset = 0){
        return DB::table($this->table)
            ->where('user_id' , $userId)
            ->where('is_del' , 0)
            ->skip($offset)->take($length)
            ->get();
    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户收货地址的总数
     */
    public function getCAByUidTotalNum($userId){
        return DB::table($this->table)
            ->where('user_id' , $userId)
            ->where('is_del' , 0)
            ->count();
    }

    /**
     * @param $data
     * @return mixed
     * 添加收货地址
     */
    public function addConsigneeAddress($data){
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     * @param $id
     * @return mixed
     * 根据ID获取收货地址
     */
    public function getConsigneeAddressById($id){
        return DB::table($this->table)->where('id' , $id)->where('is_del' , 0)->first();
    }

    /**
     * @param $userId
     * @param $id
     * @param $data
     * @return mixed
     * 修改收货地址
     */
    public function updateConsigneeAddress($userId , $id , $data){
        return DB::table($this->table)->where('id' , $id)->where('user_id' , $userId)->update($data);
    }
}
