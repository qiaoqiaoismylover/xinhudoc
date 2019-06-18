<?php
/**
*	信呼OA上传文件
*	主页：http://www.rockoa.com/
*	软件：信呼文件管理平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\OpenApi;

use Illuminate\Http\Request;
use App\Model\Base\FiledaModel;
use Cache;
use Rock;

class UpfileController extends OpenApiController
{
	
	/**
	*	别的系统同服务器上传文件，如信呼OA跟信呼文件平台是同服务器下就用这
	*/
	public function postpath(Request $request)
	{
		$rootpath 		= $request->input('rootpath');
		$filepath 		= $request->input('filepath');
		$thumbpath 		= nulltoempty($request->input('thumbpath'));
		$pdfpath 		= nulltoempty($request->input('pdfpath'));
		
		$upobj			= c('upfile');
		$updir 			= $upobj->getupdir($request->input('updir'),'/');

		$frompath 		= $rootpath.'/'.$filepath;
		if(!file_exists($frompath))return returnerror('frompath not exists');
		
		//缩略图的
		if(!isempt($thumbpath)){
			$fpath = $rootpath.'/'.$thumbpath;
			if(!file_exists($fpath)){
				$thumbpath = '';
			}else{
				$thumbpath = ''.$updir.'/'.$this->updircl($thumbpath).'';
				c('base')->createdir($thumbpath, 1, 1);
				@$bo	 = copy($fpath,$thumbpath);
				if(!$bo)$thumbpath = '';
			}
		}
		
		//上传的pdf文件
		if(!isempt($pdfpath)){
			$fpath = $rootpath.'/'.$pdfpath;
			if(!file_exists($fpath)){
				$pdfpath = '';
			}else{
				$pdfpath = ''.$updir.'/'.$this->updircl($pdfpath).'';
				c('base')->createdir($pdfpath, 1, 1);
				@$bo	 = copy($fpath,$pdfpath);
				if(!$bo)$pdfpath = '';
			}
		}
		
		
		$toppath		= ''.$updir.'/'.$this->updircl($filepath).'';
		c('base')->createdir($toppath, 1, 1); //创建目录
		@$bo	 = copy($frompath,$toppath);
		if(!$bo)return returnerror('copy '.$frompath.' error');
		
		
		$table		= nulltoempty($request->input('table'));
		$mid 		= (int)$request->input('mid');
		$filenum	= '';
		if(!isempt($table) && $mid>0){
			$foldrs = FiledaModel::where(array('table'=>$table,'mid'=>$mid))->first();
			if($foldrs)$filenum = $foldrs->filenum;
		}
		
		$barr 		= $upobj->createFileda(array(
			'filename' 	=> $request->input('filename'),
			'fileext' 	=> $request->input('fileext'),
			'filesize' 	=> $request->input('filesize'),
			'optname' 	=> nulltoempty($request->input('optname')),
			'adddt' 	=> $request->input('adddt'),
			'filenum' 	=> $filenum,
			'table' 	=> $table,
			'mid' 		=> $mid,
			'thumbpath' => $thumbpath,
			'pdfpath' 	=> $pdfpath,
			'filepath' 	=> $toppath,
			'outuid' 	=> (int)$request->input('optid')
		));
		
		return returnsuccess($barr);
	}
	
	private function updircl($path)
	{
		return str_replace('upload/','', $path);
	}
	
	
	//发送文件流上传的
	public function postfile(Request $request)
	{
		$upobj		= c('upfile');
		$sendtype	= $request->input('sendtype');
		
		$updir 		= $upobj->getupdir($request->input('updir'),'/');
		$paramsstr	= c('rockjm')->base64decode($request->input('paramsstr'));
		$params		= json_decode($paramsstr, true);
		
		$data		= isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents('php://input');
		
		if(!$data)return returnerror('post data is empty');
		
		$filepath	= $params['filepath'];
		$toppath	= ''.$updir.'/'.$this->updircl($filepath).'';
		$bo 		= c('base')->createtxt($toppath, base64_decode($data));
		if(!$bo)return returnerror('upfile '.$filepath.' error');
		
		$table		= nulltoempty($params['table']);
		$mid 		= (int)$params['mid'];
		$filenum	= '';
		
		if(!isempt($table) && $mid>0){
			$foldrs = FiledaModel::where(array('table'=>$table,'mid'=>$mid))->first();
			if($foldrs){
				$filenum = $foldrs->filenum;
				if($sendtype!='file'){
					if($sendtype=='thumb')$foldrs->thumbpath = $toppath;//缩略图
					if($sendtype=='pdf')$foldrs->pdfpath 	 = $toppath;//生成pdf文件
					$foldrs->save();
					return returnsuccess($foldrs);
				}
			}else{
				if($sendtype!='file')return returnerror('not found '.$sendtype.''); //不是发送主文件
			}
		}
	
		$barr 		= $upobj->createFileda(array(
			'filename' 	=> $params['filename'],
			'fileext' 	=> $params['fileext'],
			'filesize' 	=> $params['filesize'],
			'optname' 	=> nulltoempty($params['optname']),
			'adddt' 	=> $params['adddt'],
			'filenum' 	=> $filenum,
			'table' 	=> $table,
			'mid' 		=> $mid,
			'filepath' 	=> $toppath,
			'outuid' 	=> (int)arrvalue($params, 'optid','0')
		));
		
		return returnsuccess($barr);
	}
	
	
	/**
	*	获取文件信息,$type 0预览,1下载,2编辑
	*/
	public function getinfo(Request $request)
	{
		$filenum = $request->get('filenum');
		$type 	 = (int)$request->get('type');
		$ismobile= (int)$request->get('ismobile');
		$uid 	 = (int)$request->get('uid');
		$frs  = FiledaModel::where('filenum',$filenum)->first();
		if(!$frs)return returnerror('文件记录不存在');
		$fileext  = $frs->fileext;
		$filename = $frs->filename;
		$filepath = $frs->filepath;
		
		if(!$frs->fileexists)return returnerror('文件不存在');
		$jm 		= c('rockjm');
		$upobj 		= c('upfile');
		
		$qudong		= config('rock.fileopt.view'); //预览驱动
		if($type==2)$qudong	= config('rock.fileopt.edit'); //编辑驱动
		
		$offic		= ',doc,docx,xls,xlsx,ppt,pptx,';
		$key		= $jm->strlook(''.$frs->id.''.$type.''.$filenum.'_'.$uid.'');
		$useragent	= $request->userAgent();
		$url		= '';
		
		$barr['fileext'] = $frs->fileext;
		$filenums	= $jm->base64encode($filenum);
		
		Cache::put($key, array(
			'useragent' => $useragent,
			'filenum' 	=> $filenum,
			'uid' 		=> $uid,
			'ismobile' 	=> $ismobile,
			'name' 		=> $request->get('name'),
			'user' 		=> $request->get('user'),
			'callurl' 	=> $request->get('callurl'),
			'logo' 		=> $jm->base64decode($request->get('logo')),
		), 5); //5分钟
		
		//预览
		if($type==0){
			$url = route('afileview', $filenums);
		}
		
		//下载
		if($type==1){
			$url = route('afiledown', $filenums);
			if($frs->ishttpout)$url = $filepath;//远程地址的
		}
		
		//编辑的
		if($type==2){
			$url = route('afileedit', $filenums);
		}
		
		$barr['url'] = $url.'?key='.$key.'';
		
		//本地插件返回调用本地插件的信息
		if($type==0 || $type==2){
			if($ismobile==0 && $qudong=='rockoffice' && contain($offic,','.$fileext.',')){
				$barr['url'] 	 = Rock::replaceurl($filepath); //文档下载地址
				$barr['fileext'] = $qudong;
			}
		}
		
		//手机端预览有是本地插件
		if($type==0 && $ismobile==1 && $qudong=='rockoffice'){
			
		}
		
		//图片时返回对应图片地址
		if($upobj->isimg($fileext) && $type==0){
			$barr['url'] 	 = Rock::replaceurl($filepath); 
		}
		
		return returnsuccess($barr);
	}
	
	
	/**
	*	删除文件
	*/
	public function delfile(Request $request)
	{
		$filenum = $request->get('filenum');
		if(isempt($filenum ))return returnerror('filenum is empty');
		c('upfile')->delTorecycle($filenum);//先删到回收站
		return returnsuccess('delTorecycle.ok');
	}
}
