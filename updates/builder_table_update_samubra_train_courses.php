<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainCourses extends Migration
{
    public function up()
    {
        Schema::table('samubra_train_courses', function($table)
        {
            $table->string('title')->change();
            $table->string('course_type')->change();
            
            $table->integer('default_teacher_id')->unsigned()->before('created_at');
            $table->decimal('default_hours', 5, 1)->default(4.0)->before('created_at');
        });
    }
    
    public function down()
    {
        Schema::table('samubra_train_courses', function($table)
        {
            $table->string('title', 191)->change();
            $table->string('course_type', 191)->change();
            
            $table->dropColumn(['default_teacher_id','default_hours']);
        });
    }
}