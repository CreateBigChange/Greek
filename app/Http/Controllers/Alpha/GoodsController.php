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

    private $length;

    public function __construct(){
        parent::__construct();
        $this->response['title']		= '商品库管理';
        $this->length = 10;
    }

    /**
     *
     * 获取商品列表
     */
    public function getGoodsList(Request $request){
        $goodsModel = new Goods;

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $search = array();

        $param = '';

        if(isset($_GET['name']) && !empty($_GET['name'])){
            $search['name'] = trim($_GET['name']);
            $param .= 'name=' . $search['name'] . '&';
        }
        if(isset($_GET['c_one_id']) && !empty($_GET['c_one_id'])){
            $search['c_one_id'] = trim($_GET['c_one_id']);
            $param .= 'c_one_id=' . $search['c_one_id'] . '&';
        }
        if(isset($_GET['c_two_id']) && !empty($_GET['c_two_id'])){
            $search['c_two_id'] = trim($_GET['c_two_id']);
            $param .= 'c_two_id=' . $search['c_two_id'] . '&';
        }
        if(isset($_GET['c_id']) && !empty($_GET['c_id'])){
            $search['c_id'] = trim($_GET['c_id']);
            $param .= 'c_id=' . $search['c_id'] . '&';
        }
        if(isset($_GET['b_id']) && !empty($_GET['b_id'])){
            $search['b_id'] = trim($_GET['b_id']);
            $param .= 'b_id=' . $search['b_id'] . '&';
        }
        if(isset($_GET['is_open']) && $_GET['is_open'] != -1 ){
            $search['is_open'] = trim($_GET['is_open']);
            $param .= 'is_open=' . $search['is_open'] . '&';
        }
        if(isset($_GET['is_checked']) && $_GET['is_checked'] != -1 ){
            $search['is_checked'] = trim($_GET['is_checked']);
            $param .= 'is_checked=' . $search['is_checked'] . '&';
        }
        
        $totalNum = $goodsModel->getGoodsTotalNum($search);

        $pageData = $this->getPageData($page  , $this->length, $totalNum);

        $this->response['page']         = $pageData->page;
        $this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/goods?' . $param);
        $this->response['goods']        = $goodsModel->getGoodsList( $this->length , $pageData->offset , $search);
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
     * 修改商品
     */
    public function editGoods(Request $request){

        $id = $request->get('id');

        $data = array();

        if($request->has('name')) {
            $data['name'] = $request->get('name');
        }
        if($request->has('img')) {
            $data['img'] = $request->get('img');
        }
        if($request->has('out_price')) {
            $data['out_price'] = $request->get('out_price');
        }
        if($request->has('spec')) {
            $data['spec'] = $request->get('spec');
        }
        if($request->has('desc')) {
            $data['desc'] = $request->get('desc');
        }
        if($request->has('c_id')) {
            $data['c_id'] = $request->get('c_id');
        }
        if($request->has('b_id')) {
            $data['b_id'] = $request->get('b_id');
        }

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

        $data['updated_at'] = date('Y-m-d H:i:s' , time());

        $goodsModel = new Goods;

        $goodsModel->editGoods($id , $data);

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
     * 获取商品分类
     */
    public function getGoodsCategory(){
        $goodsModel = new Goods;
        $this->response['category'] = $goodsModel->getGoodsCategoryByPid(0);
        return view('alpha.goods.category_list' , $this->response);
    }

    /**
     * 获取单个商品分类
     */
    public function getGoodsCategoryById($id){
        $goodsModel = new Goods;
        $this->response['category'] = $goodsModel->getGoodsCategoryById($id);
        return $this->response;
    }

    /**
     * 更新商品分类
     */
    public function updateGoodsCategory(Request $request){
        $goodsModel = new Goods;

        if(!$request->has('id')){
            return view('errors.503');
        }
        if(!$request->has('name')){
            return view('errors.503');
        }

        $id = $request->get('id');

        $data = array();

        $data['name']       = $request->get('name');
        $data['updated_at'] = $request->get('updated_at');

        if($goodsModel->updateGoodsCategory($id , $data)) {
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * 获取单个商品品牌
     */
    public function getGoodsBrandById($id){
        $goodsModel = new Goods;
        $this->response['brand'] = $goodsModel->getGoodsBrandById($id);
        return $this->response;
    }

    /**
     * 更新商品品牌
     */
    public function updateGoodsBrand(Request $request){
        $goodsModel = new Goods;

        if(!$request->has('id')){
            return view('errors.503');
        }
        if(!$request->has('name')){
            return view('errors.503');
        }

        $id = $request->get('id');

        $data = array();

        $data['name']       = $request->get('name');
        $data['updated_at'] = $request->get('updated_at');

        if($goodsModel->updateGoodsBrand($id , $data)) {
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * ajax获取商品分类
     */
    public function ajaxGoodsCategoryByLevel($level){
        $goodsModel = new Goods;
        $this->response['category'] = $goodsModel->getGoodsCategoryByLevel($level);
        return response()->json(Message::setResponseInfo('SUCCESS' , $this->response));
    }

    /**
     * 获取商品信息
     */
    public function ajaxGoodsInfo($id){
        $goodsModel = new Goods;
        $this->response['info'] = $goodsModel->getGoodsInfo($id);
        if(isset($this->response['info'][0])){
            $this->response['info'] = $this->response['info'][0];
        }
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

    /**
     * @param Request $request
     * @return mixed
     * 添加分类
     */
    public function addCategory(Request $request){
        if(!$request->has('name')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        if(!$request->has('pid')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();

        $data['name']           = $request->get('name');
        $data['p_id']           = $request->get('pid');
        $data['level']          = $request->get('level');
        $data['created_at']     = date('Y-m-d H:i:s' , time());
        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        $goodsModel = new Goods;

        $id = $goodsModel->addCategory($data);

        if($id){
            return response()->json(Message::setResponseInfo('SUCCESS' , $id));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 添加品牌
     */
    public function addBrand(Request $request){
        if(!$request->has('name')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        if(!$request->has('cid')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();

        $data['name']           = $request->get('name');
        $data['c_id']           = $request->get('cid');
        $data['created_at']     = date('Y-m-d H:i:s' , time());
        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        $goodsModel = new Goods;

        $id = $goodsModel->addBrand($data);

        if($id){
            return response()->json(Message::setResponseInfo('SUCCESS' , $id));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @param $id
     * @return mixed
     * 删除分类
     */
    public function delCategory($id){
        $goodsModel = new Goods;
        if($goodsModel->delCategory($id)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @param $id
     * @return mixed
     * 删除品牌
     */
    public function delBrand($id){
        $goodsModel = new Goods;
        if($goodsModel->delBrand($id)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

}
