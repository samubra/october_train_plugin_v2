<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainPlanCourse extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_plan_course', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('plan_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('teacher_id')->unsigned();
            $table->decimal('hours', 10, 1)->default(4.0);
            $table->string('teaching_form')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['plan_id','course_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_plan_course');
    }
}
