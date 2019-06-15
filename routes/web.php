<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Users\HomeController@index')->name('usersindex');


//一些基本路由，如获取验证码，发验证码，发短信等
Route::get('/base/{act}', 'Base\BaseController@index')->name('base');


//文件预览编辑
Route::get('/fileview/{ckey}/{filenum}', 'Api\FileoptController@fileview')->name('fileview');
Route::get('/filedown/{ckey}/{filenum}', 'Api\FileoptController@filedown')->name('filedown');
Route::get('/fileedit/{ckey}/{filenum}', 'Api\FileoptController@fileedit')->name('fileedit');
Route::post('/fileeditcall/{ckey}/{filenum}', 'Api\FileoptController@fileeditcall')->name('fileeditcall');
Route::get('/filesend/{ckey}/{filenum}', 'Api\FileoptController@filesend')->name('filesend');


//对外接口时文件预览的
Route::get('/afileview/{filenum}', 'Api\FileoptController@afileview')->name('afileview');
Route::get('/afiledown/{filenum}', 'Api\FileoptController@afiledown')->name('afiledown');


/**
*	平台用户路由
*/
Route::group([
	'prefix' => 'users',
	'as' 	=> 'users',
], function () {
	
	Route::get('/', 'Users\HomeController@index')->name('users');

	Route::get('/login', 'Users\LoginController@showLoginForm')->name('login');
	Route::get('/reg', 'Users\LoginController@showRegForm')->name('reg');
	
	Route::get('/find', 'Users\LoginController@showFindForm')->name('find');
	Route::get('/loginout', 'Users\LoginController@loginout')->name('loginout');
	
	
	Route::get('/index/{cnum?}', 'Users\HomeController@index')->name('indexs');
	Route::get('/indexhome', 'Users\HomeController@showViews')->name('indexhome');
	
	Route::get('/manage', 'Users\ManageController@index')->name('manage');
	Route::get('/active/{id}', 'Users\ManageController@activeForm')->name('active');

	Route::get('/cog', 'Users\CogController@showCogForm')->name('cog');
	Route::get('/agent', 'Users\AgentController@index')->name('agent');
	Route::get('/companyadd', 'Users\CompanyController@showCreateForm')->name('companyadd');
	
	
});


/**
 * 后台管理路由
 */
Route::group([
	'prefix' => 'admin',
	'as'	 => 'admin'
], function () {
	
	Route::get('/login', 'Admin\LoginController@showLoginForm')->name('login');
	Route::post('/login', 'Admin\LoginController@login')->name('logincheck');
	Route::get('/loginout', 'Admin\LoginController@loginout')->name('loginout');
	

	Route::get('/', 'Admin\HomeController@index')->name('home');
	Route::get('/company', 'Admin\CompanyController@index')->name('company');
	Route::get('/companyedit/{id?}', 'Admin\CompanyController@getForm')->name('companyedit');
	
	Route::get('/usera', 'Admin\UseraController@index')->name('usera');
	Route::get('/users', 'Admin\UsersController@index')->name('users');
	
	Route::get('/usersedit/{id?}', 'Admin\UsersController@getForm')->name('usersedit');
	Route::get('/dept', 'Admin\DeptController@index')->name('dept');
	
	
	
	//安装第三方应用
	Route::get('/anstall', 'Admin\AnstallController@getaList')->name('anstall');
	

	//平台管理
	Route::get('/manage/{act}', 'Admin\ManageController@index')->name('manage');
});



/**
*	单位管理后台
*/
Route::get('/manage/{cnum}/{act?}', 'Manage\HomeController@getForm')->name('manage');