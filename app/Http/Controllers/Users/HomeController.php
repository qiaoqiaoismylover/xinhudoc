<?php
/**
*	用户单位下主页
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

namespace App\Http\Controllers\Users;

use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use App\Model\Base\CompanyModel;
use App\Model\Base\AgentmenuModel;
use Auth;
use App\Model\Base\BaseModel;
use Rock;
use Illuminate\Http\Request;

class HomeController extends UsersController
{

    /**
     * 主页显示，$cnum对应单位编号
     */
    public function index($cnum='')
    {
		//获取默认的单位
		$auth 		 = Auth::user();
		$joincompany = $auth->joincompany()->get();
		$nowcompany	 = $allcompany	= $agenharr = array();
		if($joincompany){
			foreach($joincompany as $k=>$rs){
				//需要激活状态
				if($rs->status==1){
					$allcompany[] 	= $rs->company;
					if(!$nowcompany)$nowcompany = $rs->company;
					if(!isempt($cnum)){
						if($cnum==$rs->company->num)$nowcompany = $rs->company;
					}else{
						if($auth->devcid==$rs->cid)$nowcompany = $rs->company;
					}
				}
			}
			//显示对应应用
			if($nowcompany){
				$useainfo	= UseraModel::where('cid', $nowcompany->id)->where('uid', $auth->id)->first();
			}
		}
		if(!$nowcompany)return redirect(route('usersmanage')); //没有加入
	
		$barr = [
			'joincompany' 	=> $allcompany, //加入单位企业
			'companyinfo' 	=> $nowcompany,
			'useainfo' 		=> $useainfo,
		];
		
		//用户类型0,1,2
		$type 	= $useainfo->type;
		if($nowcompany->uid==$auth->id)$type = 2; //创建人
		$barr['useatype']	= $type;
		$barr['style']		= $this->getBootstyle();

        return view('users/index',$barr);
    }
	
	/**
	*	加载选择卡上文件
	*/
	public function showViews(Request $request)
	{
		$surl = $request->get('surl');
		$surl = c('rockjm')->base64decode($surl);
		
		return view('users/index/'.$surl.'');
	}
}
