<?php
/**
*	导入系统应用
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Database\Seeder;
use App\RainRock\Systems\Databeifen;

class AgentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $to  = Databeifen::insert('agent');
		$to1 = Databeifen::insert('agentfields');
		$to2 = Databeifen::insert('agentmenu');
		$to3 = Databeifen::insert('agenttodo');
		$to4 = Databeifen::insert('task');
		$to5 = Databeifen::insert('flowmenu');
		echo 'addagent('.$to.'),fields('.$to1.'),menu('.$to2.'),todo('.$to3.'),task('.$to4.'),flowmenu('.$to5.')';
    }
	
}
