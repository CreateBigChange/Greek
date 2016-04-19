<?php

namespace App\Models\Alpha;

use DB;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $_table = 'logs';

	public function addActionLog($data){
		return DB::table($this->_table)->insert($data);
	}

}
