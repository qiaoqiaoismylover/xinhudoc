<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests,  ValidatesRequests;
	
	
	public $now;	//当前时间
	public $limit = 15;//默认分页数
	private $error_message; //错误信息
	
	public function __construct()
    {
        $this->now = date('Y-m-d H:i:s');
    }
	
	
	/**
	*	返回错误信息
	*/
	public function returnerror($msg, $code=422)
	{
		$this->error_message = $msg;
		return response($msg, $code);
	}
	
	public function geterrormessage()
	{
		return $this->error_message;
	}
	
	public function returnerrors($request, $fid, $msg)
	{
		$barr[$fid] = [$msg];
		return $this->buildFailedValidationResponse($request,$barr);
	}
	
	public function returntishi($msg, $code=402)
	{
		return abort($code, $msg);
	}
	
	
	/**
	*	获取分页数组
	*	route 地址路由
	*	total 总数量
	*/
	public function getPager($route, $total=0, $urlparams=array(), $ots=array())
	{
		$page 	= (int)\Request::input('page','1');
		$limit	= (int)\Request::input('limit','0');
		
		$limits	= $limit> 0 ? $limit	: $this->limit;
		
		$maxpage = ceil($total/$limits);

		$url 	 = route($route, $ots).'?page=%d';
		if(\Request::has('limit'))$url.='&limit='.$limit.'';
		foreach($urlparams as $k=>$v)if(!isempt($v))$url.='&'.$k.'='.$v.'';
		$pager 	 = [
			'total'		=> $total,
			'lastpage'  => $page-1,
			'nextpage'  => $page+1,
			'page'  	=> $page,
			'limit'  	=> $limits,
			'maxpage' 	=> $maxpage,
			'url'		=> $url
		];
		
		return $pager;
	}
	
	/**
	*	每页数量
	*/
	public function getLimit()
	{
		$this->limit	= (int)\Request::input('limit', $this->limit);
		return $this->limit;
	}
	
	/**
	*	根据数字获取样式
	*/
	public function getBootstyle($val=false, $lx=0)
	{
		if($val===false)$val = \Auth::user()->bootstyle;
		if(isempt($val))$val = 0;
		$val = floatval($val);
		if($val==0 && $lx==0)$val = config('rock.usersstyle');
		if($val==0 && $lx==1)$val = config('rock.adminstyle');
		//$val  = rand(1,34);
		$stylearr	= c('bootstyle')->getStylearr(1);
		$zys  		= count($stylearr)-1;
		$style  	= $val;
		$styys= 'inverse';
		if($style>$zys){
			$styys= 'default';
			$style= $style-$zys;
		}
		$stylecs1	= '/res/bootstrap3.3/css/bootstrap_'.$stylearr[$style].'.css';
		
		return array(
			'path' => $stylecs1,
			'nav'  => $styys
		);
	}
}
