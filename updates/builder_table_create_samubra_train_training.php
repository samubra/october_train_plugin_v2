<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainTraining extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_training', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('plan_id')->nullable()->unsigned();
            $table->integer('status_id')->nullable()->unsigned();
            $table->boolean('can_apply')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('exam_date')->nullable();
            $table->date('end_apply_date')->nullable();
            $table->smallInteger('cost')->nullable();
            $table->text('remark')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_training');
    }
}
