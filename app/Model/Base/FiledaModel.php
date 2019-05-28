<?php
/**
*	系统文件
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;

class FiledaModel extends Model
{
	protected $table = 'fileda';
	public $timestamps 	= false;
	
	protected $appends = [
	    'ishttpout',
		'fileexists'
    ];
	
	//是否外部链接的文件
	public function getIsHttpoutAttribute()
    {
		return substr($this->filepath, 0, 4)=='http';
    }
	
	//文件是否存在
	public function getFileExistsAttribute()
    {
		if(substr($this->filepath, 0, 4)=='http'){
			return true;
		}else{
			return file_exists(public_path($this->filepath));
		}
    }
}