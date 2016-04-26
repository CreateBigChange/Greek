<?php

namespace App\Http\Controllers\Alpha;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Input;

use App\Http\Requests;
use App\Http\Controllers\AdminController;

use App\Libs\Message;

use Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class UploadController extends AdminController
{

    /**
     * @param Request $request
     * @return mixed
     * 上传本地服务器
     */
    public function uploadImg(Request $request) {

        if($request->hasFile('img')){
            $destinationPath	= './upload/';
            $filename			= time() + mt_rand(1000 , 9999);

            $exten = substr($request->file('img')->getClientOriginalName()  , strpos($request->file('img')->getClientOriginalName() , '.' ) + 1 );

            if( $request->file('img')->isValid() ){
                if( $request->file('img')->move($destinationPath , $filename . '.' . $exten )  ){
                    $filePath = 'http://'.$_SERVER['SERVER_NAME'].'/upload/'.$filename.'.'.$exten;
                    return response()->json( Message::setResponseInfo( 'SUCCESS' , $filePath ) );
                }
            }
        }
        return response()->json( Message::setResponseInfo( 'FAILED' ) );
    }

    /**
     *
     * 上传到七牛服务器
     */
    public function uploadQiniu(Request $request){
        if($request->hasFile('img')){
            $destinationPath	= './upload/';
            $filename			= time() + mt_rand(1000 , 9999);

            $exten = substr($request->file('img')->getClientOriginalName()  , strpos($request->file('img')->getClientOriginalName() , '.' ) + 1 );

            if( $request->file('img')->isValid() ){
                // 要上传文件的本地路径
                $path = $request->file('img')->getPathname();

                // 构建鉴权对象
                $auth = new Auth(Config::get('qiniu.Access_Key'), Config::get('qiniu.Secret_Key'));

                // 生成上传 Token
                $token = $auth->uploadToken(Config::get('qiniu.bucket'));

                // 上传到七牛后保存的文件名
                $key = $filename.'.'.$exten;

                // 初始化 UploadManager 对象并进行文件的上传
                $uploadMgr = new UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传
                list($ret, $err) = $uploadMgr->putFile($token, $key, $path);
                if ($err !== null) {
                    return response()->json( Message::setResponseInfo( 'FAILED' ) );
                } else {
                    $ret['host'] = Config::get('qiniu.host');
                    return response()->json( Message::setResponseInfo( 'SUCCESS'  , $ret) );
                }
//                if( $request->file('img')->move($destinationPath , $filename . '.' . $exten )  ){
//                    $filePath = 'http://'.$_SERVER['SERVER_NAME'].'/upload_qiniu/'.$filename.'.'.$exten;
//                    return response()->json( Message::setResponseInfo( 'SUCCESS' , $filePath ) );
//                }
            }
            return response()->json( Message::setResponseInfo( 'FAILED' ) );
        }
        return response()->json( Message::setResponseInfo( 'FAILED' ) );
    }

}
