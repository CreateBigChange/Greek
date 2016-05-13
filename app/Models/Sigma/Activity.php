<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models\Sigma;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Libs\Message;


class Activity extends Model
{
    protected $_banners_table            = 'banners';


    public function getBannerList(){
        return DB::table($this->_banners_table)->where('is_open' , 1)->get();
    }

}
