<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainCertificates extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_certificates', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('identity');
            $table->string('id_type');
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('organ_id')->unsigned();
            $table->date('first_get_date');
            $table->date('print_date');
            $table->boolean('is_valid')->default(0);
            $table->boolean('is_reviewed')->default(0);
            $table->text('profile')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_certificates');
    }
}