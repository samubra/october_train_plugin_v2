<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainRecords extends Migration
{
    public function up()
    {
        Schema::table('samubra_train_records', function($table)
        {
            $table->integer('import_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('samubra_train_records', function($table)
        {
            $table->dropColumn('import_id');
        });
    }
}
