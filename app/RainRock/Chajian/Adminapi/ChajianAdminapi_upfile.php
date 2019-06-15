<?php
/**
*	总管理管理后台上传文件地址
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Adminapi;


use Illuminate\Http\Request;

class ChajianAdminapi_upfile extends ChajianAdminapi
{
	
	/**
	*	上传文件：/webapi/admin/upfile
	*/
	public function postData(Request $request)
	{
		$upobj		= c('upfile');
		$uptype		= $request->get('uptype');
		$thumbnail	= $request->get('thumbnail');
		$updir		= $request->get('updir');
		
		$upobj->initupfile($uptype, $upobj->getupdir($updir));
		$upses	= $upobj->up('file');
		if(is_array($upses)){
			if(isempt($thumbnail))$thumbnail='150x150';//默认缩略图大小
			$upses	= $upobj->uploadback($upses, $thumbnail, array(
				'optname' => $this->admininfo->name,
			));
			return returnsuccess($upses);
		}else{
			return returnerror($upses);
		}
	}
	
}	