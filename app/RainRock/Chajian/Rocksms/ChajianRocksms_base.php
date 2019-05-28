<?php
/**
*	短信-调用信呼官网的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rocksms;

use Cache;

class ChajianRocksms_base extends ChajianRocksms
{
	
	
	public function send($tomobile,$tplnum, $params=array(),$provider='',$addlog=true)
	{
		if(isempt($tomobile))return returnerror('接收人手机号不能为空');
		if($provider=='')$provider = config('rocksms.provider');
		$conf 	  = config('rocksms.'.$provider.'');
		if(!isset($conf['sign']))return returnerror('未配置此短信');
		$qiannum  = $conf['sign'];
		if(isempt($qiannum))return returnerror('未配置签名');
		if($tplnum=='yzm'){
			if(!isset($conf['codetpl']))return returnerror('未配置验证码短信模版');
			$tplnum = $conf['codetpl'];
		}
		$obj 	  = c('Rocksms:'.$provider.'');
		if(!method_exists($obj, 'send'))return returnerror('没有开发'.$provider.'的短信接口');
		
		//return returnsuccess('ok');
		
		$barr 	  = $obj->send($tomobile,$qiannum, $tplnum, $params, $conf);
		if(!$barr['success'] && $addlog)c('log')->adderror('短信', $barr['msg'],2);//记录日志
		
		return $barr;
	}
	
	
	/**
	*	获取验证码(1分钟内只能获取一次)，有效期5分钟
	*	$tomobile 接收手机号
	*	$qiannum 签名编号
	*	$tplnum 模版编号
	*	方法:$barr = c('Rocksms:base')->getcode($mobile,$gtype, $device);
	*/
	public function getcode($mobile,$gtype, $device)
	{
		$time   = 60; //这时间内只能一次
		
		$key 	= Cache::get('mo'.$mobile.'');
		if(!isempt($key)){
			$mshu = time()-$key;
			$jg   = $time-$mshu;
			if($jg>0)return returnerror('获取太频繁了，请'.$jg.'秒后在试');
		}
		
		$key 	= Cache::get('mo'.$device.'');
		if(!isempt($key)){
			$mshu = time()-$key;
			$jg   = $time-$mshu;
			if($jg>0)return returnerror('获取太频繁了，请'.$jg.'秒后在试');
		}
		
		$code 	= rand(100000,999999);
		$params['code'] = $code;
		
		
		
		$barr =  $this->send($mobile,'yzm', $params);
		if($barr['success']){
			Cache::put('mo'.$mobile.'', time(), $time);
			Cache::put('mo'.$device.'', time(), $time);
			
			//记录起来
			$key = md5($mobile.$gtype.$device);
			Cache::put($key, md5('abc'.$code.''), 5*60);//5分钟内容
		}
		
		return $barr;
	}
	
	/**
	*	验证验证码是否正确,最多只能验证5次
	*	bool c('Rocksms:base')->checkcode($mobile, $code, $gtype, $device);
	*/
	public function checkcode($mobile, $code, $gtype, $device)
	{
		$key = md5($mobile.$gtype.$device);
		$val = Cache::get($key);
		if($val!=md5('abc'.$code.'')){
			return false;
		}else{
			return true;
		}
	}
	
}