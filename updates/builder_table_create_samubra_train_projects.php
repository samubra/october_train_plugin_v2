<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainProjects extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_projects', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->unsignedInteger('plan_id');
            $table->string('status');
            $table->boolean('on_apply')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('exam_date');
            $table->dateTime('end_apply_date');
            $table->decimal('price', 10, 2)->default(0);
            $table->text('remark')->nullable();
            $table->text('certificate_filter')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_projects');
    }
}