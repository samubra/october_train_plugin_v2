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
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('identity');
            $table->string('qualification_number')->nullable();
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('edu_type')->nullable();
            $table->string('company')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_teachers');
    }
}
