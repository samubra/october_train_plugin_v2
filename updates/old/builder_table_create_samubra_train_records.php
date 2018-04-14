<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainRecords extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_records', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('edu_id')->unsigned();
            $table->date('first_get_date')->nullable();
            $table->date('print_date')->nullable();
            $table->boolean('is_valid')->default(0);
            $table->string('phone');
            $table->string('address');
            $table->string('company');
            $table->text('remark')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_records');
    }
}
