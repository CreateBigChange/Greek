<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned()->comment('分类id');
            $table->integer('p_id')->unsigned()->comment('父级id')->default(0);
            $table->string('name')->comment('分类名称');
            $table->boolean('is_del')->comment('是否已经删除;0未删除, 1已删除')->default(0);
            $table->unique('name');
            $table->timestamps();
        });
        //
        Schema::create('store_infos', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('c_id')->unsigned()->comment('分类');
            $table->string('name')->comment('店铺/企业名称');
            $table->string('description')->nullable();
            $table->string('business_license')->comment('营业执照图片地址');
            $table->string('legal_person')->comment('法人或店主名字');
			$table->string('legal_person_phone')->nullable()->comment('店主电话');
            $table->string('id_card')->comment('店主身份证');
            $table->integer('province')->comment('省');
            $table->integer('city')->unsigned()->comment('市');
            $table->integer('county')->unsigned()->comment('区县');
            $table->string('address')->comment('详细地址');
			$table->string('contacts')->comment('联系人');
			$table->string('contact_phone')->comment('联系电话');
			$table->boolean('is_open')->default(0)->comment('是否开启');
			$table->boolean('is_checked')->default(0)->comment('是否审核');
			$table->boolean('is_del')->default(0)->comment('是否删除');
            $table->foreign('c_id')->references('id')->on('store_categories');
			$table->unique('name');
            $table->timestamps();
        });


		
        Schema::create('store_configs', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->integer('store_id')->unsigned();
            $table->string('store_logo')->nullable()->comment('店铺logo');
            $table->float('start_price')->nullable()->unsigned()->comment('起送价');
            $table->float('deliver')->nullable()->unsigned()->comment('配送费');
            $table->string('business_cycle')->nullable()->comment('营业周期');
            $table->string('business_time')->nullable()->comment('营业时间');
            $table->boolean('recommend')->default(0)->comment('是否推荐首页;不为0为推荐首页');
            $table->tinyInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('is_close')->default(0)->comment('是否打烊;1打烊');
            $table->string('bell')->nullable()->comment('铃声');
            $table->primary('store_id');
            $table->unique('store_id');
            $table->foreign('store_id')->references('id')->on('store_infos');
            $table->timestamps();
        });

        Schema::create('store_users', function (Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('store_id')->unsigned();
            $table->string('account' , '25');
            $table->string('real_name', 60);
            $table->string('password', 60);
            $table->string('salt', 60)->comment('密码加密随机字符串');
            $table->string('tel');
			$table->boolean('is_del')->default(0)->comment('是否删除');
            $table->rememberToken();
            $table->foreign('store_id')->references('id')->on('store_infos')->onDelete('cascade');
			$table->unique('account');
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
        Schema::dropIfExists('store_infos');
        Schema::dropIfExists('store_categories');
        Schema::dropIfExists('store_configs');
        Schema::dropIfExists('store_users');
    }
}
