<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainMembers extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_members', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('member_name');
            $table->string('member_identity');
            $table->string('member_phone', 12)->nullable();
            $table->string('member_address')->nullable();
            $table->integer('member_edu_id')->nullable()->unsigned();
            $table->integer('member_user_id')->nullable()->unsigned();
            $table->string('member_company')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_members');
    }
}
