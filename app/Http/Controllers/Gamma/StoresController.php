<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Gamma;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator , Input;
use Session , Cookie , Config;

use App\Http\Controllers\ApiController;

use App\Models\Gamma\Stores;
use App\Models\Gamma\Orders;
use App\Libs\Message;

class StoresController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Stores;
        $this->_length		= 20;
    }

    /**
     * @api {POST} /gamma/store/info/{id} 获取店铺信息
     * @apiName storeInfo
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取店铺信息
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/info/1
     *
     * @apiParam {id} [id] 店铺ID
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/areas
     *      {
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreInfo($id){

        $info =  $this->_model->getStoreInfo($id);

        return response()->json(Message::setResponseInfo('SUCCESS' , $info));


    }

    /**
     * @api {POST} /gamma/store/areas 地区
     * @apiName areas
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 地区
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/areas
     *
     * @apiParam {pid} [pid] 地区pid默认为0;不传为获取所有地区
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/areas
     *      {
     *          pid : 0
     *      }
     * @apiUse CODE_200
     *
     */
    public function areas(Request $request) {

        if($request->has('pid')){
            $pid = $request->get('pid');
            $areas = $this->_model->areas($pid);

        }else{
            $areas = $this->_model->allAreas();
        }

        return response()->json(Message::setResponseInfo('SUCCESS' , $areas));


    }

    /**
     * @api {POST} /gamma/store/settling 申请入驻
     * @apiName settling
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 申请入驻
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/settling
     *
     * @apiParam {sting} name 姓名
     * @apiParam {string} contact 联系电话
     * @apiParam {number} province 省ID
     * @apiParam {number} city 市/区ID
     * @apiParam {number} county 区/县ID
     * @apiParam {string} address 详细地址
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/login
     *      {
     *          "name" : '吴辉',
     *          "contact" : '18401586654',
     *          "province" : 1,
     *          "city" : 36,
     *          "county" : 41,
     *          "address" : "融科望京中心11栋1102"
     *      }
     * @apiUse CODE_200
     *
     */
    public function settling(Request $request) {

        $validation = Validator::make($request->all(), [
            'name'          => 'required|max:40',
            'contact'       => 'required|max:11',
            'province'      => 'required',
            'city'          => 'required',
            'county'        => 'required',
        ]);

        //|match:/^1[34578]\d{9}$/
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }
        $data = array();

        $data['name']       = $request->get('name');
        $data['province']   = $request->get('province');
        $data['city']       = $request->get('city');
        $data['county']     = $request->get('county');
        $data['address']    = $request->get('address');
        $data['contact']    = $request->get('contact');
        $data['created_at'] = date('Y-m-d H:i:s' , time());
        $data['updated_at'] = date('Y-m-d H:i:s' , time());

        if($this->_model->setting($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }


    /**
     * @api {POST} /gamma/store/config 配置店铺
     * @apiName storeConfig
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 配置店铺
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/config
     *
     * @apiParam {json} config 配置;可选值有<br/>
     *              店铺logo   store_logo<br/>
     *              起送价     start_price<br/>
     *              配送费     deliver<br/>
     *              铃声       bell<br/>
     *              是否打烊   is_close{0为打烊|1打烊了}<br/>
     *              营业时间   business_time{08:00-20:00}<br/>
     *              营业周期   business_cycle{星期一,星期二....}<br />
     *              公告      notice  
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/login
     *      {
     *          "config" : {"store_logo":"http:\/\/xxx.png","start_price":"20.00","deliver":"3.00","bell":"套马的汉子","is_close":1,"business_time":"08:00-20:00","business_cycle":"\u661f\u671f\u4e00,\u661f\u671f\u4e8c,\u661f\u671f\u4e09"}
     *      }
     * @apiUse CODE_200
     *
     */
    public function config( Request $request) {
        /**
        $validation = Validator::make($request->all(), [
            'field' => 'required|max:255|in:store_logo,start_price,deliver,business_cycle,business_time,is_close',
            'value' => 'required|max:55',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }*/

        $config = (array) json_decode($request->get('config'));
        $config['updated_at'] = date('Y-m-d H:i:s');

        if($this->_model->config($this->storeId , $config)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /gamma/store/goods/add 添加商品
     * @apiName storeGoodsAdd
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 添加商品
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods/add
     *
     * @apiParam {string} img 商品图片
     * @apiParam {string} name 商品名称
     * @apiParam {number} c_id 分类ID
     * @apiParam {number} b_id 品牌ID
     * @apiParam {number} nav_id 栏目ID
     * @apiParam {string} spec 规格
     * @apiParam {number} out_price 销售单价
     * @apiParam {string} [desc] 商品描叙
     * @apiParam {string} [give_points] 赠送积分
     * @apiParam {number} [stock] 库存
     *              '
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods/add
     *      {
     *          'img'       : 'http://xxx.png',
     *          'name'      : '测试商品一',
     *          'c_id'      : 1,
     *          'b_id'      : 1,
     *          'nav_id'    : 1,
     *          'spec'      : '瓶',
     *          'out_price' : '30.00',
     *          'desc'      : '这是测试商品的描叙',
     *          'give_points' : '0',
     *          'stock' : '100'
     *      }
     * @apiUse CODE_200
     *
     */
    public function addGoods(Request $request){

        $validation = Validator::make($request->all(), [
            'nav_id'    => 'required',
            //'c_id'      => 'required',
            'b_id'      => 'required',
            'name'      => 'required',
            'img'       => 'required',
            'spec'      => 'required',
            'out_price' => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();
        $data['store_id']       = $this->storeId;
        $data['nav_id']         = trim($request->get('nav_id'));
        $data['c_id']           = trim($request->get('c_id' , 0));
        $data['b_id']           = trim($request->get('b_id'));
        $data['name']           = htmlspecialchars(trim($request->get('name')));
        $data['img']            = trim($request->get('img'));
        $data['spec']           = trim($request->get('spec'));
        $data['out_price']      = trim($request->get('out_price'));
        $data['give_points']    = trim($request->get('give_points'));
        $data['stock']          = trim($request->get('stock'));
        $data['desc']           = htmlspecialchars(trim($request->get('desc' , '')));
        $data['created_at']     = date('Y-m-d H:i:s' , time());
        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        if($this->_model->addGoods($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /gamma/store/goods[?page=1] 获取商品列表
     * @apiName storeGoods
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取商品列表
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods?page=1
     *
     * @apiParam {string} [is_open] 上架1/下架/0
     * @apiParam {string} [name] 商品名称搜索
     * @apiParam {string} [nav_id] 栏目搜索
     * @apiParam {string} [sort_stock] 库存排序(desc/asc)
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods?page=1
     *      {
     *          'is_open' : 1,
     *          'name'  : '测试',
     *          'nav_id'  : '1',
     *          'sort_stock' : 'desc'
     *      }
     * @apiUse CODE_200
     *
     */
    public function getGoodsList(Request $request){
        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $search['is_open'] = $request->input('is_open');
        if($search['is_open'] == ''){
            $search['is_open'] = 1;
        }
        if($request->has('name')){
            $search['name'] = htmlspecialchars($request->get('name'));
        }
        if($request->has('nav_id') && !$request->get('nav_id') == 0 ){
            $search['nav_id'] = trim($request->get('nav_id'));
        }

        if($request->has('sort_stock') && !empty($request->get('sort_stock'))){
            $search['sort_stock'] = trim($request->get('sort_stock'));
        }

        $goodsNum   = $this->_model->getGoodsTotalNum($this->storeId , $search);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $goodsNum);
        $response['goodsList']  = $this->_model->getGoodsList($this->storeId , $search , $this->_length , $response['pageData']->offset);

        return response()->json(Message::setResponseInfo('SUCCESS' , $response));

    }

    /**
     * @api {POST} /gamma/store/goods/info/{id} 获取单个商品信息
     * @apiName storeGoodsInfo
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取单个商品信息
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods/info/1
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods/info/1
     *      {
     *
     *      }
     * @apiUse CODE_200
     *
     */
    public function getGoodsInfo($id){

        $info = $this->_model->getGoodsList($this->storeId , array('id' => $id) , $this->_length , 0);

        if(count($info) == 0){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('SUCCESS' , $info[0]));
        }


    }

    /**
     * @api {POST} /gamma/store/goods/update/{id} 修改商品
     * @apiName storeGoodsUpdate
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 修改商品
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods/update/1
     *
     * @apiParam {string} [img] 商品图片
     * @apiParam {string} [name] 商品名称
     * @apiParam {number} [b_id] 品牌ID
     * @apiParam {number} [nav_id] 栏目ID
     * @apiParam {string} [spec] 规格
     * @apiParam {number} [out_price] 销售单价
     * @apiParam {number} [give_points] 赠送积分
     * @apiParam {string} [desc] 商品描叙
     * @apiParam {number} [stock] 库存
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods/update/1
     *      {
     *          'img'       : 'http://xxx.png',
     *          'name'      : '测试商品一',
     *          'b_id'      : 1,
     *          'nav_id'    : 1,
     *          'spec'      : '瓶',
     *          'out_price' : '30.00',
     *          'give_points' : '0',
     *          'desc'      : '这是测试商品的描叙',
     *          'stock'      : '库存',
     *          'is_open'   : '是否上下架',
     *          'is_del'    : '是否删除'
     *      }
     * @apiUse CODE_200
     *
     */
    public function updateGoods($id , Request $request){

        $data = array();
        if( $request->has('nav_id') ) {
            $data['nav_id'] = trim($request->get('nav_id'));
        }
//        if( $request->has('c_id') ){
//            $data['c_id'] = trim($request->get('c_id'));
//        }
        if( $request->has('b_id') ) {
            $data['b_id'] = trim($request->get('b_id'));
        }
        if( $request->has('name') ) {
            $data['name'] = htmlspecialchars(trim($request->get('name')));
        }
        if( $request->has('img') ) {
            $data['img'] = trim($request->get('img'));
        }
        if( $request->has('spec') ) {
            $data['spec'] = trim($request->get('spec'));
        }
        if( $request->has('out_price') ) {
            $data['out_price'] = trim($request->get('out_price'));
        }
        if( $request->has('desc') ) {
            $data['desc'] = htmlspecialchars(trim($request->get('desc', '')));
        }
        if( $request->has('is_open') ) {
            $data['is_open'] = htmlspecialchars(trim($request->get('is_open')));
        }
        if( $request->has('is_del') ) {
            $data['is_del'] = htmlspecialchars(trim($request->get('is_del')));
        }
        if( $request->has('give_points') ) {
            $data['give_points'] = htmlspecialchars(trim($request->get('give_points')));
        }

        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        if($this->_model->updateGoods($this->storeId,  $id , $data )){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /gamma/store/goods/opens 商品批量上下架
     * @apiName storeGoodsOpen
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 商品批量上下架
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods/opens
     *
     * @apiParam {json} ids 商品IDS
     * @apiParam {number} [is_open] 上架1或下架0的状态
     *
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods/opens
     *      {
     *             ids : "[1,2,3,4,5]",
     *             is_open : 1
     *      }
     * @apiUse CODE_200
     *
     */
    public function opens(Request $request){

        $data = array();
        $ids                    = json_decode($request->get('ids'));

        $data['is_open']        = $request->get('is_open');
        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        if($this->_model->updateUtatus($this->storeId,  $ids , $data )){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /gamma/store/goods/dels 商品批量删除
     * @apiName storeGoodsDel
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 商品批量删除
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods/dels
     *
     * @apiParam {array} ids 商品IDS
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods/dels
     *      {
     *             ids : [1,2,3,4,5],
     *      }
     * @apiUse CODE_200
     *
     */
    public function dels(Request $request){

        $data = array();

        $ids                    = $request->get('ids');

        $data['is_del']        = 1;
        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        if($this->_model->updateUtatus($this->storeId,  $ids , $data )){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }

    }

    /**
     * @api {POST} /gamma/store/goods/categories/{pid} 获取商品分类
     * @apiName storeGoodsCategories
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取商品分类
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods/categories/0
     *
     * @apiParam {string} [pid] 父级ID
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods/categories/0
     *      {
     *          'pid' : 0,
     *      }
     * @apiUse CODE_200
     *
     */
    public function getGoodsCategories($pid){

        $categoriesList = $this->_model->getGoodsCategories($pid);
        return response()->json(Message::setResponseInfo('SUCCESS' , $categoriesList));
    }

    /**
     * @api {POST} /gamma/store/goods/brand 获取商品品牌
     * @apiName storeGoodsBrand
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取商品品牌
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/goods/brand
     *
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods/brand
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getGoodsBrand(){

        $brandList = $this->_model->getGoodsBrand();
        return response()->json(Message::setResponseInfo('SUCCESS' , $brandList));
    }

    /**
     * @api {POST} /gamma/store/nav/add 添加栏目
     * @apiName storeNavAdd
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 添加栏目
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/nav/add
     *
     * @apiParam {string} name 栏目名称
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/nav/add
     *      {
     *          'name'      : '测试商品一',
     *      }
     * @apiUse CODE_200
     *
     */
    public function addNav(Request $request){
        $validation = Validator::make($request->all(), [
            'name'      => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();

        $data['store_id']       = $this->storeId;
        $data['name']           = $request->get('name');
        $data['created_at']     = date('Y-m-d H:i:s' , time());
        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        $navId = $this->_model->addNav($data);

        if($navId){
            return response()->json(Message::setResponseInfo('SUCCESS' , $navId));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /gamma/store/nav/update/{id} 更新栏目
     * @apiName storeNavUpdate
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 添加栏目
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/nav/update/1
     *
     * @apiParam {string} [name] 栏目名称
     * @apiParam {number} [sort] 排序
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/nav/update/1
     *      {
     *          'name'      : '测试商品一',
     *      }
     * @apiUse CODE_200
     *
     */
    public function updateNav($navId , Request $request){

        $storeId = $this->storeId;

        $data = array();

        if($request->has('name')){
            $data['name']           = $request->get('name');
        }
        if($request->has('sort')){
            $data['sort']           = $request->get('sort');
        }

        $data['updated_at']     = date('Y-m-d H:i:s' , time());

        if($this->_model->updateNav($navId , $storeId , $data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /gamma/store/nav 获取栏目
     * @apiName storeNav
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取栏目
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/nav
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/nav
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function nav(){

        $storeId = $this->storeId;

        $navList = $this->_model->getNav($storeId);

        return response()->json(Message::setResponseInfo('SUCCESS' , $navList));
    }

    /**
     * @api {POST} /gamma/store/nav/goods/del/{nav} 删除栏目商品
     * @apiName storeNavGoods
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 删除栏目商品
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/nav/goods/del
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/nav/goods/del
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function delNavGoods($navId ){

        $storeId = $this->storeId;

        if($this->_model->delNavGoods($navId , $storeId)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAIED'));
        }


    }

    /**
     * @api {POST} /gamma/store/nav/del/{id} 删除栏目
     * @apiName storeNavDel
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 删除栏目
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/nav/del/1
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/nav
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function delNav($navId , Request $request){

        $storeId = $this->storeId;

        $isDel = $this->_model->delNav($navId , $storeId);
        if($isDel == '-1'){
            return response()->json(Message::setResponseInfo('NOT_DELETE'));
        }else if($isDel){
            return response()->json(Message::setResponseInfo('SUCCESS' ));
        }else{
            return response()->json(Message::setResponseInfo('FAILED' ));
        }
    }

    /**
     * @api {POST} /gamma/store/nav/{id} 获取单个栏目
     * @apiName storeNavOne
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取单个栏目
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/nav/1
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/nav
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getNavInfo($navId){

        $storeId = $this->storeId;

        $info = $this->_model->getNavInfo($navId , $storeId);

        return response()->json(Message::setResponseInfo('SUCCESS' , $info));
    }

    /**
     * @api {POST} /gamma/store/count/today 获取今日统计
     * @apiName storeCountToday
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取今日统计
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/count/today
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/count/today
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreTodayCount(){
        $date = date('Y-m-d');

        $storeId = $this->storeId;

        $visitingNumber = $this->_model->getTodayStoreCount($storeId, $date);

        $visitingNumberData = 0;
        foreach ($visitingNumber as $v){
            $visitingNumberData += $v->visiting_number;
        }

        $orderModel = new Orders;
        $orderCount = $orderModel->getOrderTodayCounts($storeId , $date);

        return response()->json(Message::setResponseInfo('SUCCESS' , array(
            'visiting_number'       => $visitingNumberData,
            'order_num'             => $orderCount[0]->order_num ? $orderCount[0]->order_num : 0,
            'turnover'              => $orderCount[0]->turnover ? $orderCount[0]->turnover : 0
        )));
    }

    /**
     * @api {POST} /gamma/store/month/points 获取本月的积分
     * @apiName storeMonthPoints
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取本月的积分
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/month/points
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/month/points
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getStoreMonthPoint(){
        $date = date('Y-m');

        $storeId = $this->storeId;

        $storeInfo = $this->_model->getStoreInfo($storeId);

        $orderModel = new Orders;
        $orderCount = $orderModel->getOrderMonthPoint($storeId , $date);

        return response()->json(Message::setResponseInfo('SUCCESS' , array(
            'usable_points'          => $storeInfo->point ? $storeInfo->point : 0,
            'out_points'             => $orderCount[0]->out_points ? $orderCount[0]->out_points : 0,
            'in_points'              => $orderCount[0]->in_points ? $orderCount[0]->in_points : 0
        )));
    }

    public function ajaxFinanceCount(Request $request){

        $type = $request->get('type' , 1);

        $year   = date('Y');
        $month  = date('m');
        $day    = date('d');
        $hour   = date('H');

        if($type == 1) {
            //本天
            $today = $this->_model->financeCountByDay($this->storeId, $year, $month, $day);

            $todayTime = array(
                '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'
            );

            $todayTurnover = array();

            for ($i = 0; $i < count($todayTime); $i++) {
                $todayTurnover[$i] = 0;
                foreach ($today as $t) {
                    if ($t->hour == $todayTime[$i]) {
                        $todayTurnover[$i] = $t->turnover;
                    }
                }
            }

            $todayData = array(
                'time' => $todayTime,
                'turnover' => $todayTurnover
            );
            $response = array(
                'today' => $todayData,
            );
        }elseif($type == 2) {


            //获取本周日期
            $sdefaultDate = date("Y-m-d");
            $first = 1;
            $w = date('w', strtotime($sdefaultDate));

            $day = array();
            $weekTime = array(
                '周一',
                '周二',
                '周三',
                '周四',
                '周五',
                '周六',
                '周日',
            );
            $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));

            $day[] = explode('-', $week_start)[2];

            for ($i = 1; $i < 7; $i++) {
                $weektemp = date('Y-m-d', strtotime("$week_start + $i days"));
                $day[] = explode('-', $weektemp)[2];
            }

            $week = $this->_model->financeCountByWeek($this->storeId, $year, $month, implode(',', $day));
            $weekTurnover = array();
            for ($i = 0; $i < count($weekTime); $i++) {
                $weekTurnover[$i] = 0;
                foreach ($week as $w) {
                    if ($w->day == $day[$i]) {
                        $weekTurnover[$i] = $w->turnover;
                    }
                }
            }

            $weekData = array(
                'time' => $weekTime,
                'turnover' => $weekTurnover
            );
            $response = array(
                'week' => $weekData,
            );
        }elseif($type == 3) {


            $dayTimes = date('j', mktime(0, 0, 1, ($month == 12 ? 1 : $month + 1), 1, ($month == 12 ? $year + 1 : $year)) - 24 * 3600);

            $month = $this->_model->financeCountByMonth($this->storeId, $year, $month);
            $monthTurnover = array();
            for ($i = 1, $j = 0; $i <= $dayTimes, $j <= $dayTimes; $i++, $j++) {
                $monthTime[] = $i;
                $monthTurnover[$j] = 0;
                foreach ($month as $m) {
                    if ($i == $m->day) {
                        $monthTurnover[$j] = $m->turnover;
                    }
                }
            }

            $monthData = array(
                'time' => $monthTime,
                'turnover' => $monthTurnover
            );
            $response = array(
                'month' => $monthData
            );
        }


        return response()->json(Message::setResponseInfo('SUCCESS' , $response));

    }

    public function financeCount(){
        $orderModel = new Orders;
        $orderCount = $orderModel->getOrderCounts($this->storeId);
        return view('count');
    }
}