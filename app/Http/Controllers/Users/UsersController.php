<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;


class UsersController extends Controller
{
	
    /**
     * Create a new controller instance.
     * 后台认证验证
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:users');
		parent::__construct();
    }
}
