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
                    sg.c_id AS category_id ,
                    sg.b_id AS brand_id,
                    gb.name AS brand_name,
                    sn.id AS nav_id,
                    sn.name AS nav_name
                FROM $this->table AS sg ";

        $sql .= " LEFT JOIN goods_categories as gc ON gc.id = sg.c_id";
        $sql .= " LEFT JOIN goods_brand as gb ON gb.id = sg.b_id";
        $sql .= " LEFT JOIN store_nav as sn ON sn.id = sg.nav_id";


      //  $sql .= " WHERE sg.is_open = 1";

        if(isset($search['is_open'])){
            $sql .= " WHERE sg.is_open = ".$search['is_open'];
        }else{
            $sql .= " WHERE sg.is_open = 1";
        }
        if(isset($search['is_checked'])){
            $sql .= " WHERE sg.is_checked = ".$search['is_checked'];
        }else{
            $sql .= " WHERE sg.is_checked = 1";
        }

        if(isset($search['stock'])){
            $sql .= " AND sg.stock > 0";
        }

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
                FROM $this->table AS sg ";

        $sql .= " LEFT JOIN goods_categories as gc ON gc.id = sg.c_id";
        $sql .= " LEFT JOIN goods_brand as gb ON gb.id = sg.b_id";
        $sql .= " LEFT JOIN store_nav as sn ON sn.id = sg.nav_id";

        if(isset($search['is_open'])){
            $sql .= " WHERE sg.is_open = ".$search['is_open'];
        }else{
            $sql .= " WHERE sg.is_open = 1";
        }

        if(isset($search['is_checked'])){
            $sql .= " WHERE sg.is_checked = ".$search['is_checked'];
        }else{
            $sql .= " WHERE sg.is_checked = 1";
        }

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

    /**
     * 更新商品购买数量
     */
    public function updateGoodsBuyNum($goodsId , $addNum){
        return DB::table($this->table)->where('id' , $goodsId)->increment('out_num' , $addNum);
    }


    /**
     * 添加商品
     * @param data   array
     */
    public function addGoods($data){
        return DB::table($this->table)->insert($data);
    }

    /**
     * 修改商品
     * @param storeId   number
     * @param id   number
     * @param data   array
     */
    public function updateGoods($storeId , $id , $data){
        return DB::table($this->table)->where('id' , $id)->where('store_id' , $storeId)->update($data);
    }

    /**
     * 修改店铺商品
     * @param storeId   number
     * @param id   number
     * @param data   array
     */
    public function updateStoreGoods( $id , $data){
        return DB::table($this->table)->where('id' , $id)->update($data);
    }

    /**
     * 批量修改商品状态
     * @param storeId   number
     * @param ids   array
     * @param data   array
     */
    public function updateStatus($storeId , $ids , $data){
        return DB::table($this->table)->whereIn('id' , $ids)->where('store_id' , $storeId)->update($data);
    }

    /**
     *
     * 删除栏目下的商品
     * @param navId     number
     * @param storeId   number
     */
    public function delNavGoods($navId , $storeId){
        return DB::table($this->table)->where('nav_id' , $navId)->where('store_id' , $storeId)->update(array('is_del' => 1));

    }

    /**
     *
     * 下架栏目下的所以商品
     * @param navId     number
     * @param storeId   number
     */
    public function xiaJiaNavGoods($navId , $storeId){
        return DB::table($this->table)->where('nav_id' , $navId)->where('store_id' , $storeId)->update(array('is_open' => 0));

    }
}
