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

use Config;

use App\Models\StoreInfo;
use App\Models\StoreConfig;
use App\Models\StoreBankCard;
use App\Models\StoreCategory;
use App\Models\StoreSettlings;
use App\Models\StoreUser;
use App\Libs\Message;
use App\Libs\Curl;
use App\Models\GoodsBrand;
use App\Models\StoreGoods;

use App\Models\AmapCityCode;

class StoresController extends AdminController
{

	private $length;
    public function __construct(){
        parent::__construct();
        $this->response['title']		= '店铺管理';
		$this->length = 10;
    }

	/**
	 * 获取店铺列表
	 */
	public function getStoreInfoList(){
		
		$storeModel = new StoreInfo;

		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		$search = array();

		$param = '';

		if(isset($_GET['name']) && !empty($_GET['name'])){
			$search['name'] = trim($_GET['name']);
			$param .= 'name=' . $search['name'] . '&';
		}
		if(isset($_GET['contacts']) && !empty($_GET['contacts'])){
			$search['contacts'] = trim($_GET['contacts']);
			$param .= 'contacts=' . $search['contacts'] . '&';
		}
		if(isset($_GET['contact_phone']) && !empty($_GET['contact_phone'])){
			$search['contact_phone'] = trim($_GET['contact_phone']);
			$param .= 'contact_phone=' . $search['contact_phone'] . '&';
		}
		if(isset($_GET['c_id']) && $_GET['c_id'] != 0){
			$search['c_id'] = trim($_GET['c_id']);
			$param .= 'c_id=' . $search['c_id'] . '&';
		}
		if(isset($_GET['province']) && $_GET['province'] != 0){
			$search['province'] = trim($_GET['province']);
			$amapCodeModel = new AmapCityCode();

			$area = $amapCodeModel->getAreas($search['province']);
			if($area){
				$search['province'] = $area->name;
			}
			$param .= 'province=' . $search['province'] . '&';
		}
		if(isset($_GET['city']) && $_GET['city'] != 0){
			$search['city'] = trim($_GET['city']);
			$amapCodeModel = new AmapCityCode();

			$area = $amapCodeModel->getAreas($search['city']);
			if($area){
				$search['city'] = $area->name;
			}
			$param .= 'city=' . $search['city'] . '&';
		}
		if(isset($_GET['county']) && $_GET['county'] != 0){
			$search['county'] = trim($_GET['county']);
			$amapCodeModel = new AmapCityCode();

			$area = $amapCodeModel->getAreas($search['county']);
			if($area){
				$search['county'] = $area->name;
			}
			$param .= 'county=' . $search['county'] . '&';
		}
		if(isset($_GET['address']) && !empty($_GET['address'])){
			$search['address'] = trim($_GET['address']);
			$param .= 'address=' . $search['address'] . '&';
		}
		if(isset($_GET['is_open']) && $_GET['is_open'] != -1){
			$search['is_open'] = trim($_GET['is_open']);
			$param .= 'is_open=' . $search['is_open'] . '&';
		}
		if(isset($_GET['is_checked']) && $_GET['is_checked'] != -1){
			$search['is_checked'] = trim($_GET['is_checked']);
			$param .= 'is_checked=' . $search['is_checked'] . '&';
		}

		/**
		 * 如果登录的用户是代理商
		 */
		if($this->userInfo->is_agent){
			$search['agent_id'] = $this->userInfo->id;
		}
		
		$totalNum = $storeModel->getStoreTotalNum($search);

		$pageData = $this->getPageData($page  , $this->length, $totalNum);

		$this->response['page']         = $pageData->page;
		$this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/stores/infos?' . $param);
		$storeInfos = $storeModel->getStoreList($search  , $this->length , $pageData->offset );
		$this->response['storeInfos'] = $storeInfos;

        return view('alpha.store.info.list' , $this->response);
	}
	/**
	 *
	 * (ajax)获取店铺信息
	 */
	public function ajaxStoreInfo($id){

		$storeModel = new StoreInfo;
		$storeInfo = $storeModel->getStoreInfo($id);

		return response()->json( Message::setResponseInfo( 'SUCCESS' , $storeInfo ) );
	}

