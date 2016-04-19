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
     * @api {POST} /gamma/store/config/{id} 配置店铺
     * @apiName storeConfig
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 配置店铺
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/config/1
     *
     * @apiParam {json} config 配置;可选值有<br/>
     *              店铺logo   store_logo<br/>
     *              起送价     start_price<br/>
     *              配送费     deliver<br/>
     *              铃声       bell<br/>
     *              是否打烊   is_close{0为打烊|1打烊了}<br/>
     *              营业时间   business_time{08:00-20:00}<br/>
     *              营业周期   business_cycle{星期一,星期二....}'
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
     *          'desc'      : '这是测试商品的描叙'
     *      }
     * @apiUse CODE_200
     *
     */
    public function addGoods(Request $request){

        $validation = Validator::make($request->all(), [
            'nav_id'    => 'required',
            'c_id'      => 'required',
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
        $data['nav_id']         = 1;
        $data['c_id']           = 1;
        $data['b_id']           = 1;
        $data['name']           = $request->get('name');
        $data['img']            = $request->get('img');
        $data['spec']           = $request->get('spec');
        $data['out_price']      = $request->get('out_price');
        $data['desc']           = $request->get('desc' , '');
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
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/goods?page=1
     *      {
     *          'is_open' : 1,
     *          'name'  : '测试',
     *      }
     * @apiUse CODE_200
     *
     */
    public function getGoodsList(Request $request){
        $search = array();

        $search['is_open'] = $request->input('is_open');
        if($search['is_open'] == ''){
            $search['is_open'] = 1;
        }
        if($request->has('name')){
            $search['name'] = $request->get('name');
        }

        $goodsList = $this->_model->getGoodsList($this->storeId , $this->_length , $search);


        return response()->json(Message::setResponseInfo('SUCCESS' , $goodsList));

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
     *              '
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

        if($this->_model->addNav($data)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
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
     * @apiParam {string} name 栏目名称
     *              '
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
        $validation = Validator::make($request->all(), [
            'name'      => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $storeId = $this->storeId;

        $data = array();

        $data['name']           = $request->get('name');
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
    public function delNav($navId){

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
}