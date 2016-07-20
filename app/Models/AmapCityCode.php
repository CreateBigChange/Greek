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

class AmapCityCode extends Model{

    protected $table = 'amap_city_code';

    /**
     *
     * 地区
     * @param pid  number
     */
    public function getAreasProvince(){
        $parent =  DB::table($this->table)->select('adcode as id' , 'name')->where('level'  , 'province')->get();
        return $parent;
    }

    /**
     *
     * 地区
     * @param pid  number
     */
    public function getAreasCity($pid){
        $pid = substr($pid , 0 , 2);
        $parent =  DB::table($this->table)->select('adcode as id' , 'name')->where('adcode' , 'like' , $pid."%")->where('level'  , 'city')->get();
        return $parent;
    }

    /**
     *
     * 地区
     * @param pid  number
     */
    public function getAreasDistrict($cid){
        $cid = substr($cid , 0 , 4);
        $parent =  DB::table($this->table)->select('adcode as id' , 'name')->where('adcode' , 'like' , $cid."%")->where('level'  , 'district')->get();
        return $parent;
    }

    /**
     *
     * 所有地区
     */
    public function allAreas(){
        $parent =  DB::table($this->table)->select('adcode as id' , 'name')->where('level'  , 'province')->get();

        $son = DB::table($this->table)->select('adcode as id' , 'name')->where('level' , 'city')->get();

        $grandson = DB::table($this->table)->select('adcode as id' , 'name')->where('level' , 'district')->get();

        foreach ($son as $s) {
            $s->son = array();
            foreach ($grandson as $g) {
                if(substr($s->id , 0 , 4) == substr($g->id , 0 , 4)){
                    $g->parent = $s->id;
                    $s->son[] = $g;

                }
            }
        }

        foreach ($parent as $p) {
            $p->son = array();
            foreach ($son as $s){
                if (substr($p->id , 0, 2) == substr($s->id , 0, 2)) {
                    $s->parent = $p->id;
                    $p->son[] = $s;
                }
            }
        }

        return $parent;
    }

    /**
     *
     * 地区
     * @param pid  number
     */
    public function getAreas($code){
        $area =  DB::table($this->table)->select('adcode as id' , 'name')->where('adcode'  , $code)->first();
        return $area;
    }


}
