<?php

namespace App\Models\Alpha;

use DB;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $_goods_table       	            = 'goods';
    protected $_goods_categories_table       	= 'goods_categories';
    protected $_goods_brand_table       	    = 'goods_brand';

    /**
     *
     * 获取商品
     */
    public function getGoodsList(){
        $sql = "SELECT 
                    g.id,
                    g.name,
                    g.img,
                    g.in_price,
                    g.out_price,
                    g.give_points,
                    g.spec,
                    g.desc,
                    g.stock,
                    g.is_open,
                    g.is_checked,
                    g.created_at,
                    gc.id AS c_id,
                    gc.name AS cname,
                    gb.id AS b_id,
                    gb.name AS bname
                    FROM $this->_goods_table AS g";

        $sql .= " LEFT JOIN $this->_goods_categories_table AS gc ON gc.id = g.c_id";
        $sql .= " LEFT JOIN $this->_goods_brand_table AS gb ON gb.id = g.b_id";

        $sql .= " ORDER BY created_at DESC";

        return DB::select($sql);
    }

    /**
     *
     * 添加商品
     */
    public function addGoods($data){
        return DB::table($this->_goods_table)->insert($data);
    }

    /**
     *
     * 获取商品分类
     */
    public function getGoodsCategoryByPid($pid){
        return DB::table($this->_goods_categories_table)->where('p_id' , $pid)->get();
    }

    /**
     *
     * 获取商品品牌
     */
    public function getGoodsBrandByCid($cid){
        return DB::table($this->_goods_brand_table)->where('c_id' , $cid)->get();
    }
}