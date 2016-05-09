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

use App\Models\Alpha\StoreInfos;
use App\Libs\Message;

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
		
		$storeModel = new StoreInfos;

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
			$param .= 'province=' . $search['province'] . '&';
		}
		if(isset($_GET['city']) && $_GET['city'] != 0){
			$search['city'] = trim($_GET['city']);
			$param .= 'city=' . $search['city'] . '&';
		}
		if(isset($_GET['county']) && $_GET['county'] != 0){
			$search['county'] = trim($_GET['county']);
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

		$totalNum = $storeModel->getStoreInfoTotalNum($search);

		$pageData = $this->getPageData($page  , $this->length, $totalNum);

		$this->response['page']         = $pageData->page;
		$this->response['pageHtml']     = $this->getPageHtml($pageData->page , $pageData->totalPage  , '/alpha/stores/infos?' . $param);

		$storeInfos = $storeModel->getStoreInfoList($this->length , $pageData->offset , $search);

		$this->response['storeInfos'] = $storeInfos;

        return view('alpha.store.info.list' , $this->response);
	}
	/**
	 *
	 * (ajax)获取店铺信息
	 */
	public function ajaxStoreInfo($id){

		$storeModel = new StoreInfos;
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

		$storeModel = new StoreInfos;

		if($storeModel->updateStore($id , $data)){
			return redirect('/alpha/stores/infos');
		}
	}

	/**
	 * @param Request $request
	 * @return mixed
	 * 添加店铺
	 */
	public function addStore(Request $request){
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

		$storeModel = new StoreInfos;

		$storeId = $storeModel->addStore($data);
		if($storeId){
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

			$storeModel->addStoreUser($user);

			return redirect('/alpha/stores/infos');
		}
	}

	/**
	 * 获取店铺类型
	 */
	public function ajaxStoreCategoriesList(){

		$storeModel = new StoreInfos;

		$storeCategories = $storeModel->getStoreCategoriesList();

		$this->response['storeCategories'] = $storeCategories;

		return $this->response;
	}

	/**
	 * 获取地区
	 */
	public function ajaxAreas($pid){

		$storeModel = new StoreInfos;

		$areas = $storeModel->getAreas($pid);

		$this->response['areas'] = $areas;

		return $this->response;
	}

	/**
	 * 获取申请入驻的列表
	 */
	public function getSettlings(){
		$storeModel = new StoreInfos;
		$this->response['settlings'] = $storeModel->getSettlings();
		return view('alpha.store.info.settling' , $this->response);
	}

	/**
	 * 完成入驻
	 */
	public function delSettlings($id){
		$storeModel = new StoreInfos;
		if($storeModel->delSettlings($id)){
			return redirect('/alpha/stores/settlings');
		}

	}
}
