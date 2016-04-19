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
    return view('welcome');
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
		Route::get('/roles' , 'RoleController@getRoleList');
		Route::get('/roles/user/ajax/{userId}' , 'RoleController@ajaxUserRole');
		Route::get('/roles/ajax' , 'RoleController@ajaxRoleList');
		Route::post('/role/add' , 'RoleController@addRole');
		Route::get('/role/del/{id}' , 'RoleController@delRole');
		Route::get('/role/info/{id}' , 'RoleController@getRoleInfo');
		Route::post('/role/update' , 'RoleController@updateRole');

		//节点操作
		Route::get('/permissions' , 'PermissionController@getPermissionsList');
		Route::get('/permissions/ajax' , 'PermissionController@ajaxPermissionsList');
		Route::post('/permission/add' , 'PermissionController@addPermission');
		Route::get('/permission/del/{id}' , 'PermissionController@delPermission');
		Route::get('/permission/info/{id}' , 'PermissionController@getPermissionInfo');
		Route::post('/permission/update' , 'PermissionController@updatePermission');
		Route::get('/permission/top' , 'PermissionController@getTopPermission');

		//角色权限操作
		Route::get('/permission/role/{rid}' , 'PermissionRoleController@getPermissionIdsByRoleID');
		Route::get('/permission/role/relation/{rid}' , 'PermissionRoleController@getPermissionRoleByID');
		Route::post('/permission/role/add' , 'PermissionRoleController@addPermissionRole');
		Route::post('/permission/role/delete' , 'PermissionRoleController@delPermissionRole');

		//店铺
		Route::get('/stores/infos' , 'StoresController@getStoreInfoList');

	});
    //登陆
    Route::get('/login' , 'AdminUsersController@showLoginView');
    Route::post('/login' , 'AdminUsersController@login');
    Route::get('/logout' , 'AdminUsersController@logout');

});


Route::group(['middleware' => ['api'] , 'prefix' => 'gamma' , 'namespace' => 'Gamma' ], function () {

	//登陆
	Route::post('/login' , 'StoreUsersController@login');
	Route::post('/logout' , 'StoreUsersController@logout');

	//店铺
	Route::post('/store/settling' , 'StoresController@settling');
	Route::post('/store/config/{id}' , 'StoresController@config');

});