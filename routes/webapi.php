<?php
/**
*	后台管理api路由
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/



//--------------------平台管理后台的api--------
Route::get('/admin/{act}', 'Admin\AdminapiController@getApidata')->name('apiadmin');
Route::post('/admin/{act}', 'Admin\AdminapiController@postApidata')->name('apiadminpost');


Route::group([
	'prefix' => 'adminold',
	'as' => 'admin'
], function () {
	
	//保存用户
	Route::post('/userssave', 'Admin\UsersController@saveData')->name('userssave');
	
	Route::post('/companysave', 'Admin\CompanyController@saveData')->name('companysave');
	
	
	//平台设置保存
	Route::post('/managesave/{act}', 'Admin\ManageController@saveData')->name('managesave');
});