	/**
	 * @param Request $request
	 * @return mixed
	 * 修改店铺
	 */
	public function updateStore(Request $request){

		$id = $request->get('id');

		$page = $request->get('page' , 1);

		$data					= array();

		if($request->has('c_id')) {
			$data['c_id'] = $request->get('c_id');
		}
		if($request->has('name')) {
			$data['name'] = $request->get('name');
		}
		if($request->has('description')) {
			$data['description'] = $request->get('description');
		}
		if($request->has('business_license')) {
			$data['business_license'] = $request->get('business_license');
		}
		if($request->has('id_card_img')) {
			$data['id_card_img'] = $request->get('id_card_img');
		}
		if($request->has('province')) {
			$data['province'] = $request->get('province');
		}
		if($request->has('city')) {
			$data['city'] = $request->get('city');
		}
		if($request->has('county')) {
			$data['county'] = $request->get('county');
		}
		if($request->has('address')) {
			$data['address'] = $request->get('address');
		}
		if($request->has('contacts')) {
			$data['contacts'] = $request->get('contacts');
		}
		if($request->has('location')) {
			$data['location'] = $request->get('location');
		}
		if($request->has('contact_phone')) {
			$data['contact_phone'] = $request->get('contact_phone');
		}
		if($request->has('contact_email')) {
			$data['contact_email'] = $request->get('contact_email');
		}
		if($request->has('is_open') && $request->get('is_open') == 'on'){
			$data['is_open']			= 1;
		}else{
			$data['is_open']			= 0;
		}

		if($request->has('is_checked') && $request->get('is_checked') == 'on'){
			$data['is_checked']			= 1;
		}else{
			$data['is_checked']			= 0;
		}

		$data['updated_at']			= date('Y-m-d H:i:s' , time());

		$storeModel = new StoreInfo;

		$storeModel->updateStore($id , $data);

		$config = array();
		if($request->has('store_logo')) {
			$config['store_logo'] = $request->get('store_logo');
		}
		if($request->has('construction_money')) {
			$config['construction_money'] = $request->get('construction_money');
		}
		if($request->has('security_deposit')) {
			$config['security_deposit'] = $request->get('security_deposit');
		}

		$storeConfigModel = new StoreConfig();

		if(!empty($config)) {
			$storeConfigModel->config($id, $config);
		}

		if($request->has('bank_card')) {
			$bankCard = $request->get('bank_card');
			$storeBankModel = new StoreBankCard();
			$storeBankModel->updateBankCard($id , array('bank_card_num'=>$bankCard));
		}

		return redirect('/alpha/stores/infos?page=' .$page );
	}

