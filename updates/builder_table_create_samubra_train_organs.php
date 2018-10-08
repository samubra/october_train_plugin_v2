<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainOrgans extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_organs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('complete_type')->nullable();
            $table->smallInteger('validity')->default(0);
            $table->string('unit')->default('Y');
            $table->boolean('need_review')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_organs');
    }
}
