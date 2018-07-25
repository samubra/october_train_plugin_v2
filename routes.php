<?php

use Samubra\Train\Models\Project;
use Samubra\Train\Models\Plan;
//use Faker;
use Samubra\Train\Models\Category;
use Samubra\Train\Models\Lookup;
use Carbon\Carbon;

Route::get('seeding', function () {
    echo "seeding plan and project ！";
    /**
    $faker = Faker\Factory::create('zh_CN');
    $typeIds = Category::lists('id');
    //dd($faker->phoneNumber);
    for($i = 0;$i<10;$i++){
        $plan = Plan::create([
            'type_id' => $faker->randomElement($typeIds),
            'create_user_id' => '1',
            'is_new' => $faker->randomElement([0,1]),
            'target' => $faker->text(100),
            'result' => $faker->text(100),
            'train_claim' => $faker->text(100),
            'operate_hours' => $faker->numberBetween(50,100),
            'theory_hours => $faker->numberBetween(50,100)',
            'address' => $faker->address,
            'contact_person' => $faker->name,
            'contact_phone' => $faker->phoneNumber,
            'title' => $faker->sentence(10),
            'content' => $faker->text(200)
       ]);
       //echo $plan->title.'<br/>';
    }

    $planIds = Plan::lists('id');
    $statusIds = Lookup::where('type','project_status')->lists('id');

    for($i = 0;$i<100;$i++){
        $dateFaker = $faker->date('Y-m-d','now');
        $date = Carbon::createFromFormat('Y-m-d',$dateFaker);
        

        trace_log($date->toDateString());

        
        $project = Project::create([
            'title' => $faker->realText(50,1),
             'plan_id' => $faker->randomElement($planIds),
             'status_id' => $faker->randomElement($statusIds),
             'can_apply' => $faker->randomElement([0,1]),
             'start_date' =>$date->toDateString(),
             'end_date' => $date->addMonth()->toDateString(),
             'exam_date' => $date->addDays(10)->toDateString(),
             'end_apply_date' => $date->subMonth()->subDays(15)->toDateString(),
             'cost' => $faker->numberBetween(500,1500),
             'certiicate_print_date_filter' => [
                 ['start' => $date->subYears(3)->startOfMonth()->toDateString(),'end'  => $date->addMonths(2)->endOfMonth()->toDateString()],
                 ['start' => $date->subYears(3)->subMonths(2)->startOfMonth()->toDateString(),'end'  => $date->addMonths(2)->endOfMonth()->toDateString()]
             ]
       ]);
       echo $project->title.'<br/>';
    }
    */
});