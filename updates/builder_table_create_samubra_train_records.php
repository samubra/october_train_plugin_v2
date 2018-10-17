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
            $table->increments('id')->unsigned();
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('samubra_train_orders')->onDelete('cascade');
            $table->integer('certificate_id')->unsigned()->index();
            $table->foreign('certificate_id')->references('id')->on('samubra_train_certificates')->onDelete('cascade');
            $table->integer('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('samubra_train_projects')->onDelete('cascade');
            $table->string('health')->nullable();
            $table->string('status')->nullable();
            $table->text('profile');
            $table->unsignedInteger('amount');
            $table->decimal('price', 10, 2);
            $table->smallInteger('theory_score')->nullable();
            $table->smallInteger('operate_score')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_records');
    }
}