	/**
	 * @param Request $request
	 * @return mixed
	 * 添加店铺
	 */
	public function addStore(Request $request){

		if(!$request->has('c_id')){
			return view('errors.503');
		}
		if(!$request->has('name')){
			return view('errors.503');
		}
		if(!$request->has('address')){
			return view('errors.503');
		}
		if(!$request->has('contacts')){
			return view('errors.503');
		}
		if(!$request->has('contact_phone')){
			return view('errors.503');
		}

		$data					= array();
		$data['c_id'] 				= $request->get('c_id');
		$data['name']				= $request->get('name');
		$data['description']		= $request->get('description');
		$data['business_license']	= $request->get('business_license');
		$data['id_card_img']		= $request->get('id_card_img');
		$data['province']			= $request->get('province');
		$data['city']				= $request->get('city');
		$data['county']				= $request->get('county');
		$data['address']			= $request->get('address');
		$data['contacts']			= $request->get('contacts');
		$data['location']			= $request->get('location');
		$data['contact_phone']		= $request->get('contact_phone');
		$data['contact_email']		= $request->get('contact_email');
		if($request->has('is_open')){
			$data['is_open']			= 1;
		}else{
			$data['is_open']			= 0;
		}

		if($request->has('is_checked')){
			$data['is_checked']			= 1;
		}else{
			$data['is_checked']			= 0;
		}

		$data['created_at']			= date('Y-m-d H:i:s' , time());
		$data['updated_at']			= date('Y-m-d H:i:s' , time());


		$storeModel = new StoreInfo;

		$storeId = $storeModel->addStore($data);

		if($storeId){

//			$curl = new Curl();
//			/*
//			 *
//			 * 在高德地图上标注店铺
//			 */
//			$param = array(
//				"key" 		=> Config::get('amap.key'),
//				"tableid"	=> Config::get('amap.tableid'),
//				"loctype"	=> "1",
//			);
//
//			$amapAddress = array(
//				"_name"		=> $data['name'],
//				"_location" => $data['location'],
//				"coordtype"	=> 2,
//				"province"	=> $data['province'],
//				"city"		=> $data['city'],
//				"county"	=> $data['county'],
//				"_address"	=> $data['address'],
//				"store_id"	=> $storeId
//			);
//
//			$param['data'] = json_encode($amapAddress);
//
//			$url = Config::get('amap.url');
//
//			$amap = json_decode($curl->post($url , $param));
//
//			if(empty($amap) && $amap->status == '1'){
//				$sign['is_sign'] = 1;
//				$sign['amap_id'] = $amap->_id;
//				$storeModel->updateStore($storeId , $sign);
//			}

			/*
			 *
			 * 生成默认用户
			 */
			$user = array();
			$user['real_name'] 	= $data['contacts'];
			$user['account']	= $data['contact_phone'];
			$user['tel']		= $data['contact_phone'];
			$user['store_id']	= $storeId;
			$user['salt']		= $this->getSalt(8);
			$password			= $this->getSalt(8);
			$user['password']	= $this->encrypt( $password , $user['salt']);
			$user['created_at']	= date('Y-m-d H:i:s' , time());
			$user['updated_at']	= date('Y-m-d H:i:s' , time());


			$storeUserModel = new StoreUser();
			$storeUserModel->addStoreUser($user);

			//更新店铺设置

			$config = array();
			if($request->has('store_logo')) {
				$config['store_logo'] = $request->get('store_logo');
			}
			if($request->has('construction_money')) {
				$config['construction_money'] = $request->get('construction_money');
			}
			if($request->has('security_deposit')) {
				$config['security_deposit'] = $request->get('security_deposit');
			}

			$storeConfigModel = new StoreConfig();

			if(!empty($config)) {
				$storeConfigModel->config($storeId, $config);
			}

			if($request->has('bank_card')) {
				$bankCard = $request->get('bank_card');
				$storeBankModel = new StoreBankCard();
				$storeBankModel->updateBankCard($storeId , array('bank_card_num'=>$bankCard));
			}

			return redirect('/alpha/stores/infos');
		}
	}

	/**
	 * 获取店铺类型
	 */
	public function ajaxStoreCategoriesList(){

		$storeModel = new StoreCategory;

		$storeCategories = $storeModel->getStoreCategory();

		$this->response['storeCategories'] = $storeCategories;

		return $this->response;
	}

	/**
	 * 获取地区
	 */
	public function ajaxAreas(Request $request){

		$level = 'province';

		if($request->has('level')) {
			$level = $request->get('level');
		}

		$amapCodeModel = new AmapCityCode();

		if($level == 'province'){
			$areas = $amapCodeModel->getAreasProvince();
		}elseif($level == 'city'){
			$pid = $request->get('pid');
			$areas = $amapCodeModel->getAreasCity($pid);
		}elseif($level == 'district'){
			$cid = $request->get('cid');
			$areas = $amapCodeModel->getAreasDistrict($cid);
		}

		$this->response['areas'] = $areas;

		return $this->response;
	}

	/**
	 * 获取申请入驻的列表
	 */
	public function getSettlings(){
		$storeSettlingsModel = new StoreSettlings();
		$this->response['settlings'] = $storeSettlingsModel->getSettlings();
		return view('alpha.store.info.settling' , $this->response);
	}

	/**
	 * 完成入驻
	 */
	public function delSettlings($id){
		$storeSettlingsModel = new StoreSettlings();
		if($storeSettlingsModel->delSettlings($id)){
			return redirect('/alpha/stores/settlings');
		}
	}

