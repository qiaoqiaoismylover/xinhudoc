<?php
/**
*	文件预览删除下载等
*	主页：http://www.rockoa.com/
*	软件：信呼文件管理平台
*	作者：雨中磐石(rainrock)
*	时间：2019-05-22
*/

namespace App\Http\Controllers\Api;


use App\Model\Base\FiledaModel;
use Illuminate\Http\Request;
use Illuminate\Http\Testing\MimeType;
use Rock;

class FileoptController extends ApiauthController
{

	private function filetypeget()
	{
		$opelx= ',txt,log,html,htm,js,php,php3,md,cs,sql,java,json,css,asp,aspx,shtml,cpp,c,vbs,jsp,xml,bat,sh,';
		$offic= ',doc,docx,xls,xlsx,ppt,pptx,';
		$video= ',mp4,';
		$audio= ',mp3,ogg,';
		return array(
			'opelx' => $opelx,
			'offic' => $offic,
			'video' => $video,
			'audio' => $audio,
		);
	}
	
	private function getdocumentType($filext)
	{
		$lx = 'text';
		$xles = explode(',', 'xls,xlsx,ods,csv');
		$ppps = explode(',', 'ppt,pptx,odp,csv');
		if(in_array($filext, $xles))$lx='spreadsheet';
		if(in_array($filext, $ppps))$lx='presentation';
		return $lx;
	}
	
	/**
	*	预览打开
	*/
	public function fileview($cnum, $filenum, Request $request)
	{
		$this->getCompanyId($request, $cnum);
		if($this->companyid==0)return $this->returntishi('无效访问');
		
		$jm  = c('rockjm');
		$frs = FiledaModel::where('filenum', $jm->base64decode($filenum))->first();
		if(!$frs)return $this->returntishi('文件记录不存在');
		$filename = $request->input('filename');
		if(!isempt($filename)){
			$filename = $jm->base64decode($filename);
		}else{
			$filename = $frs->filename;
		}
		$fileext = $frs->fileext;
		$filepath = $frs->filepath;
		if(!$frs->fileexists)return $this->returntishi('文件不存在');
		
		
		$barr['filename'] 		= $filename;
		$barr['companyinfo']  	= $this->companyinfo;
		$tplv = '';
		$bople= $this->filetypeget();
		
		$opelx= $bople['opelx'];
		$offic= $bople['offic'];
		$video= $bople['video'];
		$audio= $bople['audio'];
		
		if($fileext=='pdf'){
			$tplv = 'pdf';
			$barr['filepath'] = $jm->base64encode('/'.$filepath);
		}else if(contain($opelx,','.$fileext.',')){
			$tplv 		= 'open';
			$content  	= file_get_contents($filepath);
			if(substr($filepath,-6)=='uptemp')$content = base64_decode($content);
			$bm =  c('check')->getencode($content);
			if(!contain($bm, 'utf')){
				$content = @iconv($bm,'utf-8', $content);
			}
			$barr['content'] = $content;
		}else if(contain($offic,','.$fileext.',')){
			$pdfpath= $frs->pdfpath;
			if(isempt($pdfpath) || !file_exists(public_path($filepath))){
				$conf 	= config('rock.fileopt');
				$viewqd = $conf['view'];
				if(!isset($conf[$viewqd]))return $this->returntishi('没有配置此预览驱动'.$viewqd.'');
				$qdconf = $conf[$viewqd];
				
				$url 	= Rock::replaceurl($filepath,1);
				if($viewqd=='microsoft' || $viewqd=='mingdao'){
					$tplv 	= 'iframe';
					$url	= $qdconf['url'].'?src='.urlencode($url).'';
				}
				if($viewqd=='onlyoffice'){
					$tplv 	= $viewqd;
					$barr['onlyurl']  = $qdconf['url'];
					$barr['filenum']  = $filenum;
					$barr['appurl']   = config('app.url');
					$barr['useainfo']  	  	= $this->useainfo;
					$barr['documentType']  	 = $this->getdocumentType($fileext);
					$barr['viewtype'] = c('base')->ismobile() ? 'mobile' : 'desktop';
				}
				
				//用官网的
				if($viewqd=='rockdoc'){
					
				}
				if($tplv=='')return $this->returntishi('没有开发此预览驱动'.$viewqd.'');
				
				$barr['url'] 	= $url;
				$barr['fileext'] = $fileext;
			}else{
				$tplv = 'pdf';
				$barr['filepath'] = $jm->base64encode('/'.$pdfpath);
			}
		}else if(contain($video,','.$fileext.',')){
			$tplv 		= 'video';
			$barr['url']= '/'.$filepath;
		}else if(contain($audio,','.$fileext.',')){
			$tplv 		= 'audio';
			$barr['url']= '/'.$filepath;	
		}else{
			return $this->returntishi('无法在线预览此['.$fileext.']文件类型，请使用下载功能');
		}
		
		if($tplv!='')return view('base.fileview_'.$tplv.'', $barr);
	}
	
