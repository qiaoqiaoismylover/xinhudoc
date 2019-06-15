<?php
/**
*	文件上传地址
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2019-05-20
*/

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;


class UpfileController extends ApiauthController
{
	


	/**
	*	上传文件，需要用户登录的
	*/
	public function upFileacheck(Request $request)
	{
		$this->getUserId();
		$cnum	= $request->get('cnum');
		if(!isempt($cnum))$this->getCompanyId($request, $cnum);
		
		$upobj		= c('upfile');
		$uptype		= $request->get('uptype');
		$thumbnail	= $request->get('thumbnail');
		$updir		= $request->get('updir');
		
		$upobj->initupfile($uptype, $upobj->getupdir($updir));
		$upses	= $upobj->up('file');
		if(is_array($upses)){
			if(isempt($thumbnail))$thumbnail='150x150';//默认缩略图大小
			$upses	= $upobj->uploadback($upses, $thumbnail, array(
				'cid' => $this->companyid,
				'aid' => $this->useaid,
				'uid' => $this->userid,
				'optname' => $this->userinfo->name,
			));
			return $upses;
		}else{
			return $this->returnerror($upses);
		}
	}
	
	
}