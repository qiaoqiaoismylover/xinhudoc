<?php
/**
*	插件-读取文档中心操作
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Base\FiledaModel;
use DB;

class ChajianWeapi_fileda extends ChajianWeapi
{
	/**
	*	获取列表数据
	*/
	public function getData($req)
	{
		$limit		= (int)$req->get('limit');
		$page		= (int)$req->get('page');
		$key		= $req->get('key');
		$atype		= $req->get('atype');
		
		$obj 			= FiledaModel::select(); //创建个对象
		$obj->where('cid', $this->companyid);
		
		if(!isempt($key)){
			$key = c('rockjm')->base64decode($key);
			$obj->where(function($query)use($key){
				$query->where('fileext', $key);
				$query->orWhere('filename','like', '%'.$key.'%');
				$query->orWhere('optname',$key);
			});
		}
		
		//共享给我的
		if($atype=='mytrash'){
			$obj->where('aid', $this->useaid);
			$obj->where('isdel', 1);
		}else if($atype=='alltrash'){
			$obj->where('isdel', 1);	
		}else{
			$obj->where('aid', $this->useaid);
		}
		
		$barr['totalCount'] = $obj->count();
		
		
		$dir	= $req->get('dir');
		$sort	= $req->get('sort');
		if(!isempt($sort) && !isempt($dir)){
			$obj->orderBy($sort, $dir);
		}else{
			$obj->orderBy('id','desc');
		}
		
		$rowa 				= $obj->simplePaginate($limit, ['*'], 'page', $page)->getCollection();		
		
		$barr['rows'] 		= $rowa;
		
		
		return returnsuccess($barr);
	}
	
	public function postdelfile($req)
	{
		$id = $req->input('id');
		if(isempt($id))return returnerror('id empty');
		$id = c('check')->onlynumber($id);
		$ida= explode(',', $id);
		$row= FiledaModel::whereIn('id', $ida)->get();
		$fobj = c('upfile');
		foreach($row as $k=>$rs)$fobj->delfile($rs->filenum);
		return returnsuccess();
	}
}