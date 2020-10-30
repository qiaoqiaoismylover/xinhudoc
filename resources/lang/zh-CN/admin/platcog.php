<?php
/**
*	语言包
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

return [

	'platcogdesc'	=> '此设置是修改平台系统配置文件.env，如无写入权限请直接到服务器下修改。',
	
	'infotit'	=> '基本信息',
	'logotit'	=> '平台Logo',
	'smstit'	=> '短信和安全',
	'uploadtit'	=> '文件上传地址',
	'guantit'	=> '关联信呼官网',
	'origintit'	=> '跨域上传地址',
	'filetit'	=> '文档预览编辑',
	
	'name'				=> '平台名称',
	'name_msg'			=> '不设置就为默认名称',
	'nameadmin'			=> '管理后台名称',
	'nameadmin_msg'		=> '不设置就为默认名称',
	
	'logo'				=> '平台Logo',
	
	'openreg'			=> '是否开放注册',
	'openreg_false'		=> '不开放',
	'openreg_true'		=> '开放注册',
	
	'debug'				=> 'debug模式',
	'debug_false'			=> '生产上线模式',
	'debug_true'			=> '开发者debug模式',
	
	'url'				=> '平台URL',
	'url_msg'			=> '以http开头，默认自动识别',
	
	'urllocal'			=> '平台本地地址',
	'urllocal_msg'		=> '以http开头，不知道就不用设置，这个目前没啥用',
	
	'appurly'			=> '平台外网地址',
	'appurly_msg'		=> '以http开头，默认自动识别，不需要设置',
	
	'randkey'			=> '系统随机密钥',
	'randkey_msg'		=> '由26位英文字母组成，不要乱写哦',
	
	
	'smsprovider'			=> '短信使用平台',
	'smsprovider_ali'		=> '阿里短信服务(未开发)',
	'smsprovider_xinxi'		=> '企业信使',
	'smsprovider_yunpian'	=> '云片网(未开发)',
	'smsprovider_xinhu'		=> '信呼官网短信服务',
	
	
	'baseurl'		=> '文件上传地址',
	'baseurl_msg'		=> '当文件上传重新部署站点就需要设置，默认是/base',
	
	'basekey'		=> '链接密钥',
	'basekey_msg'		=> '连接上面那网址的密钥',
	
	'urly'		=> '信呼官网地址',
	'urly_msg'	=> '修改成其他就不能在线升级了，默认是http://www.rockoa.com',
	
	'xinhukey'		=> '信呼官网key',
	'xinhukey_msg'	=> '信呼官网用户中心的key，用于在线升级',
	
	'xinhukey_help'	=> '如何获取？请查看',
	
	'xinhu'			=> '开发团队',
	'xinhu_msg'		=> '开发团队的名称，建议不要修改',
	
	'origin'			=> '允许跨域地址',
	'origin_msg'		=> '全部就写*，为空就是不允许，格式写如:http://docs.rockoa.com，多个,分开',
	
	'officeview'	=> '文档预览',
	'officeview_help'	=> '这几个是什么区别？更多查看',
	'officeedit'	=> '文档编辑',
	
	'officeview_microsoft'		=> '使用微软提供在线预览(需外网域名80端口)',
	'officeview_mingdao'		=> '使用明道提供在线预览(需外网域名80端口)',
	'officeview_rockdoc'		=> '信呼官网提供(需在外网上)',
	'officeview_onlyoffice'		=> '自己安装onlyoffice服务',
	'officeview_rockoffice'		=> '用信呼文档编辑平台，需设置预览编辑key',
	
	'onlyoffice'	=> '自己部署onlyoffice服务地址',
	'onlyoffice_help'	=> '如何部署onlyoffice服务？请看',
	
	
	'yzmlogin'		=> '短信验证码登录',
	'yzmlogin_false'=> '不需要',
	'yzmlogin_true'=> '需要(建议)待开发',
	
	
	'onlyoffice_msg'	=> '自己部署这必须填写，http开头',
	
	'blackip'		=> '访问IP黑名单',
	'blackip_msg'	=> '设置哪些IP禁止访问，写*为全部，多个,分开',
	
	'whiteip'		=> '访问IP白名单',
	'whiteip_msg'	=> '设置哪些IP可以访问，多个,分开',
	
	
	'rockoffice_url' => '预览编辑平台地址',
	'rockoffice_key' => '预览编辑key',
	'rockoffice_keymsg' => '请看帮助获取',
	'rockoffice_msg' => '可不设置，默认用由信呼官网提供，必须http头',
];
