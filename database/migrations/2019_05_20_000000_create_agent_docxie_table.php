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

class CreateAgentDocxieTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'docxie'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '文档协作';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			
			$table->string('filename',100)->default('')->comment('文档名称');
			$table->string('filenum',50)->default('')->comment('文件编号关联文件系统上');
			$table->string('fenlei',50)->default('')->comment('分类');
			$table->string('fileext',20)->default('')->comment('类型docx,xlsx,pptx');
			$table->datetime('adddt')->nullable()->comment('添加时间');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');
			
			$table->string('xienameid',500)->default('')->comment('协作人ID');
			$table->string('xiename',500)->default('')->comment('协作人');
			
			$table->string('recename',500)->default('')->comment('可查看人');
			$table->string('receid',500)->default('')->comment('可查看人ID');
			
			$table->string('explian',500)->default('')->comment('说明');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->index('cid');
			$table->index('aid');
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
