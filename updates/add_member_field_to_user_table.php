<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddMemberFieldToUserTable extends Migration
{
    public function up()
    {
        
        Schema::table('users', function ($table) {
            $table->string('identity', 20)->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('address', 100)->nullable();
            $table->integer('edu_id')->unsigned();
            //$table->foreign('edu_id')->references('id')->on('train_lookups');
            $table->string('company', 100)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn([
                'identity',
                'phone',
                'address',
                'edu_id',
                'company',
            ]);
        });
    }
}