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

class Banner extends Model
{

    protected $table = 'banners';

    /*
     * 获取轮波图列表
     * is_open      是否上线
     */
    public function getBannerList(){
        return DB::table($this->table)->where('is_open' , 1)->orderBy('sort' , 'desc')->get();
    }

    public function saveBanner($datas,$method){
            $imgs="";
        if($datas['str']!='')
            $imgs=$datas['str'];
        else
            $imgs=$datas['img'];
        

            $redirect=$datas['redirect'];
            $name=$datas['name'];
            $create_at = $datas['create_time'];
            $updated_at =$datas['update_time'];
            $is_open =$datas['is_open'];
            $sort = $datas['order'];
            $id = $datas['id'];


        if($method==1)
        {
                return DB::update("update banners set img='$imgs',redirect='$redirect',name='$name',created_at= '$create_at',updated_at= '$updated_at',is_open='$is_open',sort='$sort' where id = ?", ["$id"]);
        }

        if($method==0)
        {
            return DB::insert('insert into banners (img, redirect,name,created_at,updated_at,is_open,sort) values (?, ?,?,?,?,?,?)', [$imgs,$redirect,$name,$create_at,$updated_at, $is_open,$sort]);
        }
            return 0;

    }
    

    public function bannerVersionTotalNum()
    {
        $sql = "select  count(*) as num from banners";
        $num = DB::select($sql);
        return $num[0]->num;
    }

}