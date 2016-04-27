<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned()->comment('分类id');
            $table->integer('p_id')->unsigned()->comment('父级id')->default(0);
            $table->string('name')->comment('分类名称');
            $table->integer('level')->unsigned()->comment('层级')->default(1);
            $table->boolean('is_del')->comment('是否已经删除;0未删除, 1已删除')->default(0);
            $table->unique('name');
            $table->timestamps();
        });

        Schema::create('goods_brand', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned()->comment('品牌ID');
            $table->integer('c_id')->unsigned()->comment('分类ID');
            $table->string('name')->comment('品牌名称');
            $table->boolean('is_del')->comment('是否已经删除;0未删除, 1已删除')->default(0);
            $table->unique('name');
            $table->timestamps();
        });

        Schema::create('goods', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('c_id')->unsigned()->comment('分类ID');
            $table->integer('b_id')->unsigned()->comment('品牌ID');
            $table->string('name')->comment('商品名称');
            $table->string('img')->comment('商品图片');
            $table->float('in_price')->nullable()->unsigned()->comment('进价单价');
            $table->float('out_price')->unsigned()->comment('销售单价');
            $table->integer('give_points')->unsigned()->default(0)->comment('赠送积分');
            $table->string('spec')->comment('规格');
            $table->string('desc')->nullable()->comment('描述');
            $table->integer('stock')->default(0)->comment('库存');
            $table->boolean('is_open')->default(1)->comment('是否上架');
            $table->boolean('is_checked')->default(0)->comment('是否审核');
            $table->boolean('is_del')->default(0)->comment('是否删除');
            $table->foreign('c_id')->references('id')->on('goods_categories');
            $table->foreign('b_id')->references('id')->on('goods_brand');
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

    }
}
