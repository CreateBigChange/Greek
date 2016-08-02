<?php


Route::get('/', function () {
    return redirect('/alpha/index');
});


Route::group(['middleware' => ['web'] , 'prefix' => 'alpha' , 'namespace' => 'Alpha' ], function () {
	Route::get('/' , function(){
		return redirect('/alpha/index');
	});

	Route::group(['middleware' => ['checkAuth']] , function(){

		//首页
		Route::get('/index' , 'IndexController@index');

		//管理员用户操作
		Route::get('/admin/users' , 'AdminUsersController@getAdminUserList');
		Route::post('/admin/user/add' , 'AdminUsersController@addAdminUser');
		Route::get('/admin/user/del/{id}' , 'AdminUsersController@delAdminUser');
		Route::get('/admin/user/info/{id}' , 'AdminUsersController@getAdminUserInfo');
		Route::post('/admin/user/update' , 'AdminUsersController@updateAdminUser');

		//角色操作
		Route::get('/roles' , 'AdminRoleController@getRoleList');
		Route::get('/roles/user/ajax/{userId}' , 'AdminRoleController@ajaxUserRole');
		Route::get('/roles/ajax' , 'AdminRoleController@ajaxRoleList');
		Route::post('/role/add' , 'AdminRoleController@addRole');
		Route::get('/role/del/{id}' , 'AdminRoleController@delRole');
		Route::get('/role/info/{id}' , 'AdminRoleController@getRoleInfo');
		Route::post('/role/update' , 'AdminRoleController@updateRole');

		//节点操作
		Route::get('/permissions' , 'AdminPermissionController@getPermissionsList');
		Route::get('/permissions/ajax' , 'AdminPermissionController@ajaxPermissionsList');
		Route::post('/permission/add' , 'AdminPermissionController@addPermission');

		Route::get('/permission/del/{id}' , 'AdminPermissionController@delPermission');

		Route::get('/permission/info/{id}' , 'AdminPermissionController@getPermissionInfo');
		Route::post('/permission/update' , 'AdminPermissionController@updatePermission');
		Route::get('/permission/level/{level}' , 'AdminPermissionController@getLevelPermission');



		//角色权限操作
		Route::get('/permission/role/{rid}' , 'AdminPermissionRoleController@getPermissionIdsByRoleID');
		Route::get('/permission/role/relation/{rid}' , 'AdminPermissionRoleController@getPermissionRoleByID');
		Route::post('/permission/role/add' , 'AdminPermissionRoleController@addPermissionRole');
		Route::post('/permission/role/delete' , 'AdminPermissionRoleController@delPermissionRole');

		//店铺
		Route::get('/stores/infos' , 'StoresController@getStoreInfoList');
		Route::get('/stores/info/{id}' , 'StoresController@ajaxStoreInfo');
		Route::post('/stores/add' , 'StoresController@addStore');
		Route::post('/stores/update' , 'StoresController@updateStore');

		Route::get('/stores/categories' , 'StoresController@ajaxStoreCategoriesList');
		Route::get('/stores/categories/list' , 'StoresController@getStoreCategoriesList');
		Route::post('/stores/category/add' , 'StoresController@addStoreCategory');
		Route::post('/stores/category/update' , 'StoresController@updateStoreCategory');
		Route::get('/stores/category/info/{id}' , 'StoresController@getStoreCategoryById');
		Route::get('/stores/settlings' , 'StoresController@getSettlings');
		Route::get('/stores/settlings/del/{id}' , 'StoresController@delSettlings');
		Route::get('/stores/goodsmanager','StoresController@managergoods');

		//店铺商品
		Route::get('/store/goods/{storeId}' , 'StoresController@getStoreGoodsList');
		Route::get('/store/goods/by/nocheck' , 'StoresController@getStoreGoodsListByNoCheck');
		Route::post('/store/goods/check/{goodsId}' , 'StoresController@storeGoodsChecked');
		Route::get('/store/goods/info/{goodsId}' , 'StoresController@getStoreGoodsInfo');
		Route::post('/store/goods/update' , 'StoresController@updateStoreGoods');
        Route::get('/store/goods/by/update' , 'StoresController@updateStoreGoodsInfo');
        Route::post('/store/goods/import' , 'StoresController@storeGoodsImport');




		//店员
		Route::get('/store/user' , 'StoresController@getStoreUserList');

		//商品
		Route::get('/goods' , 'GoodsController@getGoodsList');
		Route::post('/goods/add' , 'GoodsController@addGoods');
		Route::post('/goods/update' , 'GoodsController@editGoods');
		Route::get('/goods/info/{id}' , 'GoodsController@ajaxGoodsInfo');
		Route::get('/goods/del/{id}' , 'GoodsController@delGoods');
        Route::post('/goods/import' , 'GoodsController@excelImport');
        Route::get('/goods/excleExport' , 'GoodsController@excleExport');

		//品类
		Route::get('/goods/category/pid/{pid}' , 'GoodsController@ajaxGoodsCategoryByPid');
		Route::post('/goods/category/update' , 'GoodsController@updateGoodsCategory');
		Route::get('/goods/category/info/{id}' , 'GoodsController@getGoodsCategoryById');
		Route::get('/goods/category/level/{pid}' , 'GoodsController@ajaxGoodsCategoryByLevel');
		Route::get('/goods/brand/{cid}' , 'GoodsController@ajaxGoodsBrandByCid');
		Route::post('/goods/brand/update' , 'GoodsController@updateGoodsBrand');
		Route::get('/goods/brand/info/{id}' , 'GoodsController@getGoodsBrandById');
		Route::get('/goods/category/list' , 'GoodsController@getGoodsCategory');
		Route::post('/goods/category/add' , 'GoodsController@addCategory');
		Route::post('/goods/brand/add' , 'GoodsController@addBrand');
		Route::get('/goods/category/del/{id}' , 'GoodsController@delCategory');
		Route::get('/goods/brand/del/{id}' , 'GoodsController@delBrand');

		//地区
		Route::post('/areas' , 'StoresController@ajaxAreas');

		Route::post( '/upload' , 'UploadController@uploadImg' );
		Route::post( '/upload/qiniu' , 'UploadController@uploadQiniu' );

		//订单
		Route::get('/order/list' , 'OrdersController@getOrderList');
		Route::get('/order/delivery' , 'OrdersController@getOrderDelivery');
		Route::get('/order/notdelivery' , 'OrdersController@getOrderNotDelivery');
		Route::get('/order/accident' , 'OrdersController@getOrderAccident');
		Route::get('/order/dispatching' , 'OrdersController@getOrderDispatching');
        Route::get('/order/balance', 'OrdersController@getOrderBalence');
        Route::get('/order/completd', 'OrdersController@getOrderCompletd');
        Route::get('/order/balance/getOrderExport', 'OrdersController@getOrderExport');
        Route::post('/order/import', 'OrdersController@Orderimport');
		Route::post('/order/change/status/{id}', 'OrdersController@changeStatus');



		//用户
		Route::get('/user/list' , 'UserController@getUserList');

		//财务
		Route::get('/finance/cash' , 'FinanceController@getWithdrawCashLog');
		Route::get('/finance/withdrawReject' , 'FinanceController@withdrawReject');
		Route::get('/finance/withdrawAgree/{id}' , 'FinanceController@withdrawAgree');
        Route::get('/finance/checked', 'FinanceController@getCheckedWithdrawCashLog');
        Route::get('/finance/finish_withdraw', 'FinanceController@finish_withdraw');
        Route::get('/finance/cashFinishList', 'FinanceController@getCashFinishList');


		//版本
		Route::get('/app/version' , 'AppVersionController@getApkVersion');
		//活动
		Route::get('/Activity/bannerVersion' , 'ActivityController@bannerVersion');
		Route::get('/Activity/save' , 'ActivityController@save');
		Route::get('/Activity/coupon','ActivityController@coupon');
		Route::get('/Activity/couponOpen/{id}','ActivityController@couponOpen');
		Route::post('/Activity/couponAdd','ActivityController@couponAdd');
		Route::get('/Activity/couponClose/{id}','ActivityController@couponClose');

	});

	//登陆
    Route::get('/login' , 'AdminUsersController@showLoginView');
    Route::post('/login' , 'AdminUsersController@login');
    Route::get('/logout' , 'AdminUsersController@logout');

});















