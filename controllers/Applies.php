<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Samubra\Train\Models\Apply;

class Applies extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.ImportExportController',
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public $requiredPermissions = [
        'manage_apply' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Train', 'main-menu-item2', 'apply-item');
        $this->addJs('/plugins/rainlab/user/assets/js/bulk-actions.js');
    }

    public function onChangeStatus()
    {
        $status = [
            'waiting_acceptance'=>4,
            'receiving'=>5,
            'allowed'=>6,
            'not_allow'=>7,
            'waiting_get_record'=>9,
            'get_record'=>10,
        ];

        Apply::whereIn('id',post('checked'))->update(['status_id'=>$status[post('action')]]);
        return \Redirect::refresh();
    }



}
