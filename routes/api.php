<?php
/**
*	api路由
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/


//提交注册登录，找回密码
Route::post('/user/regcheck', 'Api\LoginController@regCheck')->name('apiregcheck');
Route::post('/user/logincheck', 'Api\LoginController@loginCheck')->name('apilogincheck');
Route::post('/user/findcheck', 'Api\LoginController@findCheck')->name('apifindcheck');
Route::get('/user/loginout', 'Api\LoginController@loginOut')->name('apiloginout');

//文件上传
Route::post('/upfile', 'Api\UpfileController@upFileacheck')->name('apiupfile');

//--------------------单位后台上api地址，必须cnum单位编号，act方法--------------
Route::get('/unit/{cnum}/{act}', 'Api\UnitapiController@getApidata')->name('apiunit');
Route::post('/unit/{cnum}/{act}', 'Api\UnitapiController@postApidata')->name('apiunitpost');


//--------------------以下是app/移动端接口必须act方法，cnum单位编号-------------------
Route::get('/we/{act}/{cnum?}', 'Api\WeapiController@getApidata')->name('apiwe');
Route::post('/we/{act}/{cnum?}', 'Api\WeapiController@postApidata')->name('apiwepost');