<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     * 后台认证验证
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
		parent::__construct();
    }
	
	
	public function getShowView($tpl, $params=array())
	{
		$arr = [
			'tpl' 			=> $tpl,
			'bootstyle'		=> $this->getBootstyle(false,1),
			'helpstr'		=> c('help')->helpstr($tpl, arrvalue($params,'kzq'), arrvalue($params,'lang'))
		];
		foreach($params as $k=>$v)$arr[$k]=$v;
		return view($tpl, $arr);
	}
}
