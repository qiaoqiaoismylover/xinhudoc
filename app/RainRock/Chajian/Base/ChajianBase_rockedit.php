<?php 
/**
*	连接官网在线编辑文档
*/

namespace App\RainRock\Chajian\Base;
use App\Model\Base\FiledaModel;
use Rock;

class ChajianBase_rockedit extends ChajianBase{
	

	protected function initChajian()
	{
		$urs = $this->getNei('rockjm')->base64decode('aHR0cDovL29mZmljZS5yb2Nrb2EuY29tLw::');
		$url = config('rock.rockoffice_url');
		if(isempt($url))$url = $urs;
		$this->agentkey = config('rock.rockoffice_key');
		if(substr($url,-1)!='/')$url.='/';
		$this->updatekel = $url;
		$this->updatekey = $url.'api.php';
	}
	
	
	public function geturlstr($mod, $act, $can=array())
	{
		$url 	= $this->updatekey;
		$url.= '?m='.$mod.'&a='.$act.'';
		//$url.= '&host='.$this->rock->jm->base64encode(HOST).'&xinhukey='.getconfig('xinhukey').'';
		$url.='&xinhukey='.config('rock.xinhukey').'';;
		//$url.= '&adminid='.$this->adminid.'';
		$url.= '&agentkey='.$this->agentkey.'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}

	
	/**
	*	get获取数据
	*/
	public function getdata($mod, $act, $can=array())
	{
		$url 	= $this->geturlstr($mod, $act, $can);
		$barr 	= Rock::curlget($url);
		if(!$barr['success'])return $barr;
		$data 	= json_decode($barr['data'], true);
		return $data;
	}
	
	/**
	*	post发送数据
	*/
	public function postdata($mod, $act, $can=array())
	{
		$url 	= $this->geturlstr($mod, $act);
		$barr 	= Rock::curlpost($url, $can);
		if(!$barr['success'])return $barr;
		$data 	= json_decode($barr['data'], true);
		return $data;
	}
	
	public function sendedit($id, $ckey, $otype=0, $callb='')
	{
		$frs 		= FiledaModel::where('id', $id)->first();
		if(!$frs)return returnerror('文件不存在');
		
		$filepath 	= $frs->filepath;
		$onlynum	= $frs->filenum;
		
		$jm 		= c('rockjm');
		
		$barr 		= $this->getdata('file','change', array(
			'filenum' 	=> $onlynum,
			'optid'		=> $this->useaid,
			'optname'	=> $jm->base64encode($this->adminname),
			'face'		=> $jm->base64encode($this->useainfo->face),
		));
		if(!$barr['success'])return $barr;
		$data 		= $barr['data'];
		$type 		= $data['type'];
		$gokey		= $data['gokey'];
		$bsar		= $data;
		if($type=='0'){
			$barr 	= $this->postdata('file','recedata', array(
				'data' 		=> $jm->base64encode(file_get_contents($filepath)),
				'fileid' 	=> $id,
				'filenum' 	=> $onlynum,
				'fileext'	=> $frs->fileext,
				'filename'	=> $frs->filename,
				'optid'		=> $frs->aid,
				'optname'	=> '',
				'filesize'	=> $frs->filesize,
				'filesizecn'=> $frs->filesizecn,
			));
			if(!$barr['success'])return $barr;
			$bsar['type'] = '1';
		}
		if($bsar['type']=='1'){
			$url = $this->updatekey.'?m=file&a=goto&filenum='.$onlynum.'&sign=rockdoc';
			$url.= '&optid='.$this->useaid.'';
			$url.= '&gokey='.$gokey.'';
			$url.= '&otype='.$otype.'';
			if($otype==0){
				$callurl = Rock::replaceurl('fileeditcall/'.$ckey.'/'.$jm->base64encode($onlynum).'',1);
				$callurl.= '?saveurl=yes';
				if(!isempt($callb))$callurl.='&callb='.$callb.'';
				$url.='&callurl='.$jm->base64encode($callurl).'';
			}
			$bsar['url'] = $url;
		}
		return returnsuccess($bsar);
	}
}