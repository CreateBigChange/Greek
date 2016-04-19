<?php

namespace App\Models\Alpha;

use DB;
use Illuminate\Database\Eloquent\Model;

class StoreInfos extends Model
{
	public function getStoreInfoList(){
		$storeList = DB::table('store_infos')
			->orderBy('created_at' , 'desc')
			->get();

		return $storeList;
	}

}
