<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Certificates extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
        'Backend.Behaviors.ImportExportController',
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public $requiredPermissions = [
        'manage_record' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Train', 'main-menu-item2', 'record-item');
        $this->addJs('/plugins/rainlab/user/assets/js/bulk-actions.js');
    }

    public function onChangeStatus()
    {
        //var_dump(request('action'));
    }
}
