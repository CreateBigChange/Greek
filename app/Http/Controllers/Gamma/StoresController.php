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
     * @api {POST} /gamma/store/config/[:id] 配置店铺
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
    public function config($storeId , Request $request) {
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

        if($this->_model->config($storeId , $config)){
            return response()->json(Message::setResponseInfo('SUCCESS'));
        }else{
            return response()->json(Message::setResponseInfo('FAILED'));
        }
    }

}