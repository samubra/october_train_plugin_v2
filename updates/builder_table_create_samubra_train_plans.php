<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainPlans extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_plans', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('type_id')->nullable()->unsigned();
            $table->integer('create_user_id')->nullable()->unsigned();
            $table->boolean('is_new')->default(0);
            $table->text('target')->nullable();
            $table->text('result')->nullable();
            $table->text('material')->nullable();
            $table->text('claim')->nullable();
            $table->smallInteger('operate_hours')->nullable();
            $table->smallInteger('theory_hours')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('remark')->nullable();
            $table->string('title');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_plans');
    }
}