	public function filesend($cnum, $filenum, Request $request)
	{
		$this->getCompanyId($request, $cnum);
		if($this->companyid==0)return returnerror('无效访问');
		
		$jm  = c('rockjm');
		$frs = FiledaModel::where('filenum', $jm->base64decode($filenum))->first();
		if(!$frs)return returnerror('文件记录不存在');
		$filename = $frs->filename;
		$fileext  = $frs->fileext;
		$filepath = $frs->filepath;
		if(!$frs->fileexists)return returnerror('文件不存在');
		$lx		    = (int)$request->get('lx');
		
		$utes		= 'edit';
		if($lx==1){
			$filename = '(只读)'.$filename.'';
			$utes     = 'yulan';
		}
		
		$saveurl = config('app.url').'/fileeditcall/'.$this->companyinfo->num.'/'.$filenum.'';
		$saveurl.= '?usertoken='.$this->usertoken.'&useragent='.$this->useragent.'&saveurl=true';
		
		$arr	 = array();
		$arr[0]  = $saveurl; 
		$arr[1]  = $filename;
		$arr[2]  = $this->createtempurl($frs);//生成键值
		$arr[3]  = Rock::replaceurl($filepath); //下载地址
		$arr[4]  = $frs->id;
		$arr[5]  = 0;
		$arr[6]  = 'wu';
		$arr[7]  = $utes;
		$arr[8]  = $fileext;
		
		$str 	= '';
		foreach($arr as $s1)$str.=','.$s1.'';
		
		return returnsuccess(substr($str,1));
	}
	
	private function createtempurl($frs)
	{
		$str = 'rockdoc_'.md5(config('app.url')).'_'.$frs->filesize.'_'.$frs->filenum.'.'.$frs->fileext.'';
		return $str;
	}
	
