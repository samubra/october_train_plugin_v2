<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Teachers extends Controller
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
        'manage_teacher' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Train', 'main-menu-item','teacher-item');
    }
}
