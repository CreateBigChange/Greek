<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/15
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Alpha;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AdminController;

use Session , Cookie , Config;

use App\Models\AndroidVersion;
use App\Libs\Message;

class AppVersionController extends AdminController
{
    private $_length;


    public function __construct(){
        parent::__construct();
        $this->_length		= 20;
        $this->response['title']		= '版本管理';
        $this->response['menuactive']	= 'version';
    }


    public function addApkVersion(Request $request){

        if(!$request->has('version') || !$request->has('download') ){
            return view('errors.503');
        }

        $versionModel = new AndroidVersion();

        $versionModel->version        = $request->get('version');
        $versionModel->download       = $request->get('download');

        if($versionModel->save()){
            return redirect('alpha/app/version');
        }

    }

    public function getApkVersion(Request $request){

        $version = AndroidVersion::orderBy('version' , 'desc')->get();

        $this->response['version'] = $version;
        return view('alpha.app.version' , $this->response);

    }



}