<?php namespace Samubra\Train\ReportWidgets;

use BackendAuth;
use Backend\Classes\ReportWidgetBase;
use Backend\Models\BrandSetting;
use Exception;
use Samubra\Train\Models\Category;
use Samubra\Train\Models\Certificate;
use Carbon\Carbon;

/**
 * User welcome report widget.
 *
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 */
class CertificatesReport extends ReportWidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'certificate_report';

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
                'default'           => '培训需求统计',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
            'type' => [
                'title'             => '统计类别',
                'default'           => 'review',
                'type'              => 'dropdown',
                'options'           => ['review'=>'统计复审', 'reprint'=>'统计换证'],
                'validationMessage' => '请选择统计类别',
            ],
        ];
    }



    protected function loadData()
    {
        $filterDate = $this->getFiliterDate();
        trace_sql();
        $reviewCertificates = Certificate::where('print_date','>=',$filterDate['review']['start'])->where('print_date','<=',$filterDate['review']['end'])->where('is_valid',true)->get();
        $reprintCertificates = Certificate::where('print_date','>=',$filterDate['reprint']['start'])->where('print_date','<=',$filterDate['reprint']['end'])->where('is_valid',true)->get();

        $count['review'] = $filterDate['review'] + $this->getCountList($reviewCertificates);
        $count['reprint'] = $filterDate['reprint'] + $this->getCountList($reprintCertificates);
        
        $this->vars['certificates_count'] = $count;
        $this->vars['appName'] = BrandSetting::get('app_name');
    }

    protected function getFiliterDate()
    {
        $review['start'] = Carbon::now()->subYears(3)->startOfMonth()->toDateString();
        $review['end'] = Carbon::now()->subYears(3)->addMonths(2)->endOfMonth()->toDateString();
        $reprint['start'] = Carbon::now()->subYears(6)->toDateString();
        $reprint['end'] = Carbon::now()->subYears(6)->addMonths(2)->toDateString();
        
        return ['review'=>$review,'reprint'=>$reprint];
    }

    protected function getCountList($model)
    {
        $list = [];
        if($list['count'] = $model->count()){
            $group = $model->groupBy('type_id');
            
            $typeList = Category::whereIn('id',$group->keys())->lists('title','id');
            
            foreach ($typeList as $key=>$type)
            {
                $list['list'][$key] = ['title'=>$typeList[$key],'number'=>$group->get($key)->count()];
            }
        }   
        return $list;
    }
}
