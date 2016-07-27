<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App;

class MyExcel extends Model
{
    /**
     * @param $name       String xls名字
     * @param $cellData   Array  要导出的数据数组
     * @param $title      Array  要导出的数据标题
     * @return mixed
     */
    public function  export( $name,$cellData,$title){

        $excel = App::make('excel');

        $data=array();
        $data[0] = $cellData[0];
        for($i=1;$i<=count($cellData);$i++){
            $data[$i]=$cellData[$i-1];
        }

        $num=0;
        foreach($cellData[0] as $key=>$value){
           $data[0][$key] =$title[$num];
            $num++;
        }

        return  $excel ->create($name,function($excel) use ($data){
            $excel->sheet("name", function($sheet) use ($data){
                $sheet->fromArray($data,null, 'A1', false, false);;
            });
        })->export('xls');
    }
    public function  import($filePath){
        $filePath = 'storage/exports/'.iconv('UTF-8', 'GBK', '订单').'.xls';

        $excel = App::make('excel');
        $excel->load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }
}