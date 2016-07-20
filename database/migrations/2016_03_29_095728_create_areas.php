<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->comment('区县名称');
            $table->string('parent')->comment('父级ID');
            $table->integer('sort')->comment('排序');
            $table->integer('deep')->comment('深度');
            $table->string('region')->nullable()->comment('区域');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('areas');
    }
}
