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
        $this->length=10;
    }

    public function bannerVersion(Request $request){

        $page = 1;

        if($request->has('page')){
            $page = $request->get('page');
        }
        $totalNum = $this->banner->bannerVersionTotalNum();
        $pageData = $this->getPageData($page  , $this->length, $totalNum);
        $this->response['list']=$this->banner->getBannerList();
        $this->response['pageHtml'] = $this->getPageHtml($page , $pageData->totalPage , '/alpha/activity/banner?');
        
        return view('alpha.activity.banner',$this->response);
    }

    public function save(Request $request)
    {


        $data['str']=$request->query('str');
        $data['id']=$request->query('id');
        $data['redirect']=$request->query('redirect');
        $data['name']=$request->query('name');
        $data['create_time']=$request->query('create_time');
        $data['update_time']=$request->query('update_time');
        $data['is_open']=$request->query('is_open');
        $data['order']=$request->query('order');
        $data['img']=$request->query('img');
        $method = $request->query('method');


        $sign= $this->banner->saveBanner($data,$method);
        $page = 1;

        if($request->has('page')){
            $page = $request->get('page');
        }
        $totalNum = $this->banner->bannerVersionTotalNum();
        $pageData = $this->getPageData($page  , $this->length, $totalNum);

        $this->response['pageHtml'] = $this->getPageHtml($page , $pageData->totalPage , '/alpha/activity/banner?');            

         $this->response['list']=$this->banner->getBannerList();

         return redirect('/alpha/Activity/bannerVersions/');
       
    }
}

?>