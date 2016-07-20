<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreSettlingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_settlings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('province')->unsigned()->comment('省ID');
            $table->integer('city')->unsigned()->comment('市/区ID');
            $table->integer('county')->unsigned()->comment('区/县ID');
            $table->string('address')->comment('详细地址');
            $table->string('name')->comment('姓名');
            $table->string('contact')->comment('联系电话');
            $table->integer('status')->unsigned()->default(0)->comment('状态;0未操作 1确认 2已完成 3不通过');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_settlings');
    }
}
