<?php

namespace App\Http\Controllers;

use Session , Cookie , Config;

use App\Models\Alpha\Permissions;
use App\Models\Alpha\Logs;

class AdminController extends Controller
{
    protected $response = array();
	protected $userInfo;

    public function __construct(){

        $this->response['title']		= '标题';
        $this->response['menuactive']	= 'qxgl';

		global $userInfo;


		if($userInfo){
		
			$this->userInfo = $userInfo;

			$this->_getMenu($this->userInfo->id);
		}


    }

	//获取分页数据
	public function getPageData($page , $length , $totalNum){
        $pagedata               = new \stdClass;
        $pagedata->page         = $page;
        $pagedata->length       = $length;
        $pagedata->offset       = ($pagedata->page - 1) * $pagedata->length;
        $pagedata->totalPage    = ceil($totalNum/$length);
        $pagedata->totalNum     = $totalNum;
        $pagedata->isEndPage    = $page < $pagedata->totalPage ? 0 : 1;

        return $pagedata;
    }

	//获取菜单
	private function _getMenu($userId){
	
		$permissionModel = new Permissions;

		$permissionList = $permissionModel->getAdminUserMenu($userId);

		foreach($permissionList as $pl){
			$pl->active = 0;
			if($pl->name == $_SERVER['REQUEST_URI']){
				$pl->active = 1;
			}
			foreach($pl->child as $plc){
				$plc->active	= 0;
				if($plc->name == $_SERVER['REQUEST_URI']){
					$plc->active	= 1;
					$pl->active		= 1;
				}
			}
		}

        $this->response['menu'] = $permissionList;

	}

	protected function _writeLogForSql($actionInfo){
		$log	= new Logs;
		$data	= array(
			'admin_user_id'		=> $this->userInfo->id,
			'account'			=> $this->userInfo->account,
			'real_name'			=> $this->userInfo->real_name,
			'action'			=> $actionInfo,
			'created_at'		=> date('Y-m-d H:i:s' , time())
		);

		$log->addActionLog($data);

	}


}
