<?php
/**
*	插件-读取文档中心操作
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Agent\Agent_worc;
use App\Model\Agent\Agent_word;
use App\Model\Base\FiledaModel;
use DB;

class ChajianWeapi_docfile extends ChajianWeapi
{
	/**
	*	获取列表数据
	*/
	public function getData($req)
	{
		$fqarr			= $this->getmyfenqu();
		$allfq			= '0';
		foreach($fqarr as $k=>$frs){
			$allfq.=','.$frs['id'].''; //所有分区
		}
		$allfqid		= explode(',', $allfq);
		$barr['fqarr']	= $fqarr;
		$barr['allfq']  = $allfq;
		
		$atype 	 	= $req->get('atype');
		$fqid 	 	= (int)$req->get('fqid','0');
		$folderid 	= (int)$req->get('folderid','0');
		$limit		= (int)$req->get('limit');
		$page		= (int)$req->get('page');
		$key		= $req->get('key');
		
		$obj 			= Agent_word::select(); //创建个对象
		$obj->where('cid', $this->companyid);
		
		if(!in_array($atype,['shateall','shatewfx']))$obj->whereIn('fqid', $allfqid);
		
		if($fqid>0){
			$obj->where('fqid', $fqid);
			if($folderid==0)$obj->where('folderid', $folderid);
		}
		if($folderid>0)$obj->where('folderid', $folderid);
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
		}
		
		//我共享
		if($atype=='shatewfx'){
			$obj->where('shaterenid', $this->useaid);
		}
		
		$barr['totalCount'] = $obj->count();
		
		$obj->orderBy('type','desc');
		$dir	= $req->get('dir');
		$sort	= $req->get('sort');
		if(!isempt($sort) && !isempt($dir)){
			$obj->orderBy($sort, $dir);
		}else{
			$obj->orderBy('sort','asc');
			$obj->orderBy('optdt','desc');
		}
		
		$rowa 				= $obj->simplePaginate($limit, ['*'], 'page', $page)->getCollection();		
		
		foreach($rowa as $k=>$rs){	
			$downshu = 0;
			if($rs->type==1){
				$downshu = Agent_word::where('folderid', $rs->id)->count();
				$rs->optname = '';
			}
			$rs->downshu = $downshu;
			
			//关联文件读取，判断是不是存在
			$filenum = $rs->filenum;
			if(!isempt($filenum)){
				$frs = FiledaModel::where('filenum', $filenum)->first();
				if(!$frs){
					$rs->ishui = 1;
					$rs->thumbpath = '';
				}else{
					if(!$frs->fileexists){
						$rs->ishui = 1;
						$rs->thumbpath = '';
					}
				}
			}
		}
		$barr['rows'] 		= $rowa;
		
		
		if($fqid==0){
			$lujarr[] = array(
				'name' 		=> '所有分区',
				'fqid' 		=> 0,
				'folderid'  => 0,
			);
		}else{
			$fqrs = Agent_worc::find($fqid);
			$lujarr[] = array(
				'name' 		=> $fqrs->name,
				'fqid' 		=> $fqrs->id,
				'folderid'  => 0,
			);
		}
		
		
		if($folderid>0){
			$this->folderarr = array();
			$this->folderarrget($folderid);
			foreach($this->folderarr as $k=>$rs){
				$lujarr[] = array(
					'name' 		=> $rs->filename,
					'fqid' 		=> $rs->fqid,
					'folderid'  => $rs->id,
				);
			}
		}
		$barr['lujarr'] = $lujarr;
		$barr['officeview'] = env('ROCK_OFFICEVIEW');
		$barr['officeedit'] = env('ROCK_OFFICEDIT');
		
		return returnsuccess($barr);
	}
	
	//获取文件夹路径
	private function folderarrget($id)
	{
		$frs = Agent_word::find($id);
		if($frs){
			if($frs->folderid>0)$this->folderarrget($frs->folderid);
			$this->folderarr[] = $frs;
		}
	}
	
	//获取分数列表管理数据
	public function getworcdata($req)
	{
		$limit			= (int)$req->get('limit');
		$page			= (int)$req->get('page');
		$key			= $req->get('key');
		
		$obj 			= Agent_worc::select(); //创建个对象
		$obj->where('cid', $this->companyid);
		
		//非管理员只能看到自己的分区
		if($this->useainfo->type==0){
			$obj->where('aid', $this->useaid);
		}
		
		$barr['totalCount'] = $obj->count();
		
		$dir	= $req->get('dir');
		$sort	= $req->get('sort');
		if(!isempt($sort) && !isempt($dir)){
			$obj->orderBy($sort, $dir);
		}else{
			$obj->orderBy('sort');
		}
		
		$rowa 				= $obj->simplePaginate($limit, ['*'], 'page', $page)->getCollection();	
							
		$barr['rows'] 		= $rowa;
		
		
		$fqarr			= $this->getmyfenqu();
		$allfq			= '0';
		foreach($fqarr as $k=>$frs){
			$allfq.=','.$frs['id'].''; //所有分区
		}
		$barr['fqarr']	= $fqarr;
		$barr['allfq']  = $allfq;
		
		return returnsuccess($barr);
	}
	
	/**
	*	创建分区
	*/
	public function postcreateworc($req)
	{
		$name = trim($req->input('name'));
		if(isempt($name))return returnerror('分区名称不能为空');
		
		$uarr['cid'] = $this->companyid;
		$uarr['aid'] = $this->useaid;
		$uarr['uid'] = $this->userid;
		$uarr['status'] = 1;
		$uarr['name']   = $name;
		$uarr['optdt']   = $this->now;
		$uarr['size']    = 1024*1024*1024; //默认1G
		$uarr['optname']   	= $this->useainfo->name;
		$uarr['optid']   	= $this->useaid;
		$uarr['receid']   	= 'u'.$this->useaid.'';
		$uarr['recename']   = $this->useainfo->name;
		$uarr['guanid']   	= 'u'.$this->useaid.'';
		$uarr['guanname']   = $this->useainfo->name;
		$uarr['upuserid']   	= 'u'.$this->useaid.'';
		$uarr['upuser']   	= $this->useainfo->name;
		
		DB::table('worc')->insert($uarr);
		
		return returnsuccess();
	}
	
	/**
	*	删除分区
	*/
	public function postdelworc($req)
	{
		$id = (int)$req->input('id');
		$to = Agent_word::where('fqid', $id)->count();
		if($to>0)return returnerror('此分区下有文件/文件夹不能删除');
		Agent_worc::where('id', $id)->where('cid', $this->companyid)->delete();
		return returnsuccess();
	}
	
	/**
	*	删除文件
	*/
	public function postdelfile($req)
	{
		$id = (int)$req->input('id');
		$to = Agent_word::where('folderid', $id)->count();
		if($to>0)return returnerror('此文件夹下有文件不能删除');
		
		$obj= Agent_word::where([
			'id' => $id,
			'cid' => $this->companyid,
		])->first();
		$obj->delete();
		c('upfile')->delTorecycle($obj->filenum);
		return returnsuccess();
	}
	
	/**
	*	保存字段
	*/
	public function postsavefields($req)
	{
		$tlx 	= $req->input('tablename');
		$tabar['a'] = 'worc';
		$tabar['b'] = 'word';
		$tabar['c'] = 'doctpl';
		$tabar['d'] = 'docxie';
		if(!isset($tabar[$tlx]))return returnerror('无效操作');
		
		$table 	= $tabar[$tlx];
		$id 	= (int)$req->input('id');
		$fields = $req->input('fieldname');
		$value  = nulltoempty($req->input('value'));
		$filenum= '';
		
		if($fields=='filename'){
			if(isempt($value))return returnerror('名称不能为空');
			$drs = DB::table($table)->where('id', $id)->first();
			$filenum = objvalue($drs,'filenum');
			$fileext = objvalue($drs,'fileext');
			if($drs->type==0 && !contain(strtolower($value),'.'.$fileext.''))
				return returnerror('不允许修改扩展名');
		}
		
		$uarr[$fields] = $value;
		DB::table($table)->where('id', $id)->where('cid', $this->companyid)->update($uarr);
		
		if(!isempt($filenum)){
			DB::table('fileda')->where('filenum', $filenum)->update(array(
				'filename' => $value
			));	
		}
		
		return returnsuccess();
	}
	
	/**
	*	分区修改对应管理员
	*/
	public function posteditworc($req)
	{
		$id = (int)$req->input('id');
		$lx = (int)$req->input('lx');
		
		$zd1= 'receid';
		$zd2= 'recename';
		$tab= 'worc';
		if($lx==1){
			$zd1= 'guanid';
			$zd2= 'guanname';
		}
		if($lx==2){
			$zd1= 'upuserid';
			$zd2= 'upuser';
		}
		if($lx==3){
			$zd1= 'shateid';
			$zd2= 'shatename';
			$tab= 'doctpl';
		}
		$uarr[$zd1] = nulltoempty($req->input('sid'));
		$uarr[$zd2] = nulltoempty($req->input('sna',''));
		
		DB::table($tab)->where('id', $id)->where('cid', $this->companyid)->update($uarr);
		return returnsuccess();
	}
	
	
	//获取我的分区
	public function getmyfenqu()
	{
		$obj 	= Agent_worc::select();
		$obj->where('cid', $this->companyid);
		$obj->where('status', 1);
		$rows 	= $obj->orderBy('sort')->get();
		$barr 	= array();
		$onbo	= $this->getNei('contain');
		$bobj	= $this->getNei('base');
		foreach($rows as $k=>$rs){
			if(!$onbo->iscontain($rs->receid))continue;
			$isup = 0;//是否可以上传
			$isguan = 0;//是否可以管理
			if($onbo->iscontain($rs->upuserid))$isup = 1;
			if($onbo->iscontain($rs->guanid))$isguan = 1;

			$barr[] = array(
				'name' 		=> $rs->name,
				'id'		=> $rs->id,
				'isup'		=> $isup,
				'uptype'	=> $rs->uptype,
				'isguan'	=> $isguan,
				'sizecn'	=> $bobj->formatsize($rs->sizeu)
			);
		}
		
		return $barr;
	}
	
	
	
	
	/**
	*	创建文件夹
	*/
	public function postcreatefolder($req)
	{
		$name = trim($req->input('name'));
		if(isempt($name))return returnerror('文件名称不能为空');
		
		$uarr['cid'] = $this->companyid;
		$uarr['aid'] = $this->useaid;
		$uarr['uid'] = $this->userid;
		$uarr['fqid'] = (int)$req->input('fqid');
		if($uarr['fqid']==0)return returnerror('没有选择分区');
		
		$uarr['folderid'] = (int)$req->input('folderid');
		$uarr['filename']   = $name;
		$uarr['type']   	= '1'; //1说明是文件夹
		$uarr['optdt']   	= $this->now;
		$uarr['optname']   	= $this->useainfo->name;
		DB::table('word')->insert($uarr);
		
		return returnsuccess();
	}
	
	/**
	*	保存文件
	*/
	public function postsavefile($req)
	{
		$ids   = $req->input('ids');
		if(isempt($ids))return returnerror('无文件保存');
		$uparr = FiledaModel::whereIn('id', explode(',', $ids))->get();
		$fqid  = (int)$req->input('fqid');
		$folderid  = (int)$req->input('folderid');
		if($fqid==0)return returnerror('没有选择分区');
		
		$uarr['cid'] = $this->companyid;
		$uarr['aid'] = $this->useaid;
		$uarr['uid'] = $this->userid;
		$uarr['optdt'] = $this->now;
		$uarr['optname']   	= $this->useainfo->name;
		$uarr['fqid'] = $fqid;
		$uarr['folderid'] = $folderid;
		$uarr['type'] = '0';
		
		foreach($uparr as $k=>$rs){
			$uarr['filename'] 	= $rs->filename;
			$uarr['filenum'] 	= $rs->filenum;
			$uarr['fileext'] 	= $rs->fileext;
			$uarr['thumbpath'] 	= nulltoempty($rs->thumbpath);
			$uarr['filesizecn'] = $rs->filesizecn;
			$uarr['filesize'] 	= $rs->filesize;
			DB::table('word')->insert($uarr); 
		}
		$this->worctongji();//统计分区大小
		return returnsuccess();
	}
	
	/**
	*	获取文件来预览
	*/
	public function getfile($req)
	{
		$filenum = nulltoempty($req->get('filenum'));
		$frs	 = FiledaModel::where('filenum', $filenum)->first();
		if(!$frs)return returnerror('文件记录不存在了');
		$filepath= $frs->filepath;

		$filepath = $frs->filepath;
		if(!file_exists(public_path($filepath)))return returnerror('文件不存在');
		
		
		$url 	= \Rock::replaceurl('/'.$filepath);
		return returnsuccess(array(
			'filepath' => $url,
			'filenum'  => $filenum,
		));
	}
	
	/**
	*	共享操作
	*/
	public function postshate($req)
	{
		$sna = nulltoempty($req->input('sna'));
		$sid = nulltoempty($req->input('sid'));
		$fid = nulltoempty($req->input('fid'));
		if(isempt($fid))return returnerror('没有选择记录');
		$unam= $this->useainfo->name;
		$uren= $this->useaid;
		if(isempt($sna)){
			$unam = '';
			$uren = 0;
		}
		Agent_word::whereIn('id', explode(',', $fid))
				->where('cid', $this->companyid)
				->where('type',0)
				->update(array(
					'shateid' 	=> $sid,
					'shatename' => $sna,
					'shateren'  => $unam,
					'shaterenid'  => $uren,
				));
		return returnsuccess();		
	}
	
	/**
	*	要移动获取分区
	*/
	public function getfenqu($req)
	{
		$fqarr			= $this->getmyfenqu();
		$arr  = array();
		foreach($fqarr as $k=>$rs){
			if($rs['isguan']==0)continue;
			$fqid  = $rs['id']; //区id
			$arr[] = array(
				'fqid' 	=> $fqid,
				'folderid'=> 0,
				'iconsimg'=> '/images/wjj.png',
				'iconswidth'=> '24',
				'name' 	=> $rs['name'],
				'subname'=>'分区'
			);
			
			$rowa  = $this->getfolders($fqid);
			foreach($rowa as $k1=>$rs1){
		
				$arr[] = array(
					'iconsimg' => '/images/folder.png',
					'fqid' => $fqid,
					'folderid' => $rs1->id,
					'name' => $rs1->filename,
					'padding' => $rs1->padding,
				);
			}
		}
		
		return returnsuccess($arr);
	}
	//获取文件夹,获取对应子目录
	public function getfolders($fqid, $folderid=0)
	{
		$rows = Agent_word::where(array(
			'fqid'=>$fqid,
			'type'=>1,
		))->orderBy('sort')->get();
		$this->getfoldersa = array();
		$this->getfolderss($rows, $folderid,1);
		return $this->getfoldersa;
	}
	private function getfolderss($rows, $folderid, $lev=1)
	{
		foreach($rows as $k=>$rs){
			if($rs->folderid==$folderid){
				$rs->padding = 24*$lev;
				$this->getfoldersa[] = $rs;
				$this->getfolderss($rows, $rs->id, $lev+1);
			}
		}
	}
	
	public function getUsize($fqid)
	{
		$sizeu = Agent_word::where(array(
				'fqid' => $fqid,
				'type' => 0,
			))->sum('filesize');
		if(!$sizeu)$sizeu=0;	
		return $sizeu;	
	}
	
	//统计分区使用大小
	public function worctongji()
	{
		$obj = Agent_worc::select();
		$rows= $obj->get();
		foreach($rows as $k=>$rs){
			$rs->sizeu = $this->getUsize($rs->id);
			$rs->save();
		}
		return returnsuccess();
	}
	
	/*
	*	移动文件夹
	**/
	public function postmovefile($req)
	{
		$fqid = (int)$req->input('fqid');
		$folderid = (int)$req->input('folderid');
		$ids = $req->input('ids');
		$idsa = explode(',', $ids);
		if(isempt($ids))return returnerror('没有选择文件');
		
		if($folderid>0){
			//判断是否在自己文件夹下
			$foldpath = $this->getpath($folderid);
			$foldar   = array();
			foreach($foldpath as $k1=>$rs1)$foldar[] = $rs1['id'];
			foreach($idsa as $ids1){
				if(in_array($ids1, $foldar))return returnerror('['.$ids1.']不能移动到自己的子目录下');
			}
		}
		
		Agent_word::whereIn('id', $idsa)->update(array(
			'fqid' => $fqid,
			'folderid' => $folderid,
		));
		
		
		//获取所有下级需要更新分区Id
		$this->moveaddid 	= array();
		$this->getmovedanow($idsa);
		if($this->moveaddid){
			Agent_word::whereIn('id', $this->moveaddid)->update(array(
				'fqid' => $fqid
			));
		}
		$this->worctongji();
		return returnsuccess();
	}
	//获取所有下级ID
	private function getmovedanow($idsa)
	{
		$addid 	= array();
		$rows 	= Agent_word::whereIn('folderid', $idsa)->get();
		foreach($rows as $k=>$rs){
			$addid[] = $rs->id;
			$this->moveaddid[] = $rs->id;
		}
		if($addid){
			$this->getmovedanow($addid);
		}
	}
	//获取路径
	public function getpath($id)
	{
		$this->pathss = array();
		$this->getpaths($id);
		return $this->pathss;
	}
	private function getpaths($id)
	{
		$rs = Agent_word::find($id);
		if($rs){
			$this->getpaths($rs->folderid);
			$this->pathss[] = array('id'=>$rs->id);
		}
	}
	
	
	/**
	*	发送文件编辑预览
	*/
	public function sendedit($req)
	{
		$id 		= (int)$req->get('id',0);
		$otype 		= (int)$req->get('otype',0);
		$ckey		= $req->get('ckey');
		$callb		= $req->get('callb');
		return $this->getNei('rockedit')->sendedit($id, $ckey, $otype, $callb);
	}
}