	/**
	 * 获取店铺用户
	 */
	public function getStoreUserList(Request $request){
		$storeUserModel = new StoreUser();

		$storeId = 0;
		if(isset($_GET['store_id'])){
			$storeId = $_GET['store_id'];
		}

		$this->response['userList'] = $storeUserModel->getStoreUserList($storeId);

		return view('alpha.store.user.list' , $this->response);
	}

	/**
	 * 获取店铺类型
	 */
	public function getStoreCategoriesList(){

		$storeCategoryModel = new StoreCategory();

		$storeCategories = $storeCategoryModel->getStoreCategory();

		$this->response['storeCategories'] = $storeCategories;


;		return view('alpha.store.category.list' , $this->response);
	}

	/**
	 * 添加店铺类型
	 */
	public function addStoreCategory(Request $request){

		$storeCategoryModel = new StoreCategory();

		$data = array();

		if(!$request->has('name')){
			return view('errors.503');
		}

		$data['name'] = $request->get('name');

		if($storeCategoryModel->addStoreCategory($data)){
			return redirect('/alpha/stores/categories/list');
		}

	}

	/**
	 * 修改店铺类型
	 */
	public function updateStoreCategory(Request $request){

		$storeCategoryModel = new StoreCategory();

		$data = array();

		if(!$request->has('id')) {
			return view('errors.503');
		}

		$id = $request->get('id');
		if($request->has('name')) {
			$data['name'] = $request->get('name');
		}

		if($storeCategoryModel->updateStoreCategory($id , $data)){
			return redirect('/alpha/stores/categories/list');
		}

	}

	/**
	 * 获取单个店铺类型
	 */
	public function getStoreCategoryById($id){

		$storeCategoryModel = new StoreCategory();

		$this->response['category'] = $storeCategoryModel->getStoreCategoryById($id);
 	
		return $this->response;

	}


	public function getStoreGoodsList($storeId , Request $request){
		$search = array();

		$param = '';
		if(!isset($_GET['page'])){
			$page = 1;
		}else{
			$page = $_GET['page'];
		}

		$search['is_open'] = $request->input('is_open');
		if($search['is_open'] == ''){
			$search['is_open'] = 1;
		}
		$param .= 'is_open=' . $search['is_open'] . '&';

		if($request->has('name')){
			$search['name'] = htmlspecialchars($request->get('name'));

			$param .= 'name=' . $search['name'] . '&';

		}
		if($request->has('nav_id') && !$request->get('nav_id') == 0 ){
			$search['nav_id'] = trim($request->get('nav_id'));
			$param .= 'nav_id=' . $search['nav_id'] . '&';
		}

		if($request->has('sort_stock') && !empty($request->get('sort_stock'))){
			$search['sort_stock'] = trim($request->get('sort_stock'));
			$search['sort_stock'] = trim($request->get('sort_stock'));
		}

		$search['store_id'] = $storeId;

		$storeGoodsModel = new StoreGoods;
		$goodsNum   = $storeGoodsModel->getStoreGoodsTotalNum($search);

		$this->response['pageData']   		= $this->getPageData($page , $this->length , $goodsNum);
		$this->response['pageHtml']         = $this->getPageHtml($this->response['pageData']->page , $this->response['pageData']->totalPage  , '/alpha/store/goods/'.$storeId.'?' . $param);
		$this->response['goods']  			= $storeGoodsModel->getStoreGoodsList($search , $this->length , $this->response['pageData']->offset);
        $this->response['brand']            =11;


        //dd($this->response);


        return view('alpha.store.goods.list' , $this->response);

	}