	/**
	*	文件编辑
	*/
	public function fileedit($cnum, $filenum, Request $request)
	{
		$this->getCompanyId($request, $cnum);
		if($this->companyid==0)return $this->returntishi('无效访问');
		
		$jm  = c('rockjm');
		$frs = FiledaModel::where('filenum', $jm->base64decode($filenum))->first();
		if(!$frs)return $this->returntishi('文件记录不存在');
		$filename = $request->get('filename');
		if(!isempt($filename)){
			$filename = $jm->base64decode($filename);
		}else{
			$filename = $frs->filename;
		}
		$fileext  = $frs->fileext;
		$filepath = $frs->filepath;
		if(!$frs->fileexists)return $this->returntishi('文件不存在');
		
		
		$barr['filename'] 		= $filename;
		$barr['companyinfo']  	= $this->companyinfo;
		$barr['cnum'] 	  = $this->companyinfo->num;
		$tplv = '';
		$bople= $this->filetypeget();
		
		$opelx= $bople['opelx'];
		$offic= $bople['offic'];
		
		if(contain($opelx,','.$fileext.',')){
			$tplv 		= 'open';
			$content  	= file_get_contents($filepath);
			if(substr($filepath,-6)=='uptemp')$content = base64_decode($content);
			$bm =  c('check')->getencode($content);
			if(!contain($bm, 'utf')){
				$content = @iconv($bm,'utf-8', $content);
			}
			$barr['content'] = $content;
		}else if(contain($offic,','.$fileext.',')){
			$conf 	= config('rock.fileopt');
			$viewqd = $conf['edit'];
			if(!isset($conf[$viewqd]))return $this->returntishi('没有配置此编辑驱动'.$viewqd.'');
			$qdconf = $conf[$viewqd];
			$url 	= Rock::replaceurl($filepath,1);
			if($viewqd=='onlyoffice'){
				$tplv 	= $viewqd;
				$barr['onlyurl']  = $qdconf['url'];
				$barr['filenum']  = $filenum;
				$barr['appurl']   = config('app.url');
				$barr['useainfo']  	  	= $this->useainfo;
				$barr['documentType']  	= $this->getdocumentType($fileext);
				$barr['viewtype'] 	 = c('base')->ismobile() ? 'mobile' : 'desktop';
				$callbackUrl	  	 = Rock::replaceurl('fileeditcall/'.$barr['cnum'].'/'.$filenum.'',1);
				$barr['callbackUrl'] = $callbackUrl;//在线编辑回调保存地址
				$barr['callbackCan'] = $jm->base64encode('?usertoken='.$this->usertoken.'&useragent='.$this->useragent.'');
			}
			//用官网的
			if($viewqd=='rockdoc'){
				
			}
			
			$barr['url'] 	= $url;
			$barr['fileext'] = $fileext;
		}else{
			return $this->returntishi('无法在线编辑此['.$fileext.']文件类型，请使用下载功能');
		}
		
		if($tplv!='')return view('base.fileedit_'.$tplv.'', $barr);
	}
	
	public function fileeditgcall($cnum, $filenum, Request $request)
	{
		$barr = array('error'=>'0');
		addlogs('验证', 'fileeditee');
		return "{\"error\":0}";
	}
	
	/**
	*	在线编辑后保存回调
	*/
	public function fileeditcall($cnum, $filenum, Request $request)
	{
		$barr = array('error'=>0);
		$this->getCompanyId($request, $cnum);
		if($this->companyid==0)return $this->returntishi('无效访问');
		$saveurl = $request->get('saveurl');
		$jm  = c('rockjm');
		$frs = FiledaModel::where('filenum', $jm->base64decode($filenum))->first();
		$body_stream = file_get_contents("php://input");
		
		if(isempt($saveurl)){
			addlogs($body_stream, 'fileedit');
			$data 	= json_decode($body_stream, true);
			$status = $data['status'];
		}else{
			$filesize = $this->saveeditback($frs, '', $body_stream);//返回文件大小
			if(!$filesize)return '保存失败了';
			$frs->filesize = $filesize;
			$sptph = $this->createtempurl($frs);
			return 'ok,'.$sptph.'';
		}
		
		//打开时
		if($status==1){
			
		}
		
		//保存时回调
		//http://192.168.130.130:9000/cache/files/dd069b30c9e0894b9a4c_1924/output.xlsx/output.xlsx?md5=dM7tObJpqnxn4Rm63yo5jA==&expires=1558611227&disposition=attachment&ooname=output.xlsx
		if($status==2){
			$this->saveeditback($frs, $data['url']);
		}
		
		return "{\"error\":0}";
	}
	
