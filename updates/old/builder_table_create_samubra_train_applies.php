<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainApplies extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_applies', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('training_id')->nullable()->unsigned();
            $table->integer('record_id')->nullable()->unsigned();
            $table->integer('health_id')->nullable()->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('apply_user_id')->unsigned();
            $table->string('phone');
            $table->string('address');
            $table->string('company');
            $table->smallInteger('pay')->nullable()->default(0);
            $table->smallInteger('theory_score')->nullable();
            $table->smallInteger('operate_score')->nullable();
            $table->text('remark')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_applies');
    }
}