Route::group(['middleware' => ['api'] , 'prefix' => 'gamma' , 'namespace' => 'Gamma' ], function () {

	Route::group(['middleware' => ['checkLogin']] , function() {
		//店铺
		Route::post('/store/config', 'StoresController@config');
		Route::post('/store/info/{id}', 'StoresController@getStoreInfo');

		//栏目
		Route::post('/store/nav', 'StoresController@nav');
		Route::post('/store/nav/add', 'StoresController@addNav');
		Route::post('/store/nav/update/{id}', 'StoresController@updateNav');
		Route::post('/store/nav/del/{id}', 'StoresController@delNav');
		Route::post('/store/nav/{id}', 'StoresController@getNavInfo');
		Route::post('/store/nav/goods/del/{id}', 'StoresController@delNavGoods');

		//商品
		Route::post('/store/goods/add', 'StoresController@addGoods');
		Route::post('/store/goods', 'StoresController@getGoodsList');
		Route::post('/store/goods/info/{id}', 'StoresController@getGoodsInfo');
		Route::post('/store/goods/categories/{pid}', 'StoresController@getGoodsCategories');
		Route::post('/store/goods/brand', 'StoresController@getGoodsBrand');
		Route::post('/store/goods/update/{id}', 'StoresController@updateGoods');
		Route::post('/store/goods/opens', 'StoresController@opens');
		Route::post('/store/goods/dels', 'StoresController@dels');



		//订单
		Route::post('/store/orders', 'OrdersController@getOrderList');
		Route::post('/store/orders/change/status/{orderId}', 'OrdersController@changeStatus');
		Route::post('/order/notice/num', 'OrdersController@getOrderNum');
		Route::post('/order/notice/num/clear', 'OrdersController@clearOrderNum');

		//提现
		Route::post('/store/cash', 'StoreUsersController@withdrawCash');
		Route::post('/store/cash/config', 'StoreUsersController@withdrawCashConfig');
		Route::post('/store/cash/log', 'StoreUsersController@getWithdrawCashLog');



		//统计
		Route::post('/store/count/today', 'StoresController@getStoreTodayCount');
		Route::post('/store/month/points', 'StoresController@getStoreMonthPoint');

		Route::get('/store/count/finance', 'StoresController@financeCount');
		Route::post('/store/count/finance', 'StoresController@ajaxFinanceCount');

		Route::get('/order/count', 'OrdersController@orderCount');
		Route::post('/order/count', 'OrdersController@ajaxOrderCount');

		//意见反馈
		Route::post('/feedback', 'StoreUsersController@feedback');


		//优惠券
		Route::post('/store/coupon/add', 'CouponController@addCoupon');
		Route::post('/store/coupon/list', 'CouponController@getCouponList');
		Route::post('/store/coupon/info/{couponId}', 'CouponController@getCouponInfo');
		Route::post('/store/coupon/stop/{couponId}', 'CouponController@stopCoupon');

	});

//	Route::get('/store/count/finance/{storeId}', 'StoresController@financeCount');
//	Route::post('/store/count/finance/{storeId}', 'StoresController@ajaxFinanceCount');

	Route::post('/store/areas', 'StoresController@areas');
	Route::post('/store/settling', 'StoresController@settling');

	Route::post('/franchisee', 'StoresController@franchisee');

	Route::post('/upload/qiniu' , 'UploadController@uploadQiniu');



	//登陆
	Route::post('/login' , 'StoreUsersController@login');
	Route::post('/logout' , 'StoreUsersController@logout');
	Route::post('/reset/password', 'StoreUsersController@resetPassword');

	Route::post('/sendsms', 'StoreUsersController@sendSms');


	//下载
	Route::get('/app/down', 'StoreUsersController@appDown');
	Route::get('/app/check/version', 'StoreUsersController@checkVersion');

});



