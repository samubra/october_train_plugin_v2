<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainPlanCourse extends Migration
{
    public function up()
    {
        Schema::table('samubra_train_plan_course', function($table)
        {
            $table->integer('teacher_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('samubra_train_plan_course', function($table)
        {
            $table->integer('teacher_id')->nullable(false)->change();
        });
    }
}
