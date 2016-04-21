<?php

namespace App\Http\Controllers;

use Session , Cookie , Config;

class ApiController extends Controller
{
    /**
     * @apiDefine CODE_200
     * @apiSuccess (Reponse 200) {number} code 0000
     * @apiSuccess (Reponse 200) {string} msg "请求成功"
     * @apiSuccess (Reponse 200) {json} [data='""'] 如果有数据返回
     * @apiSuccess (Reponse 200) {string} cookie jisux_store_app
     * @apiSuccessExample {json} Response 200 Example
     *   HTTP/1.1 200 OK
     *   {
     *     "code" : 0000,
     *     "msg"  : "请求成功",
     *     "data": ""
     *   }
     *  "headers": {
     *          "Set-Cookie": "jisux_store_app=eyJpdiI6IkYrTEhXUVFJb3RXTHo1KzdCTEZoUHc9PSIsInZhbHVlIjoiQTZZT3pFbm05azRIMUNxWUE0emZpTEZpRVVrS29wcCtSK0U0aFZOZndOTT0iLCJtYWMiOiJjYzc0NjYwODI1NTJiOWI5ZGMyZDRkODY4YWYyZjk2ZjEwODIwOTMzMDQ3YzhjYzg3NTU0MjdkZDE1OGEyZTI0In0%3D"
     *   }
     */
    protected $response = array();
    protected $storeId = 0;

    public function __construct(){

        global $userInfo;
        $this->userId = isset($userInfo->id) ? $userInfo->id : 0;
        $this->storeId = isset($userInfo->store_id) ? $userInfo->store_id : 0;

    }

}
