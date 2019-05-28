<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;


use App\Model\Base\CompanyModel;
use App\Model\Base\DeptModel;
use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use App\Model\Base\LogModel;

class HomeController extends AdminController
{
  

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$companytotal = CompanyModel::count();
		$userstotal = UsersModel::count();
		$useratotal = UseraModel::count();
		$depttotal = DeptModel::count();
		$logtotal 	= LogModel::whereRaw("`optdt` like '".nowdt('dt')."%'")->count();
		$logtotals 	= LogModel::whereRaw("`optdt` like '".nowdt('dt')."%' and `level`=2")->count();
		
        return $this->getShowView('admin/home',[
			'companytotal' 	=> $companytotal,
			'userstotal' 	=> $userstotal,
			'useratotal' 	=> $useratotal,
			'depttotal' 	=> $depttotal,
			'logtotal' 		=> $logtotal,
			'logtotals' 		=> $logtotals,
		]);
    }
}
