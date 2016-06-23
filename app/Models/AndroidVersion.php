<?php
/**
 * Created by PhpStorm.
 * User: wuhui
 * Date: 16/4/18
 * Time: 上午10:43
 */
namespace App\Models;

use DB , Config;
use Illuminate\Database\Eloquent\Model;

class AndroidVersion extends Model
{
    protected $table            = 'android_version';

    public function versionIsNew($version , $type){
        $oldVersion = DB::table($this->table)->where('type' , $type)->orderBy('created_at' , 'desc')->first();

        if(!$oldVersion){
            return true;
        }
        if($oldVersion->version != $version){
            return $oldVersion;
        }else{
            return true;
        }
    }

}
