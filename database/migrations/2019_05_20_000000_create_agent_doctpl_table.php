<?php
/**
*	文档模版
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2019-05-27
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentDoctplTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'doctpl'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '文档模版';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			
			$table->string('filename',100)->default('')->comment('模版名称');
			$table->string('filenum',50)->default('')->comment('文件编号关联文件系统上');
			$table->string('fileext',20)->default('')->comment('类型docx,xlsx,pptx');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');
			$table->string('shateid',200)->default('')->comment('共享给Id');
			$table->string('shatename',200)->default('')->comment('共享给');
			$table->integer('sort')->default(0)->comment('排序号');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->index('cid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablename);
    }
}
