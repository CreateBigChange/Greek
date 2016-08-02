<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App;
use  Excel;

class MyExcel extends Model
{
    /**
     * @param $name       String xls名字
     * @param $cellData   Array  要导出的数据数组
     * @param $title      Array  要导出的数据标题
     * @return mixed
     */
    public function  export( $name,$cellData,$title){
        //导入类
        $excel = App::make('excel');



        $data=array();
        //处理表格让表格和标题结合
        $data[0] = $cellData[0];
        for($i=1;$i<=count($cellData);$i++){
            $data[$i]=$cellData[$i-1];
        }

        $num=0;
        foreach($cellData[0] as $key=>$value){
           $data[0][$key] =$title[$num];
            $num++;
        }

      //  $data = $cellData;
        //生产导出excel
        return  $excel ->create($name,function($excel) use ($data){
            $excel->sheet("name", function($sheet) use ($data){
                $sheet->fromArray($data,null, 'A1', false, false);;
            });
        })->export('xls');
    }

    /**
     * @param $filePath excel的文件路径
     * @return array   返回文件数组
     */
    public function  import($filePath ,$tableName){

        Excel::load("$filePath", function($reader) {
            $data =$reader->toArray();

            $data=$data[0];
            for($i =0;$i<count($data);$i++){
                DB::table("store_goods")->insert(
                 $data[$i]
                );
            }
        });
    }
}