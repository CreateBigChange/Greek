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

use App\Models\Sigma\Activity;

class ActivityController extends ApiController
{
    private $_model;
    private $_length;

    public function __construct(){
        parent::__construct();
        $this->_model       = new Activity;
        $this->_length		= 20;
    }



    /**
     * @api {POST} /sigma/banner/list 获取轮播图列表
     * @apiName bannerList
     * @apiGroup SIGMA
     * @apiVersion 1.0.0
     * @apiDescription just a test
     * @apiPermission anyone
     * @apiSampleRequest http://greek.test.com/sigma/banner/list
     *
     * @apiParamExample {json} Request Example
     *      POST /sigma/banner/list
     *      {
     *      }
     * @apiUse CODE_200
     *
     */
    public function getBannerList() {

        return response()->json(Message::setResponseInfo('SUCCESS' , $this->_model->getBannerList()));
    }




}