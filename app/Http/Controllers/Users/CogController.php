<?php
/**
*	用户设置
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Users;



class CogController extends UsersController
{
  

    public function showCogForm()
    {
		
		$mybot 		= \Auth::user()->bootstyle;
		$stylearr	= c('bootstyle')->getStylearr();
		foreach($stylearr as &$rs){
			$rs['checked'] = ($rs['value']==$mybot)?'checked':'';
		}
        return view('users/cog',[
			'stylearr' 	=> $stylearr,
			'data'		=> \Auth::user(),
			'pagetitle' => '平台用户中心个人设置',
			'style'		=> $this->getBootstyle()
		]);
    }
}
