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
     * 添加商品
     */
    public function addGoods(Request $request){
        $data = array();
        $data['name']       = $request->get('name');
        $data['img']        = $request->get('img');
        $data['out_price']  = $request->get('out_price');
        $data['spec']       = $request->get('spec');
        $data['desc']       = $request->get('desc');
        $data['c_id']       = $request->get('c_id');
        $data['b_id']       = $request->get('b_id');

        if($request->has('is_open') && $request->get('is_open') == 'on'){
            $data['is_open'] = 1;
        }else{
            $data['is_open'] = 0;
        }

        if($request->has('is_checked') && $request->get('is_checked') == 'on'){
            $data['is_checked'] = 1;
        }else{
            $data['is_checked'] = 0;
        }

        $data['created_at'] = date('Y-m-d H:i:s' , time());
        $data['updated_at'] = date('Y-m-d H:i:s' , time());

        $goodsModel = new Goods;

        $goodsModel->addGoods($data);

        return redirect('/alpha/goods');
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
