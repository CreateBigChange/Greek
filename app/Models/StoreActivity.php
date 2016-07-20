<?php
/**
 * bannerModel
 * @author  wuhui
 * @time    2016/06-08
 * @email   wuhui904107775@qq.com
 */
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

use App\Models\Activity;

class StoreActivity extends Model{

    protected $table = 'store_activity';


    public function getStoreActivityByStoreIds($storeIds){
        $activityModel = new Activity();
        return DB::table($this->table)
            ->leftJoin($activityModel->getTable()." as a" , "a.id" , "=" , $this->table . ".activity_id")
            ->where('a.is_del' , 0)
            ->where('a.is_open' , 1)
            ->whereIn('store_id' , $storeIds)
            ->orderBy('sort' , 'asc')
            ->get();
    }

}
