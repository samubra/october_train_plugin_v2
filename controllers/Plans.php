<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Samubra\Train\Models\Plan;
use Flash;

class Plans extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'manage_plan' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Train', 'main-menu-item2', 'plan-item');
    }

    public function onCopy()
    {
        if(($checked = post('checked')) && is_array($checked) && count($checked)==1)
        {
            $plan = Plan::find($checked[0]);

            $newPlan = $plan->toArray();
            unset($newPlan['id'],$newPlan['created_at'],$newPlan['updated_at']);

            Plan::create($newPlan);
            
            Flash::success('所复制的培训计划已创建成功！');
        }else{
            Flash::error('请选择一个（仅一项）需要复制的培训计划！');
        }
        return $this->listRefresh();
    }
}
