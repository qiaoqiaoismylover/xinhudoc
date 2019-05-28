<?php
/**
*	插件-xinhuapi的请求
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use Rock;

class ChajianBase_xinhuapi extends ChajianBase
{
	private function geturl($act)
	{
		$bboj= c('base');
		$url = config('rock.urly').'/api.php?a='.$act.'';
		$can['cfrom'] 	= 'rockdoc';
		$can['xinhukey']= config('rock.xinhukey');
		$can['version'] = config('version');
		$can['host'] 	= $bboj->gethost(1);
		$can['ip'] 		= $bboj->getclientip();
		$can['web'] 	= $bboj->getbrowser();
		$can['time'] 	= time();
		$can['randkey'] = config('rock.randkey');
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}
	
	
	public function get($act, $can=array())
	{
		$url  = $this->geturl($act);
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
	
		$barr = Rock::curlget($url);
		return $this->recordchu($barr);
	}
}