Route::group(['middleware' => ['api'] , 'prefix' => 'sigma' , 'namespace' => 'Sigma' ], function () {

	Route::group(['middleware' => ['UserClientCheckLogin']] , function() {

		Route::post('/order/init' , 'OrdersController@initOrder');
		Route::post('/order/list' , 'OrdersController@getOrderList');
		Route::post('/order/status/log/{orderId}' , 'OrdersController@getOrderStatusLog');
		Route::post('/order/complaint/{orderId}' , 'OrdersController@complaint');
		Route::post('/order/evaluate/{orderId}' , 'OrdersController@evaluate');
		Route::post('/order/refund/{orderId}' , 'OrdersController@refund');
		Route::post('/order/info/{orderId}' , 'OrdersController@getOrderInfo');
		Route::post('/order/confirm/{orderId}' , 'OrdersController@confirmOrder');
		Route::post('/order/change/status/{id}' , 'OrdersController@changeStatus');
		Route::post('/order/status/{orderId}' , 'OrdersController@getOrderStatus');
		Route::post('/order/coupon/{orderId}' , 'OrdersController@getOrderCoupon');
		Route::post('/order/update/coupon/{orderId}' , 'OrdersController@updateOrderCoupon');
		Route::post('/order/update/address/{orderId}' , 'OrdersController@updateOrderAddress');

		Route::post('/user/address' , 'UsersController@getConsigneeAddressByUserId');
		Route::post('/user/address/add' , 'UsersController@addConsigneeAddress');
		Route::post('/user/address/update/{addressId}' , 'UsersController@updateConsigneeAddress');
		Route::post('/user/address/del/{addressId}' , 'UsersController@delConsigneeAddress');

		Route::post('/user/update/password' , 'UsersController@updatePassword');
		Route::post('/user/update' , 'UsersController@updateUser');
		Route::post('/user/set/pay/password' , 'UsersController@setPayPassword');
		Route::post('/user/update/pay/password' , 'UsersController@updatePayPassword');
		Route::post('/bind/mobile', 'UsersController@bindMobile');
		Route::post('/check/mobile/code' , 'UsersController@checkMobileCode');
		Route::post('/update/mobile' , 'UsersController@updateMobile');

		Route::post('/sendsms/by/bindmobile' , 'UsersController@bindMobilSms');

		Route::post('/user/info' , 'UsersController@userInfo');
		Route::post('/user/coupon/list' , 'UsersController@getUserCouponList');

	});

	Route::post('/store/list' , 'StoresController@getStoreList');
	Route::post('/store/list/byids' , 'StoresController@getStoreListByIds');
	Route::post('/store/info/{storeId}' , 'StoresController@getStoreInfo');
	Route::post('/store/goods/list/{storeId}' , 'StoresController@getStoreGoodsList');
	Route::post('/store/goods/info/{goodsId}' , 'StoresController@getStoreGoodsInfo');
	Route::post('/store/nav/{storeId}', 'StoresController@nav');
	Route::post('/store/category', 'StoresController@storeCategory');
	Route::post('/store/activity' , 'StoresController@getStoreActivity');

	Route::post('/banner/list', 'ActivityController@getBannerList');

	Route::post('/logout' , 'UsersController@logout');
	Route::post('/login' , 'UsersController@login');
	Route::post('/register' , 'UsersController@register');
	Route::post('/reset/password', 'UsersController@resetPassword');


	Route::post('/sendsms', 'UsersController@sendSms');
	Route::get('/redis', 'UsersController@redis');

	//下载
	Route::get('/app/down', 'UsersController@appDown');
	Route::get('/app/check/version', 'UsersController@checkVersion');

});

