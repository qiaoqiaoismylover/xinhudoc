<?php
/**
*	对外openapi中间件
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Middleware;
use Closure;


class OpenApiResponse
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
		$getkey = $request->get('openkey');
		$openkey= config('rock.openkey');
		if($openkey){
			if(md5($openkey) != $getkey)return response('openkey not access', 401);
		}
		
		//是否可跨站里
		/*
		$origin 	= $request->header('origin', $request->header('referer'));
		$Host		= c('base')->gethost();
		if(!contain($origin, $Host)){
			$orlist 	= env('ALLOW_ORIGIN');
			$bo = false;
			if(!isempt($orlist)){
				if($orlist=='*'){
					$bo = true;
				}else{
					$orlisa 	= explode(',', $orlist);
					foreach($orlisa as $or1)if(contain($origin, $or1))$bo = true;
				}
			}
			if(!$bo)return response(''.$origin.' not access', 401);
		}*/

		return $next($request);
	}
	
}
