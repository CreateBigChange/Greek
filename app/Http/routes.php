<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/alpha/index');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

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
		Route::get('/permission/top' , 'AdminPermissionController@getTopPermission');

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
		Route::get('/stores/settlings' , 'StoresController@getSettlings');
		Route::get('/stores/settlings/del/{id}' , 'StoresController@delSettlings');

		//商品
		Route::get('/goods' , 'GoodsController@getGoodsList');
		Route::post('/goods' , 'GoodsController@getGoodsList');
		Route::post('/goods/add' , 'GoodsController@addGoods');
		Route::post('/goods/update' , 'GoodsController@editGoods');
		Route::get('/goods/info/{id}' , 'GoodsController@ajaxGoodsInfo');
		Route::get('/goods/category/pid/{pid}' , 'GoodsController@ajaxGoodsCategoryByPid');
		Route::get('/goods/category/level/{pid}' , 'GoodsController@ajaxGoodsCategoryByLevel');
		Route::get('/goods/brand/{cid}' , 'GoodsController@ajaxGoodsBrandByCid');


		//地区
		Route::get('/areas/{pid}' , 'StoresController@ajaxAreas');

		Route::post( '/upload' , 'UploadController@uploadImg' );
		Route::post( '/upload/qiniu' , 'UploadController@uploadQiniu' );

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
		Route::post('/store/orders/{type}', 'OrdersController@getOrderList');
		Route::post('/store/orders/change/status/{id}', 'OrdersController@changeStatus');

	});

	Route::post('/store/areas', 'StoresController@areas');
	Route::post('/store/settling', 'StoresController@settling');



	//登陆
	Route::post('/login' , 'StoreUsersController@login');
	Route::post('/logout' , 'StoreUsersController@logout');
	Route::post('/reset/password', 'StoreUsersController@resetPassword');

	Route::post('/sendsms', 'StoreUsersController@sendSms');

});