	public function getStoreGoodsListByNoCheck(Request $request){
		$search = array();

		$param = '';
		if(!isset($_GET['page'])){
			$page = 1;
		}else{
			$page = $_GET['page'];
		}

		$search['is_open'] = $request->input('is_open');
		if($search['is_open'] == ''){
			$search['is_open'] = 1;
		}
		$param .= 'is_open=' . $search['is_open'] . '&';

		if($request->has('name')){
			$search['name'] = htmlspecialchars($request->get('name'));

			$param .= 'name=' . $search['name'] . '&';

		}

		$search['is_checked'] = 0;

		$storeGoodsModel = new StoreGoods;
		$goodsNum        = $storeGoodsModel->getStoreGoodsTotalNum($search);
        $goodsBrand      =new GoodsBrand;
        $StoreInfo       =new StoreInfo;

		$this->response['pageData']   		= $this->getPageData($page , $this->length , $goodsNum);
		$this->response['pageHtml']         = $this->getPageHtml($this->response['pageData']->page , $this->response['pageData']->totalPage  , '/alpha/store/goods/by/nocheck/?' . $param);
		$this->response['goods']  			= $storeGoodsModel->getStoreGoodsList($search , $this->length , $this->response['pageData']->offset);
        $this->response['store']            =$StoreInfo ->getAllStore();
        for($i=0;$i<count($this->response['goods']);$i++){
            $this->response['goods'][$i]->brand =$goodsBrand->getGoodsBrandByCid($this->response['goods'][$i]->category_id);
        }

		return view('alpha.store.goods.nocheck_list' , $this->response);

	}
    public function  updateStoreGoodsInfo(Request $request){
        $storeGoodsModel = new StoreGoods;
        $store_id       =$request->get("store_id");
        $id             =$request->get("id");
        $data           =array();


        foreach($request->request as $key=>$value)
        {
            if($value!="")
            $data[$key]=$value;
        }


        $storeGoodsModel ->updateGoods($store_id,$id,$data);
        return redirect("/alpha/store/goods/by/nocheck");
    }

	public function getStoreGoodsInfo($goodsId){

		$storeGoodsModel = new StoreGoods;
		$info = $storeGoodsModel->getStoreGoodsList(array('id' => $goodsId ) , $this->length , 0);

		if(count($info) == 0){
			return response()->json(Message::setResponseInfo('SUCCESS'));
		}else{
			return response()->json(Message::setResponseInfo('SUCCESS' , $info[0]));
		}


	}

	public function updateStoreGoods(Request $request){


		$data = array();

		$id 		= $request->get('id');
		$storeId 	= $request->get('store_id');

		if( $request->has('nav_id') ) {
			$data['nav_id'] = trim($request->get('nav_id'));
		}
//        if( $request->has('c_id') ){
//            $data['c_id'] = trim($request->get('c_id'));
//        }
		if( $request->has('b_id') ) {
			$data['b_id'] = trim($request->get('b_id'));
		}
		if( $request->has('name') ) {
			$data['name'] = htmlspecialchars(trim($request->get('name')));
		}
		if( $request->has('img') ) {
			$data['img'] = trim($request->get('img'));
		}
		if( $request->has('spec') ) {
			$data['spec'] = trim($request->get('spec'));
		}
		if( $request->has('out_price') ) {
			$data['out_price'] = trim($request->get('out_price'));
		}
		if( $request->has('stock') ) {
			$data['stock'] = trim($request->get('stock'));
		}
		if( $request->has('desc') ) {
			$data['desc'] = htmlspecialchars(trim($request->get('desc', '')));
		}
		if( $request->has('is_open') ) {
			$data['is_open'] = htmlspecialchars(trim($request->get('is_open')));
		}
		if( $request->has('is_checked') ) {
			$data['is_checked'] = htmlspecialchars(trim($request->get('is_checked')));
		}
		if( $request->has('is_del') ) {
			$data['is_del'] = htmlspecialchars(trim($request->get('is_del')));
		}
		if( $request->has('give_points') ) {
			$data['give_points'] = htmlspecialchars(trim($request->get('give_points')));
		}

		$data['updated_at']     = date('Y-m-d H:i:s' , time());

		$storeGoodsModel = new StoreGoods;
		if($storeGoodsModel->updateStoreGoods($id , $data )){
			return redirect('/alpha/store/goods/' . $storeId);
		}else{
			return redirect('/alpha/store/goods/' . $storeId);
		}

	}

	public function storeGoodsChecked($goodsId , Request $request){

		$data = array();

		$data['is_checked'] = $request->get('is_checked');

		$data['updated_at']     = date('Y-m-d H:i:s' , time());

		$storeGoodsModel = new StoreGoods;
		if($storeGoodsModel->updateStoreGoods($goodsId , $data )){
			return response()->json(Message::setResponseInfo('SUCCESS' ));
		}else{
			return response()->json(Message::setResponseInfo('FAILED'));
		}

	}

}
