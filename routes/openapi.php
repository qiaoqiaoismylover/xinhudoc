<?php
/**
*	openapi路由
*	主页：http://www.rockoa.com/
*	软件：信呼文件平台
*	作者：雨中磐石(rainrock)
*	时间：2019-06-18
*/

Route::get('/base', 'OpenApi\BaseController@index');

//同服务器上传文件路径
Route::post('/upfile/path', 'OpenApi\UpfileController@postpath');

//不同服务器用文件流上传
Route::post('/upfile/file', 'OpenApi\UpfileController@postfile');

//获取文件信息
Route::get('/upfile/info', 'OpenApi\UpfileController@getinfo');