<?php
/**
 * Created by PhpStorm.
 * User: DEL
 * Date: 2016/8/2
 * Time: 12:11
 */

 namespace App\Models;

 use DB;
 use Illuminate\Database\Eloquent\Model;
 use App;

 class FileUpload extends Model
 {
    public function upload($file){
        if(!$file->isValid()){
            exit('文件上传出错！');
        }

        $clientName = $file->getClientOriginalName();

        $tmpName = $file->getFileName();

        $realPath = $file->getRealPath();

        $extension = $file->getClientOriginalExtension();

        $newName = md5(date('ymdhis').$clientName).".".$extension;

        $path = $file->move('E:\DevelopmentTools\server\tempFile',$newName); //这里是缓存文件夹，存放的是用户上传的原图，这里要返回原图地址给flash做裁切用}
        return $path;
    }
 }