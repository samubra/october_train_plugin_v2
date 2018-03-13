<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainTraining extends Migration
{
    public function up()
    {
        Schema::table('samubra_train_training', function($table)
        {
            $table->string('title')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('samubra_train_training', function($table)
        {
            $table->dropColumn('title');
        });
    }
}
