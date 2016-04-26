<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/30
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Alpha;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AdminController;

use App\Models\Alpha\Goods;
use App\Libs\Message;

class GoodsController extends AdminController
{

    public function __construct(){
        parent::__construct();
        $this->response['title']		= '商品库管理';
    }

    /**
     *
     * 获取商品列表
     */
    public function getGoodsList(){
        $goodsModel = new Goods;
        $this->response['goods'] = $goodsModel->getGoodsList();
        return view('alpha.goods.list' , $this->response);
    }

    /**
     * ajax获取商品分类
     */
    public function ajaxGoodsCategoryByPid($pid){
        $goodsModel = new Goods;
        $this->response['category'] = $goodsModel->getGoodsCategoryByPid($pid);
        return response()->json(Message::setResponseInfo('SUCCESS' , $this->response));
    }

    /**
     * ajax获取商品品牌
     */
    public function ajaxGoodsBrandByCid($cid){
        $goodsModel = new Goods;
        $this->response['brand'] = $goodsModel->getGoodsBrandByCid($cid);
        return response()->json(Message::setResponseInfo('SUCCESS' , $this->response));
    }

}
