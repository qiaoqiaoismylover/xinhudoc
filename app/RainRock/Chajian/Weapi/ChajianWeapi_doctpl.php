<?php
/**
*	插件-读取文档中心操作
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Agent\Agent_doctpl;
use App\Model\Base\FiledaModel;
use DB;

class ChajianWeapi_doctpl extends ChajianWeapi
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
		
		$obj 			= Agent_doctpl::select(); //创建个对象
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
		if($atype=='shateall'){
			$wherestr 	= $this->getNei('devdata')->replacesql('{receid,shateid}', false);
			$obj->whereRaw($wherestr);
		}else{
			if($this->useainfo->type==0)
				$obj->where('aid', $this->useaid);
		}
		
		
		
		$barr['totalCount'] = $obj->count();
		
		
		$dir	= $req->get('dir');
		$sort	= $req->get('sort');
		if(!isempt($sort) && !isempt($dir)){
			$obj->orderBy($sort, $dir);
		}else{
			$obj->orderBy('sort','asc');
		}
		
		$rowa 				= $obj->simplePaginate($limit, ['*'], 'page', $page)->getCollection();		
		
		$barr['rows'] 		= $rowa;
		
		
		return returnsuccess($barr);
	}
	
	/**
	*	创建模版
	*/
	public function postsavetpl($req)
	{
		$filename = nulltoempty($req->input('filename'));
		$fileext  = $req->input('fileext');
		
		$filepath = c('doc')->createword($fileext,'doctpl');
		$filename = $filename.'.'.$fileext;
		
		$barr['filename'] = $filename;
		$barr['fileext']  = $fileext;
		$barr['filepath'] = $filepath;
		$barr['cid'] 	= $this->companyid;
		$barr['aid'] 	= $this->useaid;
		$barr['uid'] 	= $this->userid;
		
		$barr = c('upfile')->createFileda($barr);
		
		$uarr['cid'] 	= $this->companyid;
		$uarr['aid'] 	= $this->useaid;
		$uarr['uid'] 	= $this->userid;
		$uarr['optdt']  = $this->now;
		$uarr['optname']   	= $this->useainfo->name;
		$uarr['filename']   = $filename;
		$uarr['fileext']   	= $fileext;
		$uarr['filenum']   	= $barr['filenum'];
		DB::table('doctpl')->insert($uarr);
		
		return returnsuccess('创建成功');
	}
	
	/**
	*	删除模版
	*/
	public function postdeltpl($req)
	{
		$id = (int)$req->input('id');
		$obj= Agent_doctpl::where([
			'id' => $id,
			'cid' => $this->companyid,
		])->first();
		$obj->delete();
		c('upfile')->delfile($obj->filenum);
		return returnsuccess();
	}
	
}