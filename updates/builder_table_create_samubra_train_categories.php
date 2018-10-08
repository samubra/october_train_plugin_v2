<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainCategories extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('organ_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('lft')->nullable()->unsigned();
            $table->integer('rgt')->nullable()->unsigned();
            $table->integer('depth')->nullable()->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_categories');
    }
}