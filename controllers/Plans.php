<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Plans extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'train.manage.plan' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Train', 'train', 'train-plan');
    }
}
