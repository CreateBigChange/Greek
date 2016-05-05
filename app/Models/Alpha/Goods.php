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
    public function getGoodsList($length = 20 , $offset = 0 , $search = array()){
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
                    gcc.id AS cc_id,
                    gcc.name AS ccname,
                    gccc.id AS ccc_id,
                    gccc.name AS cccname,
                    gb.id AS b_id,
                    gb.name AS bname
                    FROM $this->_goods_table AS g";

        $sql .= " LEFT JOIN $this->_goods_categories_table AS gc ON gc.id = g.c_id";
        $sql .= " LEFT JOIN $this->_goods_categories_table AS gcc ON gcc.id = gc.p_id";
        $sql .= " LEFT JOIN $this->_goods_categories_table AS gccc ON gccc.id = gcc.p_id";
        $sql .= " LEFT JOIN $this->_goods_brand_table AS gb ON gb.id = g.b_id";
        $sql .= " WHERE g.is_del = 0";

        if(isset($search['ids'])){
            $sql .= " AND g.id IN (" . implode(',' , $search['ids']) . ")";
        }

        if(isset($search['name'])){
            $sql .= " AND g.name LIKE '%" . $search['name'] . "%'";
        }
        if(isset($search['c_one_id'])){
            $sql .= " AND gccc.id = " .$search['c_one_id'];
        }
        if(isset($search['c_two_id'])){
            $sql .= " AND gcc.id = " .$search['c_two_id'];
        }
        if(isset($search['c_id'])){
            $sql .= " AND g.c_id = " .$search['c_id'];
        }
        if(isset($search['b_id'])){
            $sql .= " AND g.b_id = " .$search['b_id'];
        }
        if(isset($search['is_open'])){
            $sql .= " AND g.is_open = " .$search['is_open'];
        }
        if(isset($search['is_checked'])){
            $sql .= " AND g.is_checked = " .$search['is_checked'];
        }

        $sql .= " ORDER BY created_at DESC";
        $sql .= " LIMIT $offset , $length ";

        return DB::select($sql);
    }


    /**
     * 获取商品总数
     */
    public function getGoodsTotalNum($search = array()){
        $sql = "SELECT 
                    count(*) as num
                    FROM $this->_goods_table AS g";

        $sql .= " LEFT JOIN $this->_goods_categories_table AS gc ON gc.id = g.c_id";
        $sql .= " LEFT JOIN $this->_goods_categories_table AS gcc ON gcc.id = gc.p_id";
        $sql .= " LEFT JOIN $this->_goods_categories_table AS gccc ON gccc.id = gcc.p_id";
        $sql .= " LEFT JOIN $this->_goods_brand_table AS gb ON gb.id = g.b_id";

        $sql .= " WHERE g.is_del = 0";

        if(isset($search['ids'])){
            $sql .= " AND g.id IN (" . implode(',' , $search['ids']) . ")";
        }

        if(isset($search['name'])){
            $sql .= " AND g.name LIKE '%" . $search['name'] . "%'";
        }
        if(isset($search['c_one_id'])){
            $sql .= " AND gccc.id = " .$search['c_one_id'];
        }
        if(isset($search['c_two_id'])){
            $sql .= " AND gcc.id = " .$search['c_two_id'];
        }
        if(isset($search['c_id'])){
            $sql .= " AND g.c_id = " .$search['c_id'];
        }
        if(isset($search['b_id'])){
            $sql .= " AND g.b_id = " .$search['b_id'];
        }
        if(isset($search['is_open'])){
            $sql .= " AND g.is_open = " .$search['is_open'];
        }
        if(isset($search['is_checked'])){
            $sql .= " AND g.is_checked = " .$search['is_checked'];
        }

        $num = DB::select($sql);

        return $num[0]->num;
    }

    /**
     *
     * 获取商品信息
     */
    public function getGoodsInfo($id){
        return $this->getGoodsList(1, 0,array('ids'=>array($id)));
    }

    /**
     *
     * 获取商品完整分类
     * @param id    最低级的分类ID
     *
     */
    public function getGoodsAllCategory($id){
        global $category;
        $category = DB::table($this->_goods_categories_table)->where('id' , $id)->frist();
        if($category->p_id == 0){
            return $category;
        }else{
            $this->getGoodsAllCategory($category->p_id);
        }
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
     * 修改商品
     */
    public function editGoods($id , $data){
        return DB::table($this->_goods_table)->where('id' , $id)->update($data);
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
     * 获取商品分类
     */
    public function getGoodsCategoryByLevel($level){
        return DB::table($this->_goods_categories_table)->where('level' , $level)->get();
    }

    /**
     *
     * 获取商品品牌
     */
    public function getGoodsBrandByCid($cid){
        return DB::table($this->_goods_brand_table)->where('c_id' , $cid)->get();
    }
}