	private function saveeditback($frs, $url, $data='')
	{
		$upobj	 = c('upfile', $this->useainfo);
		$newpath = ''.config('rock.updir').'/'.date('Y-m').'';
		if(!is_dir($newpath))mkdir($newpath);
		$file_ext= '';
		$newext  = $frs->fileext; //新扩展名，都会呗转为x的格式
		
		$randname= ''.date('d_His').''.rand(10,99).'';
		$filepath= ''.$newpath.'/'.$randname.'.'.$newext.'';
		if($data==''){
			if($newext=='xls')$newext = 'xlsx';
			if($newext=='doc')$newext = 'docx';
			if($newext=='ppt')$newext = 'pptx';
			$cont 	 = @file_get_contents($url);
		}else{
			$cont 	 = base64_decode($data);
		}

		if(!isempt($cont)){
			$bo 	 = c('base')->createtxt($filepath, $cont);
			if(!$bo){
				c('log', $this->useainfo)->adderror('在线编辑','在线编辑文件“'.$frs->filename.'”无法保存到服务器');
				return false;
			}
		}else{
			c('log', $this->useainfo)->adderror('在线编辑','在线编辑文件“'.$frs->filename.'”无法获取文件');
			return false;
		}

		//备份旧的
		$oldfilename = $frs->filename;
		$oldext		 = $upobj->getext($oldfilename, 1);
		$dtss		 = date('YmdHis', strtotime($frs->adddt));
		$oldfilename = str_replace('.'.$oldext.'', '(备份'.$dtss.').'.$oldext.'', $oldfilename);
		$upbarr = $upobj->uploadback(array(
			'filesize' 	 => $frs->filesize,
			'filesizecn' => $frs->filesizecn,
			'fileext' 	 => $frs->fileext,
			'filetype' 	 => $frs->filetype,
			'allfilename' => $frs->filepath,
			'oldfilename' => $oldfilename,
		),'', array(
			'pdfpath' => $frs->pdfpath,
			'oid'	  => $frs->id,
			'cid'	  => $this->companyid,
			'uid'	  => $this->userid,
			'aid'	  => $this->useaid,
			'adddt'	  => $frs->adddt 
		));
		
		//更新为最新
		$filesize = filesize($filepath);
		$filename = $frs->filename;
		if($newext != $frs->fileext){
			$filename = str_replace('.'.$oldext.'', '.'.$newext.'', $filename);
		}
		$nuarr = array(
			'fileext'    => $newext,
			'filepath'   => $filepath,
			'filename'   => $filename,
			'filesize' 	 => $filesize,
			'filesizecn' => $upobj->formatsize($filesize),
			'optdt' 	 => $this->now,
		); 
		\DB::table('fileda')->where('id', $frs->id)->update($nuarr);
		return $filesize;
	}
	
	/**
	*	下载
	*/
	public function filedown($cnum, $filenum, Request $request)
	{
		$this->getCompanyId($request, $cnum);
		if($this->companyid==0)return $this->returntishi('无效访问');
		
		$jm  = c('rockjm');
		$frs = FiledaModel::where('filenum', $jm->base64decode($filenum))->first();
		if(!$frs)return $this->returntishi('文件记录不存在');
		$filename = $request->input('filename');
		if(!isempt($filename)){
			$filename = $jm->base64decode($filename);
		}else{
			$filename = $frs->filename;
		}
		$filename = $frs->filename;
		$fileext  = $frs->fileext;
		$filepath = $frs->filepath;
		if(!$frs->fileexists)return $this->returntishi('文件不存在');

		$frs->downci = $frs->downci+1;
		$frs->save();
		
		$filename 	= str_replace(' ','',$filename);
		if($frs->ishttpout){
			$this->fileheader($filename, $fileext);
			return redirect($filepath);
		}else if(substr($filepath,-6)=='uptemp'){
			$content = base64_decode(file_get_contents($filepath));
			$this->fileheader($filename, $fileext);
			return $content;
		}else{
			return response()->download($filepath, $filename);
		}
	}
	
	
	private function fileheader($filename,$ext='xls')
	{
		$mime 		= MimeType::from($filename);
		header('Content-type:'.$mime.'');
		header('Accept-Ranges: bytes');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		header('Expires: 0');
		header('Content-disposition:attachment;filename='.$filename.'');
		header('Content-Transfer-Encoding: binary');
	}
}
