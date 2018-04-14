<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainCourses extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('course_title');
            $table->string('course_type')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_courses');
    }
}
