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
	*	上传文件
	*/
	public function upFileacheck(Request $request)
	{
		$this->getUserId();
		$upobj	= c('upfile');
		$uptype	= $request->get('uptype');
		$cnum		= $request->get('cnum');
		$thumbnail	= $request->get('thumbnail');
		$updir		= $request->get('updir');
		if(!isempt($cnum))$this->getCompanyId($request, $cnum);
		if(isempt($uptype))$uptype = '*';
		if(isempt($updir)){
			$updir=date('Y-m');
		}else{
			$updir=str_replace(array(' ','.'),'', trim($updir));
			$updir=str_replace('{month}',date('Y-m'), $updir);
			$updir=str_replace('{Year}',date('Y'), $updir);
			$updir=str_replace(array('{','}'),'', $updir);
			$updir=str_replace(',','|', $updir);
		}
		$upobj->initupfile($uptype, ''.config('rock.updir').'|'.$updir.'');
		$upses	= $upobj->up('file');
		if(is_array($upses)){
			if(isempt($thumbnail))$thumbnail='150x150';//默认缩略图大小
			$upses	= $upobj->uploadback($upses, $thumbnail, array(
				'cid' => $this->companyid,
				'aid' => $this->useaid,
				'uid' => $this->userid
			));
			return $upses;
		}else{
			return $this->returnerror($upses);
		}
	}
	
	
}