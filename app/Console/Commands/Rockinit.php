<?php
/**
*	命令系统初始化
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-06
*/

namespace App\Console\Commands;

use DB;

class Rockinit extends Rockcommandsbase
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'rock:docs {param}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'xinhuoa platform service init checkbase';

   

    /**
     * Execute the console command.
     * php artisan rock:docs checkbase
     * @return mixed
     */
    public function handle()
    {
		$param 	= $this->argument('param');
		
		//创建数据库
		if($param=='checkbase'){
			$this->checkbases();
			return;
		}
		
		
		echo 'success';
    }
	
	//不存在就创建数据库
	private function checkbases()
	{
		$dbpzt 	= config('database.connections.mysql');
		$base	= $dbpzt['database'];
		$charset	= $dbpzt['charset'];
		$collation	= $dbpzt['collation'];
		
		$allrows	= DB::select('show databases');
		foreach($allrows as $dors)$allbase[] = $dors->Database;
		if(in_array($base, $allbase)){
			echo 'database '.$base.' exists';
		}else{
			$sql = "CREATE DATABASE `$base` DEFAULT CHARACTER SET $charset COLLATE $collation";
			DB::select($sql);
			echo 'database '.$base.' create success';
		}
	}
		
}
