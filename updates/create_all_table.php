<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAllTable extends Migration
{
    protected $prefix = 'train_';
    public function up()
    {

        
        Schema::create($this->prefix.'categories', function($table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('complete_type')->nullable();
            $table->integer('validity')->nullable()->default(3);
            $table->string('unit')->nullable()->default('Y');
            $table->string('organ')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('_lft')->nullable();
            $table->integer('_rgt')->nullable();
            $table->integer('depth')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create($this->prefix.'lookups', function($table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('users', function ($table) {
            $table->string('identity', 20)->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('address', 100)->nullable();
            $table->integer('edu_id')->nullable()->unsigned();
            //$table->foreign('edu_id')->references('id')->on('train_lookups');
            $table->string('company', 100)->nullable();
        });
        Schema::create($this->prefix.'teachers', function($table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('identity', 20);
            $table->string('qualification_number', 20)->nullable();
            $table->string('job_title', 20)->nullable();
            $table->string('phone', 12);
            $table->integer('edu_id')->nullable()->unsigned();
            $table->foreign('edu_id')->references('id')->on($this->prefix.'lookups');
            $table->string('company', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        /**
        Schema::create($this->prefix.'members', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('identity', 20);
            $table->string('phone', 12)->nullable();
            $table->string('address', 100)->nullable();
            $table->integer('edu_id')->unsigned();
            $table->foreign('edu_id')->references('id')->on($this->prefix.'lookups');
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('company', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        **/
        Schema::create($this->prefix.'courses', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 200);
            $table->enum('type', ['theory', 'operate']);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create($this->prefix.'plans', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 200);
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on($this->prefix.'categories');
            $table->integer('create_user_id')->unsigned();
            $table->foreign('create_user_id')->references('id')->on('backend_users');
            $table->boolean('is_new')->default(0);
            $table->text('target')->nullable();
            $table->text('result')->nullable();
            $table->text($this->prefix.'material')->nullable();
            $table->text($this->prefix.'claim')->nullable();
            $table->smallInteger('operate_hours')->nullable();
            $table->smallInteger('theory_hours')->nullable();
            $table->string('address', 100)->nullable();
            $table->string('contact_person', 20)->nullable();
            $table->string('contact_phone', 12)->nullable();
            $table->text($this->prefix.'remark')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create($this->prefix.'projects', function ($table) {
            $table->engine = 'InnoDB';
            $table->uuid('id')->unique();
            $table->primary('id');
            $table->string('title',200);
            $table->integer('plan_id')->unsigned();
            $table->foreign('plan_id')->references('id')->on($this->prefix.'plans');
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on($this->prefix.'lookups');
            $table->boolean('can_apply')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('exam_date');
            $table->dateTime('end_apply_date');
            $table->smallInteger('cost')->default(0);
            $table->text($this->prefix.'remark')->nullable();
            $table->text('certiicate_print_date_filter')->nullable();
            $table->timestamps();
            
            $table->softDeletes();
        });
        Schema::create($this->prefix.'certificates', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on($this->prefix.'categories');
            $table->date('first_get_date')->nullable();
            $table->date('print_date')->nullable();
            $table->boolean('is_valid')->default(0);
            $table->string('phone', 12)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('company', 100)->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create($this->prefix.'course_project', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('course_id')->unsigned()->index();
            $table->foreign('course_id')->references('id')->on($this->prefix.'courses')->onDelete('cascade');
            $table->uuid('project_id')->index();
            $table->foreign('project_id')->references('id')->on($this->prefix.'projects')->onDelete('cascade');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('teacher_id')->nullable()->unsigned();
            $table->foreign('teacher_id')->references('id')->on($this->prefix.'teachers');
            $table->decimal('hours', 10, 1)->default(4.0);
            $table->string('teaching_form')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['course_id', 'project_id']);
        });
        Schema::create($this->prefix.'certificate_project', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('certificate_id')->unsigned()->index();
            $table->foreign('certificate_id')->references('id')->on($this->prefix.'certificates')->onDelete('cascade');
            $table->uuid('project_id')->index();
            $table->foreign('project_id')->references('id')->on($this->prefix.'projects')->onDelete('cascade');
            $table->integer('health_id')->unsigned()->nullable();
            $table->foreign('health_id')->references('id')->on($this->prefix.'lookups');
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->references('id')->on($this->prefix.'lookups');
            $table->integer('apply_user_id')->unsigned()->nullable();
            $table->foreign('apply_user_id')->references('id')->on('backend_users');
            $table->string('phone', 12)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('company', 100)->nullable();
            $table->smallInteger('pay')->default(0);
            $table->smallInteger('theory_score')->nullable();
            $table->smallInteger('operate_score')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->primary(['project_id', 'certificate_id']);
        });
    }
    
    public function down()
    {
        
        if (Schema::hasColumn('users', 'identity')) {
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
        
        Schema::dropIfExists($this->prefix.'certificate_project');
        Schema::dropIfExists($this->prefix.'course_project');
        Schema::dropIfExists($this->prefix.'certificates');
        Schema::dropIfExists($this->prefix.'projects');
        Schema::dropIfExists($this->prefix.'plans');
        Schema::dropIfExists($this->prefix.'courses');
        //Schema::dropIfExists($this->prefix.'members');
        
        Schema::dropIfExists($this->prefix.'teachers');
        Schema::dropIfExists($this->prefix.'lookups');
        Schema::dropIfExists($this->prefix.'categories');
    }
}