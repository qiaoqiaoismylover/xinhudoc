<?php
/**
*	rock配置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

return [
	
	//开发团队名称
	'xinhu'		=> env('ROCK_XINHU', '信呼开发团队'), 
	
	//平台官网地址，用于在线升级等服务器
	'urly'		=> env('ROCK_URLY', 'http://www.rockoa.com'), 
	
	
	//平台官网地址官网key
	'xinhukey'	=> env('ROCK_XINHUKEY', ''), 
	
	
	
	//系统随机密钥
	'randkey'	=> env('ROCK_RANDKEY', ''),
	
	//后台默认的样式
	'adminstyle'=> env('ROCK_ADMINSTYLE', 10),
	
	//用户默认的样式
	'usersstyle'=> env('ROCK_USERSSTYLE', 1),
	
	//平台类型,dev开发,demo演示
	'systype'	=> env('ROCK_SYSTYPE', ''),
	
	//是否异步发送消息
	'asynsend'	=> env('ROCK_ASYNSEND', false),
	
	//上传文件放目录
	'updir'		=> 'upload',
	
	//文件预览和在线编辑设置
	'fileopt'	=> array(
		
		//在线预览，microsoft,rockdoc,onlyoffice,mingdao
		'view' 		=> env('ROCK_OFFICEVIEW', 'microsoft'),
		
		//在线驱动编辑，rockdoc,onlyoffice
		'edit' 		=> env('ROCK_OFFICEDIT', 'onlyoffice'),
		
		//自己安装onlyoffice
		'onlyoffice'=> array(
			'url'  => env('ROCK_ONLYOFFICE', 'http://192.168.130.130:8090')
		),
		
		//用官网的
		'rockdoc'	=> array(
			'url'  => 'http://docs.rockoa.com'
		),
		
		//微软的在线预览
		'microsoft'=> array(
			'url'  => 'https://view.officeapps.live.com/op/view.aspx'
		),

		'mingdao'=> array(
			'url' => 'https://docview.mingdao.com/op/view.aspx'
		),
	)
];
