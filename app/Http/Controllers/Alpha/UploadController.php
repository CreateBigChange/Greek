<?php

namespace App\Http\Controllers\Alpha;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Input;

use App\Http\Requests;
use App\Http\Controllers\AdminController;

use App\Libs\Message;

class UploadController extends AdminController
{

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


}
