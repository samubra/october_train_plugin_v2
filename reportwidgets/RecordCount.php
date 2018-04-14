<?php namespace Samubra\Train\ReportWidgets;

use BackendAuth;
use Backend\Classes\ReportWidgetBase;
use Backend\Models\BrandSetting;
use Exception;
use Samubra\Train\Models\Category;
use Samubra\Train\Models\Certificate;

/**
 * User welcome report widget.
 *
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 */
class CertificateCount extends ReportWidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'record_count';

    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->loadData();
        }catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => '培训证书统计',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ]
        ];
    }



    protected function loadData()
    {
        $allCertificates = Certificate::all()->groupBy('type_id');
        $typeList = Category::whereIn('id',$allCertificates->keys())->lists('name','id');

        $countList = [];
        $count = 0;
        foreach ($allCertificates as $key=>$record)
        {
            $count+= $record->count();
            $countList[$key] = ['name'=>$typeList[$key],'number'=>$record->count()];
        }
        $this->vars['all'] = $count;
        $this->vars['records'] = $countList;
        $this->vars['appName'] = BrandSetting::get('app_name');
    }
}
