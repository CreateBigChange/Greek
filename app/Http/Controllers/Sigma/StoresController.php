<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Sigma;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

use Session , Cookie , Config;

use App\Libs\Message;

use App\Models\StoreInfo;
use App\Models\StoreDateCount;
use App\Models\StoreGoods;
use App\Models\StoreNav;
use App\Models\StoreCategory;

class StoresController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model       = new StoreInfo;
        $this->_length		= 20;
    }

    /**
     * @api {POST} /sigma/store/info/{storeId} 获取单个店铺信息
     * @apiName storeInfo
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/info/1
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/info/1
     *      {
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreInfo($storeId){

        $storeInfo = $this->_model->getStoreInfo($storeId);

        $storeDateCountModel = new StoreDateCount;

        //添加访问量
        $storeDateCountModel->addStoreCount($storeId);

        return response()->json(Message::setResponseInfo('SUCCESS' , $storeInfo));
    }


    /**
     * @api {POST} /sigma/store/list 获取店铺列表
     * @apiName storeList
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/list
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/list
     *      {
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreList(){

        $storeList = $this->_model->getStoreList();

        return response()->json(Message::setResponseInfo('SUCCESS' , $storeList));
    }

    /**
     * @api {POST} /sigma/store/list/byids[?page=1] 根据店铺ids获取店铺列表
     * @apiName storeListByIds
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/list/byids?page=1
     *
     * @apiParam {string} ids 商品id
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/list/byids
     *      {
     *          ids : "1,2,3,4,5,6,7",
     *          cid : 1
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreListByIds(Request $request){

        $page = 1;
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }

        if(!$request->has('ids')){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $ids = $request->get('ids');

        $search = array('ids'=>$ids );

        if($request->has('cid')) {
            $search['c_id'] = $request->get('cid');

        }
        $search['is_open'] = 1;
        $search['is_checked'] = 1;

        $totalNum = count(explode(',', $ids));

        $response = array();

        $response['pageData'] = $this->getPageData($page , $this->_length , $totalNum);

        $response['storeList'] = $this->_model->getStoreList( $search , $this->_length , $response['pageData']->offset);

        return response()->json(Message::setResponseInfo('SUCCESS' , $response));
    }

    /**
     * @api {POST} /sigma/store/goods/list/{storeId} 获取店铺商品列表
     * @apiName storeGoodsList
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/goods/list/1
     *
     * @apiParam {string} [name] 商品名称搜索
     * @apiParam {string} [nav_id] 栏目搜索
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/goods/list/1
     *      {
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreGoodsList($storeId , Request $request){

        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        if($request->has('name')){
            $search['name'] = htmlspecialchars($request->get('name'));
        }

        if($request->has('nav_id') && !$request->get('nav_id') == 0 ){
            $search['nav_id'] = trim($request->get('nav_id'));
        }

        $search['store_id'] = $storeId;


        $storeGoodsModel = new StoreGoods;
        $goodsNum   = $storeGoodsModel->getStoreGoodsTotalNum($search);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $goodsNum);
        $response['goodsList']  = $storeGoodsModel->getStoreGoodsList($search , $this->_length , $response['pageData']->offset);

        return response()->json(Message::setResponseInfo('SUCCESS' , $response));

    }

    /**
     * @api {POST} /sigma/store/goods/info/{goodsId} 获取单个商品信息
     * @apiName storeGoodsInfo
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取单个商品信息
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/goods/info/1
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/goods/info/1
     *      {
     *          'name'  : '测试',
     *          'nav_id'  : '1',
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreGoodsInfo($goodsId){

        $storeGoodsModel = new StoreGoods;
        $info = $storeGoodsModel->getStoreGoodsList( array('id' => $goodsId) , $this->_length , 0);

        if(count($info) == 0){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('SUCCESS' , $info[0]));
        }

    }

    /**
     * @api {POST} /sigma/store/nav/{storeId} 获取栏目
     * @apiName storeNav
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取栏目
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/nav/1
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/nav/1
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function nav($storeId){

        $storeNavModel = new StoreNav;
        $navList = $storeNavModel->getNav($storeId);

        return response()->json(Message::setResponseInfo('SUCCESS' , $navList));
    }

    /**
     * @api {POST} /sigma/store/category 获取店铺分类
     * @apiName storeCategory
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription 获取店铺分类
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/store/category
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/store/category
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function storeCategory(){

        $storeCategoryModel = new StoreCategory;
        $categoryList = $storeCategoryModel->getStoreCategory();

        return response()->json(Message::setResponseInfo('SUCCESS' , $categoryList));
    }

}