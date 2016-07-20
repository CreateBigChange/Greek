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
use Validator , Input , RedisClass as Redis;
use Session , Cookie , Config , Log;

use App\Http\Controllers\ApiController;

use App\Models\Coupon;
use App\Libs\Message;
use App\Libs\BLogger;

class CouponController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model = new Coupon;
        $this->_length		= 10;
    }

    /**
     * @api {POST} /gamma/store/coupon/add 发布优惠卷
     * @apiName couponAdd
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 发布优惠卷
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/coupon/add
     *
     * @apiParam {string} name 优惠券名称
     * @apiParam {folat} value 券价值
     * @apiParam {int} effective_time 有效时长
     * @apiParam {int} [prerequisite] 条件
     * @apiParam {int} total_num 总数量
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/coupon/add
     *      {
     *          'name'              : "店铺专享券",
     *          'value'             : "3.3",
     *          'effective_time'    : 3,
     *          'prerequisite'         : 50,
     *          'total_num'         : 1000,
     *          'content'           : "满20可用"
     *      }
     * @apiUse CODE_200
     *
     */
    public function addCoupon(Request $request){
        $validation = Validator::make($request->all(), [
            'name'                      => 'required',
            'value'                     => 'required',
            'effective_time'            => 'required',
            'total_num'                 => 'required',
            'content'                   => 'required',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();
        $data['name']               = $request->get('name');
        $data['value']              = $request->get('value');
        $data['effective_time']     = $request->get('effective_time');
        $data['content']            = $request->get('content');

        if($request->has('prerequisite')) {
            $data['prerequisite'] = $request->get('prerequisite');
        }

        $data['total_num']          = $request->get('total_num');
        $data['num']                = $request->get('total_num');
        $data['store_id']           = $this->storeId;
        $data['type']               = 1;
        $data['created_at']         = date('Y-m-d H:i:s' , time());
        $data['updated_at']         = date('Y-m-d H:i:s' , time());

        $couponId = $this->_model->addCoupon($data);
        
        if($couponId){
            return response()->json(Message::setResponseInfo('SUCCESS' , $couponId));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

    /**
     * @api {POST} /gamma/store/coupon/list 获取优惠卷列表
     * @apiName couponList
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取优惠卷列表
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/coupon/list
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/coupon/list
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getCouponList(){

        $search = array();

        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $search['store_id'] = $this->storeId;

        $couponNum   = $this->_model->getCouponTotalNum($search);

        $response = array();
        $response['pageData']   = $this->getPageData($page , $this->_length , $couponNum);

        $response['coupon']     = $this->_model->getCouponList($search , $this->_length , $response['pageData']->offset);
        
        return response()->json(Message::setResponseInfo('SUCCESS' , $response));
    }

    /**
     * @api {POST} /gamma/store/coupon/info/{couponId} 获取优惠卷详情
     * @apiName couponInfo
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 获取优惠卷详情
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/coupon/info/1
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/coupon/info/1
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getCouponInfo($couponId){

        $search = array();

        $search['store_id'] = $this->storeId;
        $search['id']       = $couponId;

        $coupon     = $this->_model->getCouponList($search);

        return response()->json(Message::setResponseInfo('SUCCESS' , $coupon));
    }

    /**
     * @api {POST} /gamma/store/coupon/stop/{couponId} 开启和关闭优惠卷
     * @apiName couponStop
     * @apiGroup GAMMA
     * @apiVersion 1.0.0
     * @apiDescription 开启和关闭优惠卷
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/gamma/store/coupon/stop/1
     *
     * @apiParam {int} stop_out 1   1停止发放   0继续发放
     *
     * @apiParamExample {json} Request Example
     *      POST /gamma/store/coupon/stop/1
     *      {
     *          stop_out : 1
     *      }
     * @apiUse CODE_200
     *
     */
    public function stopCoupon($couponId , Request $request){

        $validation = Validator::make($request->all(), [
            'stop_out'                      => 'required|in:0,1',
        ]);
        if($validation->fails()){
            return response()->json(Message::setResponseInfo('PARAMETER_ERROR'));
        }

        $data = array();

        $data['stop_out'] = $request->get('stop_out');

        $coupon     = $this->_model->stopCoupon($couponId , $data);

        if($coupon) {
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }


}