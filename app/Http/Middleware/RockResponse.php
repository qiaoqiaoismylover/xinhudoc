<?php
/**
*	系统中间件
*	主页：http://www.rockoa.com/
*	软件：信呼文件管理平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Middleware;
use Closure;
use Rock;

class RockResponse
{
	
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$msg 	= Rock::initCheck();
		if($msg)return response($msg, 401);
		
		//验证黑白名单
		$ip 	= getclientip();
		$bool 	= 0;
		
		//白名单判断
		$whiteip = env('ACCESS_WHITEIP');
		if(!isempt($whiteip)){
			$whiteipa = explode(',', $whiteip);
			foreach($whiteipa as $ips){
				$bo = strpos($ip, $ips);
				if($bo===0 || $ips=='*'){
					$bool = 1; //可以访问
					break;
				}
			}
		}
		
		//黑名单判断
		if($bool==0){
			$blackip = env('ACCESS_BLACKIP');
			if(!isempt($blackip)){
				$blackipa = explode(',', $blackip);
				foreach($blackipa as $ips){
					$bo = strpos($ip, $ips);
					if($bo===0 || $ips=='*'){
						$bool = 2;//不能访问
						break;
					}
				}
			}
		}
		
		if($bool==2){
			$msg = trans('auth.notaccess', ['ip'=>$ip]);
			return abort(402, $msg);
		}
		
		return $next($request);
	}

}