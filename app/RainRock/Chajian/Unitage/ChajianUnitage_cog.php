<?php
/**
*	管理首页-设置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;


use App\Model\Base\UsersModel;

class ChajianUnitage_cog extends ChajianUnitage
{
	
	public function getForm($request)
	{
		$udata	= UsersModel::find($this->companyinfo->uid);
		
		$obj 	= $this->getNei('option');
		$xinhuoa= array(
			'xinhuoaurl' => $obj->getval('xinhuoaurl'),
			'xinhuoakey' => $obj->getval('xinhuoakey'),
		);
		
		return [
			'data' => $this->companyinfo,
			'udata' => $udata,
			'xinhuoa'=> $xinhuoa
		];
	}
	
	/**
	*	邮件设置
	*/
	public function emailForm()
	{
		
		return [
			
		];
	}
}