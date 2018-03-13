<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainTeachers extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_teachers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('teacher_name');
            $table->string('teacher_identity');
            $table->string('teacher_qualification_number')->nullable();
            $table->string('teacher_job_title')->nullable();
            $table->string('teacher_phone');
            $table->string('teacher_company');
            $table->integer('teacher_edu_id')->nullable()->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_teachers');
    }
}
