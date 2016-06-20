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

class StoreGoods extends Model{

    protected $table       = 'store_goods';

    /**
     * 获取商品列表
     * @param storeId   number
     * @param search   array
     * @param length   number
     * @param offset   number
     */
    public function getStoreGoodsList($search = array() , $length = 20 , $offset = 0 ){

        $sql = "SELECT 
                    sg.id ,
                    sg.store_id,
                    sg.name,
                    sg.img,
                    sg.in_price,
                    sg.out_price,
                    sg.give_points,
                    sg.spec,
                    sg.desc,
                    sg.stock,
                    sg.is_open,
                    sg.is_checked,
                    sg.created_at,
                    sg.updated_at,
                    sg.out_num,
                    gc.name AS category_name ,
                    gc.id AS category_id ,
                    gb.id AS brand_id,
                    gb.name AS brand_name,
                    sn.id AS nav_id,
                    sn.name AS nav_name
                FROM $this->table AS sg ";

        $sql .= " LEFT JOIN goods_categories as gc ON gc.id = sg.c_id";
        $sql .= " LEFT JOIN goods_brand as gb ON gb.id = sg.b_id";
        $sql .= " LEFT JOIN store_nav as sn ON sn.id = sg.nav_id";


        $sql .= " WHERE sg.is_open = 1";

        if(isset($search['store_id'])) {
            $sql .= " AND sg.store_id = " . $search['store_id'];
        }

        if(isset($search['nav_id'])){
            $sql .= " AND sg.nav_id = ".$search['nav_id'];
        }
        if(isset($search['name'])){
            $sql .= " AND sg.name LIKE '%" . $search['name'] . "%'";
        }
        if(isset($search['id'])){
            $sql .= " AND sg.id = ".$search['id'];
        }
        if(isset($search['ids'])){
            $sql .= " AND sg.id IN (".implode(',' , $search['ids']) .")";
        }
        $sql .= " AND sg.is_del = 0";

        if(isset($search['sort_stock']) && $search['sort_stock'] == 'desc'){
            $sql .= " ORDER BY stock DESC ";
        }elseif(isset($search['sort_stock']) && $search['sort_stock'] == 'asc'){
            $sql .= " ORDER BY stock ASC ";
        }else{
            $sql .= " ORDER BY created_at DESC";
        }

        $sql .= " LIMIT $offset , $length";


        return DB::select($sql);

    }

    /**
     * 获取商品列表
     * @param storeId   number
     * @param search   array
     */
    public function getStoreGoodsTotalNum( $search = array()){
        $sql = "SELECT 
                    count(*) as num
                FROM $this->_store_goods_table AS sg ";

        $sql .= " LEFT JOIN goods_categories as gc ON gc.id = sg.c_id";
        $sql .= " LEFT JOIN goods_brand as gb ON gb.id = sg.b_id";
        $sql .= " LEFT JOIN store_nav as sn ON sn.id = sg.nav_id";

        $sql .= " WHERE sg.is_open = 1";

        if(isset($search['store_id'])) {
            $sql .= " AND sg.store_id = " . $search['store_id'];
        }
        if(isset($search['nav_id'])){
            $sql .= " AND sg.nav_id = ".$search['nav_id'];
        }
        if(isset($search['name'])){
            $sql .= " AND sg.name LIKE '%" . $search['name'] . "%'";
        }
        $sql .= " AND sg.is_del = 0";

        $num = DB::select($sql);

        return $num[0]->num;
    }

}
