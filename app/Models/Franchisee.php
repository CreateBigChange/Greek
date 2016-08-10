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

class Franchisee extends Model
{

    protected $table = 'franchisee';
    /**
     *
     * 申请入驻
     * @param data  array
     */
    public function franchisee($data)
    {
        return DB::table($this->table)->insert($data);
    }

    /**
     * @param int $length
     * @param int $offset
     * @param array $search
     * @return mixed
     */
    public function  getFranchiseeList($length=10,$offset =0,$search=array()){
        return DB::table($this->table)->where("is_contact",$search['is_contact'])->skip($offset)->take($length)->get();
    }

    public function  getFranchiseeListTotle($search=array()){
        return DB::table($this->table)->where("is_contact",$search["is_contact"])->count();
    }
    public function  updateStatus($id,$data){
        DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }

}