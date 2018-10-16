<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainProjectCourses extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_project_courses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('project_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->unsignedInteger('teacher_id');
            $table->decimal('hours', 10, 1)->default(4.0);
            $table->json('teaching_type')->nullable();
            $table->timestamps();
            
            $table->primary(['project_id','course_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_project_courses');
    }
}