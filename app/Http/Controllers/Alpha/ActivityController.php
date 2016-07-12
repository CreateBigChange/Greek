<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/3/30
 * Time: 下午5:10
 */
namespace App\Http\Controllers\Alpha;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AdminController;

use App\Models\User;
use App\Libs\Message;
use App\Models\Banner;

class ActivityController extends AdminController
{

    private $length;
    private $_model;
    private $banner;

    public function __construct(){
        parent::__construct();
        $this->_model = new User();
        $this->banner = new Banner();
        $this->response['title']		= 'bannerVersions';
      
    }

    public function bannerVersion(){
        $this->response['list']=$this->banner->getBannerList();
       // dump(  $this->response);
        return view('alpha.activity.banner',$this->response);
    }

    public function save(Request $request)
    {
       // dump($request);
        $sign= $this->banner->saveBanner($request->query('str'),$request->query('id'));
         $this->response['list']=$this->banner->getBannerList();
         return  view('alpha.activity.banner',$this->response);
    }
}

?>