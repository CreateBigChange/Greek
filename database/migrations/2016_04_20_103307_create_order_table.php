<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('order_num')->comment('订单号');
            $table->float('total')->unsigned()->comment('总价');
            $table->float('deliver')->unsigned()->default(0)->comment('配送费');
            $table->integer('points')->unsigned()->default(0)->comment('总积分');
            $table->integer('status')->unsigned()->comment('订单状态;0删除;1未付款;2已付款;3配送中;4已送达;5已取消;6退款中;7已退款')->default(0);
            $table->integer('store_id')->unsigned();
            $table->integer('user')->unsigned()->comment('用户ID');
            $table->string('consignee')->comment('收货人');
            $table->string('consignee_tel')->comment('收货电话');
            $table->integer('consignee_province')->comment('省');
            $table->integer('consignee_city')->comment('市');
            $table->integer('consignee_county')->comment('区');
            $table->string('consignee_address')->comment('收货地址');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('refund_reason')->nullable()->comment('退款原因');

            $table->foreign('store_id')->references('id')->on('store_infos');
            $table->unique('order_num');
            $table->timestamps();
        });

        Schema::create('order_infos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned()->comment('订单信息id');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('goods_id')->unsigned();
            $table->integer('c_id')->unsigned()->comment('分类ID');
            $table->integer('b_id')->unsigned()->comment('品牌ID');
            $table->string('c_name')->comment('分类名称');
            $table->string('b_name')->comment('品牌名称');
            $table->string('name')->comment('商品名称');
            $table->string('img')->comment('商品图片');
            $table->float('out_price')->unsigned()->comment('销售单价');
            $table->integer('give_points')->unsigned()->default(0)->comment('赠送积分');
            $table->string('spec')->comment('规格');
            $table->integer('num')->unsigned()->comment('购买数量');

            $table->foreign('goods_id')->references('id')->on('store_goods');
            $table->foreign('c_id')->references('id')->on('goods_categories');
            $table->foreign('b_id')->references('id')->on('goods_brand');

            $table->timestamps();
        });

        Schema::create('order_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned()->comment('订单id');
            $table->integer('order_id')->comment('订单ID');
            $table->string('order_num')->comment('订单号');
            $table->integer('user')->comment('用户ID');
            $table->string('identity')->comment('身份');
            $table->boolean('platform')->comment('用户是否是平台用户');
            $table->string('log')->comment('日志');

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
        //
    }
}
