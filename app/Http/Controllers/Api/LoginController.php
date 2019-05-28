<?php
/**
*	注册登录等
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Model\Base\UsersModel;
use Illuminate\Support\Facades\Auth;
use App\Model\Base\TokenModel;


class LoginController extends ApiController
{
	
	public function regCheck(Request $request)
	{
		$mobile 	= $request->mobile;
		$device 	= $request->input('device');
		
		// 验证输入。
        $this->validate($request, [
            'mobile' 	=> 'required|numeric|unique:users',
			//'captcha' 	=> 'required|captcha',
            'mobileyzm' => 'required|mobileyzm:'.$mobile.',reg,'.$device.'',
            'name' 		=> 'required',
            'pass' 		=> 'required|between:6,30',
        ],[
			'mobile.unique' => trans('users/reg.mobilecz'),
			'name.required' => trans('users/reg.namerequired'),
		]);
		
		
		$obj = new UsersModel();
		$obj->mobilecode= nulltoempty($request->mobilecode);
		$obj->mobile 	= $mobile;
		$obj->name 		= $request->name;
		$obj->nickname 	= $request->name;
		$obj->password 	= $request->pass;
		$obj->flaskm 	= 0;
		
		$obj->save();
		c('log')->adds('用户注册','['.$mobile.']注册', $obj->name, $obj->id);
	}
	
	/**
	*	api登录验证
	*/
	public function loginCheck(Request $request)
	{
		$this->validate($request, [
            'user' 	=> 'required',
            'pass' 	=> 'required'
        ]);
		
		$user 	= htmlspecialchars($request->input('user'));
        $pass 	= $request->input('pass');
        $device = $request->input('device');
		$cfrom	= $request->input('cfrom','pc');
		$loglx 	= 'userid';
		$check 	= c('check');
		if($check->iscnmobile($user))$loglx 	= 'mobile';
		if($check->isemail($user))$loglx 		= 'email';
		
		$auth 	=  Auth::guard('users');
		$bo 	=  $auth->attempt([$loglx => $user, 'password' => $pass,'status' => 1], false);
		if(!$bo){
			c('log')->adderror(''.$cfrom.'登录','['.$user.']登录失败', $user);
			return $this->returnerrors($request,'user',trans('users/login.errorinfo'));
		}
		
		$agent	= md5(strtolower($request->userAgent()));
		
		$uobj 	= $auth->user();
		$toobj	= new TokenModel();
		$token	= $toobj->createToken($uobj->id,  $cfrom, $agent);
		
		
		session(['usertoken'=>$token]); //存session
		
		if(function_exists('setcookie'))setcookie(''.config('cache.prefix').'usertoken', 
					c('rockjm')->encrypt($token), 
					time()+24*3600*365, '/');//存cookie
		
		
		c('log')->adds(''.$cfrom.'登录','['.$user.']登录成功', $uobj->name, $uobj->id);
		return [
			'token' 	=> $token,
			'face' 		=> $uobj->face,
			'bootstyle' => $uobj->bootstyle,
			'title' 	=> config('app.name')
		];
	}
	
	/**
	*	找回密码
	*/
	public function findCheck(Request $request)
	{
		$mobile 	= $request->mobile;
		$mobilecode	= nulltoempty($request->mobilecode);
		$device 	= $request->input('device');
		
		// 验证输入。
        $this->validate($request, [
            'mobile' 	=> 'required|numeric',
            'mobileyzm' => 'required|mobileyzm:'.$mobile.',find,'.$device.'',
            'pass' 		=> 'required|between:6,30',
			//'captcha' 	=> 'required|captcha',
        ]);
		
		$ors 	= UsersModel::where('mobile', $mobile)->where('mobilecode', $mobilecode)->first();
		if(!$ors)return $this->returnerrors($request,'mobile',trans('users/reg.regnot'));
		
		$ors->password 	= $request->pass;
		$ors->save();
		
		c('log')->adds('找回密码','['.$mobile.']找回密码', $ors->name, $ors->id);
	}
}