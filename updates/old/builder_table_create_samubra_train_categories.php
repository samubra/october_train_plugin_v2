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
            $table->string('name');
            $table->string('complete_type')->nullable();
            $table->integer('validity')->nullable()->default(3);
            $table->string('unit', 2)->nullable()->default('Y');
            $table->string('organ')->nullable();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('nest_left')->nullable()->unsigned();
            $table->integer('nest_right')->nullable()->unsigned();
            $table->integer('nest_depth')->nullable()->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_categories');
    }
}
