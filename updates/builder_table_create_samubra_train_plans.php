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
            $table->increments('id')->unsigned();
            $table->string('title', 200);
            $table->integer('category_id')->unsigned()->nullable();
            $table->boolean('is_new')->default(1);
            $table->smallInteger('operate_hours')->nullable();
            $table->smallInteger('theory_hours')->nullable();
            $table->string('address', 100)->nullable();
            $table->string('contact_name', 20)->nullable();
            $table->string('contact_phone', 12)->nullable();

            $table->text('target')->nullable();
            $table->text('result')->nullable();
            $table->text('material')->nullable();
            $table->text('claim')->nullable();
            $table->text('document')->nullable();
            $table->text('other')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_plans');
    }
}