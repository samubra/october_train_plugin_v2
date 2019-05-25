<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;    
use Samubra\Train\Models\Project;
use Samubra\Train\Models\Certificate;
use Flash;
use View;
use Backend;
use Carbon\Carbon;

class Projects extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';


    public $requiredPermissions = [
        'manage_training' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Train', 'main-menu-item2', 'training-item');
    }


    public function onChangeStatus()
    {
        if (
            ($action = post('action')) &&
            ($certificateIds = post('checked')) &&
            is_array($certificateIds) &&
            count($certificateIds)
        ) {
            if($action == 'is_not_valid')
                $valid = false;
            elseif($action == 'is_valid')
                $valid = true;
            Certificate::whereIn('id',$certificateIds)->update(['is_valid'=>$valid]);
            Flash::success('所选择的证书已设置为');
        }else{
            Flash::error('请选择需要操作的证书进行操作！');
        }
        return $this->listRefresh();
    }
	
	/**
     * update
     */
    public function view($certificateId = null)
    {
		$this->addJs("/plugins/samubra/train/assets/js/jquery.jqprint-master/jquery.jqprint-0.3.js");
		$this->addCss("/plugins/samubra/train/assets/css/print.css");
		

		
        $this->vars['controller'] = $this->controllerName;
		
		$certificateModel = Certificate::find($certificateId);
		$projectModel = $certificateModel->projects[0];
		$data['certificate'] = $certificateModel;
		$data['project'] = $projectModel;
		$data['pivot'] = $certificateModel->projects[0]->pivot;
		
		$data['user_sex'] = (int)substr($certificateModel->user->identity,-2,1)% 2 === 0 ? '女' : '男';
		
		
		$data['url'] = Backend::url('samubra/train/projects/update/'.$projectModel->id);
		
		$data['date_now'] = Carbon::now(new \DateTimeZone('Asia/Chongqing'))->format('Y年m月d日');

		if(!$projectModel->plan->is_new){
            $printDate = Carbon::createFromFormat('Y-m-d',$certificateModel->print_date);
            $data['print_date'] = $printDate->format('Y年m月d日');
            $data['print_end'] = $printDate->addYears(6)->format('Y年m月d日');
        }


		$data['start_date'] = Carbon::createFromFormat('Y-m-d',$projectModel->start_date)->format('Y年m月d日');
		$data['end_date'] = Carbon::createFromFormat('Y-m-d',$projectModel->end_date)->format('Y年m月d日');
		//$data['hours'] = (Carbon::createFromFormat('Y-m-d',$projectModel->end_date)
                //            ->diffInDays(Carbon::createFromFormat('Y-m-d',$projectModel->start_date),true)+1)*8;
		$data['hours'] = $projectModel->plan->operate_hours + $projectModel->plan->theory_hours;
        $this->pageTitle = '打印'.$certificateModel->user->name.'的申请表格';
        return View::make('samubra.train::project.print', $data);
    }

    public function onCopy()
    {
        if(($checked = post('checked')) && is_array($checked) && count($checked)==1)
        {
            $project = Project::find($checked[0]);

            $newProjectArray = $project->toArray();
            unset($newProjectArray['id'],$newProjectArray['created_at'],$newProjectArray['updated_at']);

            $new = Project::create($newProjectArray);
            if($project->has('courses'))
            {
                $list = [];
               foreach($project->courses as $course){
                   $list[$course->id] = [
                    'start_time' => '2018-07-27 12:00:00',
                    'end_time' => '2018-07-27 18:00:00',
                    'teacher_id' => '1',
                    'hours' => '4.0',
                    'teaching_form' => '所担负的撒'
                   ];
               }
                $new->courses()->attach($list);
            }
            
            Flash::success('所复制的项目已创建成功！');
        }else{
            Flash::error('请选择一个（仅一项）需要复制的培训项目！');
        }
        return $this->listRefresh();
    }
}
