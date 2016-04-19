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

class StoresController extends AdminController
{

    public function __construct(){
        parent::__construct();
        $this->response['title']		= '店铺管理';
    }

	/**
	 * 获取店铺基本信息
	 */
	public function getStoreInfoList(){
		
		$storeModel = new StoreInfos;
		$storeInfos = $storeModel->getStoreInfoList();

		$this->response['storeInfos'] = $storeInfos;

        return view('alpha.store.info.list' , $this->response);
	}


}
