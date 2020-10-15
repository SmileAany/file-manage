<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->comment('用户id');
            $table->bigInteger('model_id')->nullable()->comment('模型id');
            $table->string('model_type',50)->nullable()->comment("模型名称");
            $table->string('filename',100)->nullable(false)->comment('上传文件名称');
            $table->string('original',100)->nullable(false)->comment('存储文件名称');
            $table->string('ext',50)->nullable(false)->comment('文件格式');
            $table->string('size',20)->nullable(false)->comment('文件大小');
            $table->string('path',100)->nullable(false)->comment('文件路径');
            $table->tinyInteger('type')->nullable(false)->default(0)->comment('类型 附件的作用区间');
            $table->timestamp('created_at')->nullable(true)->comment('上传时间');
            $table->timestamp('updated_at')->nullable(true)->comment('更改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
