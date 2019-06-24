<?php
/**
*	插件-计划任务执行的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	cli运行 php artisan rock:taskrun --act=system:minute
*/

namespace App\RainRock\Chajian\Task;

use App\Model\Base\CompanyModel;
use App\Model\Base\UseraModel;

class ChajianTask_system extends ChajianTask
{
	/**
	*	每5分钟运行
	*/
	public function minute()
	{
		return 'success';
	}
	
	
	/**
	*	单位用户更新
	*/
	public function useraup()
	{
		$rows = CompanyModel::get();
		$obj  = c('usera');
		foreach($rows as $k=>$rs){
			$cid = $rs->id;
			$obj->reloaddata($cid);
		}
		return 'success';
	}
}