<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('account')->comment('帐号');
            $table->string('nick_name')->nullable()->comment('昵称');
            $table->string('true_name')->nullable()->comment('真实名称');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('mobile')->comment('手机号');
            $table->timestamps('login_time');
            $table->string('login_ip');
            $table->string('login_old_ip')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->tinyInteger('is_del')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('user_third_party', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('open_id')->unique();
            $table->string('nick_name')->nullable()->comment('昵称');
            $table->string('avatar')->nullable();
            $table->timestamps('login_time');
            $table->string('login_ip');
            $table->string('login_old_ip')->nullable();
            $table->string('type')->default('weixin');
            $table->integer('user_id')->default('0')->comment('绑定的id');
            $table->tinyInteger('is_del')->default(0);
            $table->string('wd_id')->default('0');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('delivery_address', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('province')->comment('省');
            $table->integer('city')->unsigned()->comment('市');
            $table->integer('county')->unsigned()->comment('区县');
            $table->string('address')->comment('详细地址');

            $table->foreign('province')->references('id')->on('areas');
            $table->foreign('city')->references('id')->on('areas');
            $table->foreign('county')->references('id')->on('areas');
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
        Schema::drop('users');
    }
}
