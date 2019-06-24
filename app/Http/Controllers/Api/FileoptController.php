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
use DB;
use Cache;
use App\Model\Base\BaseModel;
use App\Model\Base\UseraModel;


class FileoptController extends ApiauthController
{
	
	
	public function __construct()
    {
		$this->now= nowdt();
		$this->jm = c('rockjm');
		$this->companyinfo			= new \StdClass();
		$this->useainfo				= new \StdClass();
    }

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
	
	//外部来的验证
	public function changekey($request)
	{
		$key  = $request->get('key');
		if(isempt($key))return '无效请求';
		$barr = Cache::get($key);
		if(!$barr || !is_array($barr))return '地址已失效了';
		
		$useragent	= md5($request->userAgent());
		if(arrvalue($barr, 'useragent') != $useragent)return '你没有权限访问这个地址';//防止乱分享地址

		$this->companyinfo->logo 		= arrvalue($barr,'logo', '/images/logo.png');
		$this->companyinfo->name 		= config('app.name');
		$this->useainfo->deptallname 	= '';
		$this->useainfo->email = '';
		$this->useainfo->user  = arrvalue($barr,'user');
		$this->useainfo->name  = $this->jm->base64decode($barr['name']);
		
		$barr['key']	= $key;
		return $barr;
	}
	
	//内部打开验证信息
	private function changeckey($ckey)
	{
		if(isempt($ckey))return '无效请求';
		if(!$this->jm->isjm($ckey))return '无效请求1';
		$cknum	= $this->jm->uncrypt($ckey);
		$aid	= 0;
		if(contain($cknum,'_')){
			$stro	= strrpos($cknum, '_');
			$cnum	= substr($cknum, 0, $stro);
			$aid	= substr($cknum, $stro+1);
		}else{
			$cnum	= $cknum;
		}
		
		$this->companyinfo 	= BaseModel::getCompany($cnum);
		$this->companyid	= $this->companyinfo->id;
		if($aid>0){
			$this->useainfo		= UseraModel::where(array('cid'=>$this->companyid,'id'=>$aid))->first();
			if($this->useainfo){
				$this->useaid	= $aid;
				$this->userid	= $this->useainfo->uid;
				$this->userinfo	= $this->useainfo->platusers;
			}
		}
	}
	
	/**
	*	预览打开
	*/
	public function fileview($ckey, $filenum, Request $request)
	{
		$msga 	= $this->changeckey($ckey);
		if($msga && is_string($msga))return $this->returntishi($msga);
		return $this->fileviewshow($filenum, $request);
	}

	public function afileview($filenum, Request $request)
	{
		$msga 	= $this->changekey($request);
		if($msga && is_string($msga))return $this->returntishi($msga);
		return $this->fileviewshow($filenum, $request);
	}
	
	private function fileviewshow($filenum)
	{
		$frs = FiledaModel::where('filenum', $this->jm->base64decode($filenum))->first();
		if(!$frs)return $this->returntishi('文件记录不存在');
		$filename = $frs->filename;
		$fileext  = $frs->fileext;
		$filepath = $frs->filepath;
		$ismobile = c('base')->ismobile();
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
			$barr['filepath'] = $this->jm->base64encode('/'.$filepath);
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
				
				//手机端
				if($ismobile && $viewqd=='rockoffice'){
					if(env('ROCK_ONLYOFFICE')){
						$viewqd='onlyoffice';
					}else{
						$viewqd='microsoft';
					}
				}
				
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
					$barr['useainfo']  	  	 = $this->useainfo;
					$barr['documentType']  	 = $this->getdocumentType($fileext);
					$barr['viewtype'] = $ismobile ? 'mobile' : 'desktop';
				}
				
				//用官网的
				if($viewqd=='rockdoc'){
					
				}
				
				if($tplv=='')return $this->returntishi('没有开发此预览驱动'.$viewqd.'');
				
