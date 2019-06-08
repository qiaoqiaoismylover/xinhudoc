<?php
/**
*	应用.文件文档
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2019-07-13
*/

namespace App\Model\Agent;

use Illuminate\Database\Eloquent\Model;
use App\Model\Base\FiledaModel; 

class Agent_docxie  extends Model
{
	protected $table 	= 'docxie';
	public $timestamps 	= false;
	
	/**
	 * 跟文件信息关联
	public function fileinfo()
	{
		return $this->belongsTo(FiledaModel::class, 'filenum', 'filenum');
	}
	*/
}