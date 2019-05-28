<?php
/**
*	用户登录等控制器
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Base\TokenModel;

class LoginController extends Controller
{
	
	private function getStyle()
	{
		$val = @$_COOKIE['bootstyle']?:config('rock.usersstyle');
		$style = $this->getBootstyle($val);
		return $style['path'];
	}
   
	/**
	*	显示登录页面
	*/
	public function showLoginForm()
    {
		if(Auth::guard('users')->check())return redirect(route('usersindex'));
		
        return view('users.login',[
			'bootstyle' => $this->getStyle()
		]);
    }

	
	/**
	*	用户注册
	*/
	public function showRegForm()
    {
		if(Auth::guard('users')->check())return redirect(route('usersindex'));
		if(!config('app.openreg'))$this->returntishi(trans('users/reg.regopen'));
        return view('users.reg',[
			'bootstyle' => $this->getStyle()
		]);
    }
	
	/**
	*	用户退出
	*/
	public function loginout(Request $request)
    {
		$key	= 'usertoken';
		$token 	= session($key);
		session([$key=>'']);
		
		//清除cookie
		$cokey	= ''.config('cache.prefix').''.$key.'';
		if(function_exists('setcookie'))setcookie($cokey, '', 0, '/');
		
		$obj 	= new TokenModel();
		$obj->removeToken($token);
		Auth::guard('users')->logout();
        return redirect(route('userslogin'));
    }
	
	/**
	*	找回密码
	*/
	public function showFindForm()
	{
		if(Auth::guard('users')->check())return redirect(route('usersindex'));
        return view('users.find',[
			'bootstyle' => $this->getStyle()
		]);
	}
}
