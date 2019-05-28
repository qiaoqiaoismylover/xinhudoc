<?php
/**
*	短信-调用信呼官网的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rocksms;


class ChajianRocksms_smsxinhu extends ChajianRocksms
{
	
	public function geturlstr($mod, $act, $can=array())
	{
		if(contain(config('app.url'),'127.0.0.1')){
			$this->updatekeys  = 'aHR0cDovLzEyNy4wLjAuMS9hcHAvcm9ja2FwaS8:';
		}else{
			$this->updatekeys  = 'aHR0cDovL2FwaS5yb2Nrb2EuY29tLw::';
		}
		$url	= c('rockjm')->base64decode($this->updatekeys);
		
		$url.= '?m='.$mod.'&a='.$act.'';
		$url.= '&xinhukey='.config('rock.xinhukey').'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}
	
	/**
	*	发送短信
	*	$tomobile 手机号
	*	$qiannum 短信签名编号
	*	$tplnum 短信模版编号
	*	$params 模版上的参数
	*/
	public function send($tomobile,$qiannum, $tplnum, $params=array())
	{
		
		$para['sys_tomobile'] = $tomobile;
		$para['sys_tplnum']   = $tplnum;
		$para['sys_qiannum']  = $qiannum;
		
		foreach($params as $k=>$v)$para['can_'.$k.''] = $v;
		
		$url 	= $this->geturlstr('sms','send');
		$barr 	= \Rock::curlpost($url, $para);
		
		return $this->recordchu($barr);
	}
	
	
}