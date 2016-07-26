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
use App\Models\Coupon;

class ActivityController extends AdminController
{

    private $length;
    private $_model;
    private $banner;
    private $couponModel;

    public function __construct(){
        parent::__construct();
        $this->_model = new User();
        $this->banner = new Banner();
        $this->couponModel = new Coupon();
        $this->response['title']		= '活动';
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
        $data['create_time']=date('Y-m-d H:i:s',time());
        $data['update_time']=date('Y-m-d H:i:s',time());
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

        return redirect('/alpha/Activity/bannerVersion');    
       
    }

    //通用优惠券的控制器
    public function coupon(Request $request)
    {


       
        $page = 1;
        if($request->has('page')){
            $page = $request->get('page');
        }
        $search['strore_id']=0;
        $totalNum =  $this->couponModel->couponTotalNum();
        $pageData = $this->getPageData($page  , $this->length, $totalNum);
        $this->response['list']= $this->couponModel->getCouponList($search,$this->length,$pageData->offset);
        $this->response['pageHtml'] = $this->getPageHtml($page , $pageData->totalPage , '/alpha/Activity/coupon?');
        
        return view('alpha.activity.coupon',$this->response);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 增加优惠券
     */
    public function couponAdd(Request $request)
    {
            $data =  array(
            'name' => $request->get('name'),
            'content' => $request->get('content'),
            'type' => $request->get('type'),
            'effective_time' => $request->get('effective_time'),
            'value' => $request->get('value'),
            'prerequisite' => $request->get('prerequisite'),
            'total_num' => $request->get('total_num'),
            'in_num' => 0,
            'out_num' => 0,
            'num' => $request->get('total_num'),
             'created_at'=>date('Y-m-d H:i:s' , time()),
             'updated_at'=>date('Y-m-d H:i:s' , time())
            );
        if( $request->get('stop_out')=='on')
            $data['stop_out']=0;
        else
            $data['stop_out']=1;
       $this->couponModel->addCouponOther($data);
       return redirect('/alpha/Activity/coupon');  
    }

    /**
     * @param  int  $id  优惠券的id
     * 打开优惠券
     * stop_out =1 表示关闭
     * stop_out =0 表示开启
     */
    public function  couponOpen($id){
        dd($id);
        $data = array("stop_out"=>0);
        $this->couponModel->stopCoupon($id,$data);
        return redirect('/alpha/Activity/coupon');
    }

    /**
     * @param int $id 优惠券的id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 关闭优惠券
     * stop_out =1 表示关闭
     * stop_out =0 表示开启
     */
    public function  couponClose($id){

        $data = array("stop_out"=>1);
        $this->couponModel->stopCoupon($id,$data);

        return redirect('/alpha/Activity/coupon');
    }
}

?>