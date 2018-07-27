<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Samubra\Train\Models\Certificate;
use Flash;

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
            Flash::success('请选择需要操作的证书进行操作！');
        }
        return $this->listRefresh();
    }
}
