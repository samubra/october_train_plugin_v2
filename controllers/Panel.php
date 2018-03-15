<?php namespace Samubra\Train\Controllers;

use Redirect;
use BackendAuth;
use BackendMenu;
use Backend\Controllers\Index;
use Backend\Widgets\ReportContainer;

class Panel extends Index
{

    use \Backend\Traits\InspectableContainer;

    public $requiredPermissions = [
        'manage_apply' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContextOwner('Samubra.Train');

        $this->addCss('/modules/backend/assets/css/dashboard/dashboard.css', 'core');
    }

    public function index()
    {

        if ($redirect = $this->checkPermissionRedirect()) {
            return $redirect;
        }

        $this->initReportContainer();

        $this->pageTitle = '培训情况汇总';

        BackendMenu::setContextMainMenu('main-menu-item2');
    }


    /**
     * Prepare the report widget used by the dashboard
     * @param Model $model
     * @return void
     */
    protected function initReportContainer()
    {
        new ReportContainer($this, 'config_panel.yaml');
    }





}