/**
 * ##############################################################################
 * 							通用方法控制器
 * ##############################################################################
 */
Route::group(['middleware' => ['api'] , 'namespace' => 'Common' ], function () {
	Route::post('/upload/apk' , 'UploadController@uploadImg');
	Route::post('/upload/qiniu' , 'UploadController@uploadQiniu');
	Route::post('/upload/qiniu/apk' , 'UploadController@uploadApkQiniu');
	Route::get('/version/check', 'ToolController@versionIsNew');
});


/**
 * ##############################################################################
 * 							微信相关控制器
 * ##############################################################################
 */
Route::group(['middleware' => ['api'] , 'namespace' => 'Wechat' ], function () {
	Route::group(['middleware' => ['UserClientCheckLogin']] , function() {
		Route::any('/wechat/order/{orderId}', 'WechatController@wechatPay');
	});
	Route::get('/wechat/token', 'WechatController@wechatToken');
	Route::any('/wechat/notify/pub', 'WechatController@notifyPub');
	Route::any('/wechat/notify/open', 'WechatController@notifyOpen');

	Route::get('/weixin/login', 'WechatLoginController@weixinLogin');
	Route::get('/weixin/openid', 'WechatLoginController@weixinOpenId');
});


/**
 * ##############################################################################
 * 							支付宝相关控制器
 * ##############################################################################
 */
Route::group(['middleware' => ['api'] , 'namespace' => 'Alipay' ], function () {
	Route::group(['middleware' => ['UserClientCheckLogin']] , function() {
		Route::post('/alipay/order/{orderId}' , 'AlipayController@aliPay');
	});

	Route::any('/alipay/notify', 'AlipayController@notify');
	Route::any('/alipay/notify/refund', 'AlipayController@refundNotify');
});


Route::get('/push', 'Common\ToolController@push');


//Route::group(['middleware' => ['api' , 'wechat.oauth'] , 'namespace' => 'Wxpay' ], function () {
//	Route::get('wechat/view' , 'WechatController@view');
//	Route::get('/wechat/pay', 'WechatController@pay');
//	//Route::get('/wechat/notify', 'WechatController@notify');
//
//});