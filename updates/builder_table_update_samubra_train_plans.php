<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSamubraTrainPlans extends Migration
{
    public function up()
    {
        Schema::table('samubra_train_plans', function($table)
        {
            $table->text('document')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('samubra_train_plans', function($table)
        {
            $table->dropColumn('document');
        });
    }
}
