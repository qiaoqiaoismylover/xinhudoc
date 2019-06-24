<?php
/**
*	文档协作
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Agent\Agent_docxie;
use App\Model\Agent\Agent_doctpl;
use App\Model\Base\FiledaModel;
use DB;

class ChajianWeapi_docxie extends ChajianWeapi
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
		
		$obj 			= Agent_docxie::select(); //创建个对象
		$obj->where('cid', $this->companyid);
		
		if(!isempt($key)){
			$key = c('rockjm')->base64decode($key);
			$obj->where(function($query)use($key){
				$query->where('fileext', $key);
				$query->orWhere('filename','like', '%'.$key.'%');
				$query->orWhere('recename','like', '%'.$key.'%');
				$query->orWhere('xiename','like', '%'.$key.'%');
				$query->orWhere('optname',$key);
			});
		}
		
		
		if($atype=='receid'){
			$wherestr 	= $this->getNei('devdata')->replacesql('{receid,receid}', false);
			$obj->whereRaw($wherestr);
		}else if($atype=='mycj'){
			$obj->where('aid', $this->useaid);
		}else{
			$wherestr 	= $this->getNei('devdata')->replacesql('{receid,xienameid}', false);
			$obj->whereRaw($wherestr);
		}
		
		$barr['totalCount'] = $obj->count();
		
		
		$dir	= $req->get('dir');
		$sort	= $req->get('sort');
		if(!isempt($sort) && !isempt($dir)){
			$obj->orderBy($sort, $dir);
		}else{
			$obj->orderBy('optdt','desc');
		}
		
		$rowa 				= $obj->simplePaginate($limit, ['*'], 'page', $page)->getCollection();	
		$onbo	= $this->getNei('contain');		
		foreach($rowa as $k=>$rs){
			if($onbo->iscontain($rs->xienameid)){
				$rs->xiebool = true;
			}else{
				$rs->xiebool = false;
			}
		}
		$barr['rows'] 		= $rowa;
		
		//读取文档模版
		$mobj	= Agent_doctpl::select('id','filename');
		$mobj->where('cid', $this->companyid);
		$mobj->where('status', 1);
		
		$wherestr 	= $this->getNei('devdata')->replacesql('{receid,shateid}', false);
		$wherestr	= '((`aid`='.$this->useaid.') or '.$wherestr.')';
		$mobj->whereRaw($wherestr);
		$mobj->orderBy('sort');
		$barr['mtplarr'] = $mobj->get();
		
		
		return returnsuccess($barr);
	}
	
	/**
	*	创建协作文档
	*/
	public function postsave($req)
	{
		$filename = nulltoempty($req->input('filename'));
		$fenlei   = nulltoempty($req->input('fenlei'));
		$explian  = nulltoempty($req->input('explian'));
		$fileext  = $req->input('fileext');
		if(isempt($filename))return returnerror('文档名称不能为空');
		
		//从模版中选择
		$nubool	  = true;
		if(is_numeric($fileext)){
			$mrs  = Agent_doctpl::find($fileext);
			if(!$mrs)return returnerror('文档模版不存在了');
			$filenum	= $mrs->filenum;
			$fileext	= $mrs->fileext;
			$frs 		= FiledaModel::where('filenum', $filenum)->first();
			if($frs){
				if($frs->fileexists){
					$filepath = $frs->filepath;
					$barr['filesize'] = $frs->filesize;
					$nubool	  = false;
				}
			}
		}
		if($nubool){
			$filepath = c('doc')->createword($fileext,'docxie');
		}
		$filename 		  = $filename.'.'.$fileext;
		
		$barr['filename'] = $filename;
		$barr['fileext']  = $fileext;
		$barr['filepath'] = $filepath;
		$barr['cid'] 	= $this->companyid;
		$barr['aid'] 	= $this->useaid;
		$barr['uid'] 	= $this->userid;
		$barr['optname']= $this->adminname;
		
		$barr = c('upfile')->createFileda($barr);
		
		$uarr['cid'] 	= $this->companyid;
		$uarr['aid'] 	= $this->useaid;
		$uarr['uid'] 	= $this->userid;
		$uarr['fenlei'] 	= $fenlei;
		$uarr['optdt']  = $this->now;
		$uarr['adddt']  = $this->now;
		$uarr['optname']   	= $this->adminname;
		$uarr['filename']   = $filename;
		$uarr['fileext']   	= $fileext;
		$uarr['explian']   	= $explian;
		$uarr['filenum']   	= $barr['filenum'];
		
		$uarr['xienameid']   	= 'u'.$this->useaid.'';
		$uarr['xiename']   		= $this->adminname;
		$uarr['receid']   		= 'u'.$this->useaid.'';
		$uarr['recename']   	= $this->adminname;
		
		DB::table('docxie')->insert($uarr);
		
		return returnsuccess('新增文档成功');
	}
	
	/**
	*	删除记录
	*/
	public function postdocxie($req)
	{
		$id = (int)$req->input('id');
		$obj= Agent_docxie::where([
			'id' => $id,
			'cid' => $this->companyid,
		])->first();
		$obj->delete();
		c('upfile')->delTorecycle($obj->filenum);
		return returnsuccess();
	}
	
	public function postoptedit($req)
	{
		$sna = nulltoempty($req->input('sna'));
		$sid = nulltoempty($req->input('sid'));
		$id  = nulltoempty($req->input('id'));
		$lx  = (int)$req->input('lx');
		if(isempt($id))return returnerror('没有选择记录');
		$fid1= 'xienameid';
		$fid2= 'xiename';
		if($lx==1){
			$fid1= 'receid';
			$fid2= 'recename';
		}
		$uarr[$fid1] = $sid;
		$uarr[$fid2] = $sna;
		Agent_docxie::where(array(
			'id' => $id,
			'cid' => $this->companyid,
		))->update($uarr);
		
		return returnsuccess();
	}
}