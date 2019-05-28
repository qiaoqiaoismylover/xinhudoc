<?php
/**
*	导入测试
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Database\Seeder;
use App\Model\Base\UsersModel;
use App\Model\Base\TokenModel;

class TestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=TestTableSeeder
     * @return void
     */
    public function run()
    {
        $obj = new UsersModel();
		$obj->mobile 	= '15800000000';
		$obj->name 		= '陈先生';
		$obj->nickname 	= '雨中磐石';
		$obj->password 	= '123456';
		$obj->face 		= '/images/face.jpg';
		$obj->flaskm 	= 5;
		
		$obj->save();
		
		$userid = $obj->id;	
		
		$tobj 	= new TokenModel();
		$userAgent = 'testdata';
		$token 	= $tobj->createToken($userid,'pc', md5($userAgent));
		
		$barr 	= \Rock::curlpost(route('apiwepost','company_create'), [
			'name' 	=> '信呼开发团队',
			'shortname' => '信呼团队',
			'logo' 	=> 'http://demo.rockoa.com/images/logo.png',
			'contacts' => '磐石',
			'tel' 	=> '0592-1234567',
		], [
			'header'=> [
				'usertoken'=>$token
			],
			'useragent' => $userAgent
		]);
		print_r($barr);
		echo json_encode($barr,256);
		$tobj->removeToken($token);
    }
	
}