				$barr['url'] 	= $url;
				$barr['fileext'] = $fileext;
			}else{
				$tplv = 'pdf';
				$barr['filepath'] = $this->jm->base64encode('/'.$pdfpath);
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
	
	/**
	*	下载
	*/
	public function filedown($ckey, $filenum, Request $request)
	{
		$msga 	= $this->changeckey($ckey);
		if($msga && is_string($msga))return $this->returntishi($msga);
		return $this->filedownshow($filenum);
	}
	public function afiledown($filenum, Request $request)
	{
		$cfrom	= nulltoempty($request->get('cfrom'));
		$msga 	= $this->changekey($request);
		if($msga && is_string($msga) && $cfrom!='app')return $this->returntishi($msga);
		return $this->filedownshow($filenum);
	}
	private function filedownshow($filenum)
	{
		$frs = FiledaModel::where('filenum', $this->jm->base64decode($filenum))->first();
		if(!$frs)return $this->returntishi('文件记录不存在');
		$filename = $frs->filename;
		$fileext  = $frs->fileext;
		$filepath = $frs->filepath;
		if(!$frs->fileexists)return $this->returntishi('文件不存在');
	
		$frs->downci = $frs->downci+1;
		$frs->save();
		$filename 	= str_replace(' ','',$filename);
		
		$this->fileheader($filename, $fileext, $frs->filesize);
		if($frs->ishttpout){
			return redirect($filepath);
		}else if(substr($filepath,-6)=='uptemp'){
			$content = base64_decode(file_get_contents($filepath));
			return $content;
		}else{
			ob_clean();
			flush();
			readfile($filepath);
		}
	}
	private function fileheader($filename,$ext='xls', $size=0)
	{
		$mime 		= MimeType::from($filename);
		header('Content-type:'.$mime.'');
		header('Accept-Ranges: bytes');
		if($size>0){
			Header('Accept-Length:'.$size.'');
			Header('Content-Length:'.$size.'');
		}
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		header('Expires: 0');
		header('Content-disposition:attachment;filename='.$filename.'');
		header('Content-Transfer-Encoding: binary');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	*	编辑
	*/
	public function afileedit($filenum, Request $request)
	{
		$msga 	= $this->changekey($request);
		if($msga && is_string($msga))return $this->returntishi($msga);
		$curl	= Rock::replaceurl('afileeditcall/'.$filenum.'?callurl='.$msga['callurl'].'',1);
		return $this->fileeditshow($filenum, $curl, $request);
	}
	public function fileedit($ckey, $filenum, Request $request)
	{
		$msga 	= $this->changeckey($ckey);
		if($msga && is_string($msga))return $this->returntishi($msga);
		$curl	= Rock::replaceurl('fileeditcall/'.$ckey.'/'.$filenum.'',1);
		
		return $this->fileeditshow($filenum, $curl, $request);
	}
	private function fileeditshow($filenum, $callbackUrl, $request)
	{
		$frs = FiledaModel::where('filenum', $this->jm->base64decode($filenum))->first();
		if(!$frs)return $this->returntishi('文件记录不存在');
		
		$filename = $frs->filename;
		$fileext  = $frs->fileext;
		$filepath = $frs->filepath;
		$ismobile = c('base')->ismobile();
		if(!$frs->fileexists)return $this->returntishi('文件不存在');
		
		$callb	    			= $request->get('callb'); //回调
		$barr['filename'] 		= $filename;
		$barr['companyinfo']  	= $this->companyinfo;
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
			
			//手机端
			if($ismobile && $viewqd=='rockoffice'){
				if(env('ROCK_ONLYOFFICE')){
					$viewqd='onlyoffice';
				}
			}
			
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
				$barr['viewtype'] 	 = $ismobile ? 'mobile' : 'desktop';
				

				if(!isempt($callb))$callbackUrl.='?callb='.$callb.'';
				$barr['callbackUrl'] = $callbackUrl;//在线编辑回调保存地址
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
	
	
	public function afileeditcall($filenum, Request $request)
	{
		$body_stream = file_get_contents("php://input");
		addlogs($body_stream, 'afileedit_new');
		$data 	= json_decode($body_stream, true);
		$status = $data['status'];
		$frs 	= FiledaModel::where('filenum', $this->jm->base64decode($filenum))->first();
		
		$newext = $frs->fileext;
		if($newext=='xls')$newext = 'xlsx';
		if($newext=='doc')$newext = 'docx';
		if($newext=='ppt')$newext = 'pptx';
		
		//推送到对应网址上
		$callurl= $request->get('callurl');
		if(!isempt($callurl)){
			$callurl = $this->jm->base64decode($callurl);
			$callurl.= '&fileext='.$newext.'';
			$cont 	 = @file_get_contents($data['url']);
			if($cont){
				$cont= base64_encode($cont);
				$result = Rock::curlpost($callurl, $cont);
				addlogs($result, 'afileedit_new');
			}
			$frs->mid = 0;
			$frs->save();
		}
		
			
		return "{\"error\":0}";
	}
	
	public function fileeditcall($ckey, $filenum, Request $request)
	{
		$msga 	= $this->changeckey($ckey);
		if($msga && is_string($msga))return $msga;
		
		
		$saveurl = $request->get('saveurl');
		$callb 	 = $request->get('callb');
		$frs 	 = FiledaModel::where('filenum', $this->jm->base64decode($filenum))->first();
		$body_stream = file_get_contents("php://input");
		
		if(isempt($saveurl)){
			addlogs($body_stream, 'fileedit_new');
			$data 	= json_decode($body_stream, true);
			$status = $data['status'];
		}else{
			
			$filesize = $this->saveeditback($frs, '', $body_stream, $callb);//返回文件大小
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
			$this->saveeditback($frs, $data['url'],'', $callb);
		}
		
		return "{\"error\":0}";
	}
	
	private function createtempurl($frs)
	{
		$str = 'rockdoc_'.md5(config('app.url')).'_'.$frs->filesize.'_'.$frs->filenum.'.'.$frs->fileext.'';
		return $str;
	}
	
	
	private function saveeditback($frs, $url, $data='',$callb='')
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
			'optname' => $this->useainfo->name,
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
			'optdt' 	 => nowdt(),
		); 
		DB::table('fileda')->where('id', $frs->id)->update($nuarr);
		
		if(!isempt($callb) && method_exists($this, $callb))$this->$callb($frs->filenum, $frs); //回调处理
		
		return $filesize;
	}
	
	
	/**
	*	获取在线编辑信息，本地插件
	*/
	public function filesend($ckey, $filenum, Request $request)
	{
		$msga 	= $this->changeckey($ckey);
		if($msga && is_string($msga))return returnerror($msga);
		
		$frs = FiledaModel::where('filenum', $this->jm->base64decode($filenum))->first();
		if(!$frs)return returnerror('文件记录不存在');
		$filename = $frs->filename;
		$fileext  = $frs->fileext;
		$filepath = $frs->filepath;
		if(!$frs->fileexists)return returnerror('文件不存在');
		$lx		    = (int)$request->get('lx');
		$callb	    = $request->get('callb'); //回调
		
		$utes		= 'edit';
		if($lx==1){
			$filename = '(只读)'.$filename.'';
			$utes     = 'yulan';
		}
		
		
		$ckey	 = $this->jm->encrypt(''.$this->companyinfo->num.'_'.$this->useaid.'');
		$saveurl = config('app.url').'/fileeditcall/'.$ckey.'/'.$filenum.'';
		$saveurl.= '?saveurl=true';
		if(!isempt($callb))$saveurl.='&callb='.$callb.'';
		
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
	
	
	
	//---------------在线编辑的回调处理------------
	
	//文件中心
	private function calleditword($filenum)
	{
		DB::table('word')->where('filenum', $filenum)->update(array(
			'editname' 	=> $this->useainfo->name,
			'editnaid' 	=> $this->useaid,
			'optdt' 	=> nowdt(),
		));
	}
	
	//文档协作
	private function calleditdocxie($filenum)
	{
		DB::table('docxie')->where('filenum', $filenum)->update(array(
			'editname' 	=> $this->useainfo->name,
			'editnaid' 	=> $this->useaid,
			'optdt' 	=> nowdt(),
		));
	}
}
