<?php namespace Samubra\Train\Models;

use Model;
use BackendAuth;

use Carbon\Carbon;
use ApplicationException;
use Flash;

/**
 * Model
 */
class Apply extends \October\Rain\Database\Pivot
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'health_id'=>'nullable|exists:train_lookups,id',
        'status_id'=>'nullable|exists:train_lookups,id',
        'phone'=>'required|phone',
        'address'=>'required|between:2,200',
        'company'=>'required|between:2,200',
        'pay'=>'nullable|numeric',
        'theory_score'=>'nullable|numeric',
        'operate_score'=>'nullable|numeric',
        'remark'=>'nullable',
        //'avatar' => 'dimensions:min_width=100,min_height=200',
    ];

    protected $fillable = ['health_id','status_id','apply_user_id','phone','address','company','pay','theory_score','operate_score','remark'];

    public $attributeNames = [
        'health_id'=>'健康状况',
        'status_id'=>'受理状态',
        'phone'=>'联系电话',
        'address'=>'联系地址',
        'company'=>'工作单位',
        'pay'=>'已交费用',
        'theory_score'=>'理论成绩',
        'operate_score'=>'操作成绩',
        'remark'=>'备注',
        'avatar'=>'照片'
    ];

    protected $jsonable = ['remark'];
    //protected $appends = ['member_name','member_identity','training_type'];
    public $belongsTo = [
        'health'=>[
            Lookup::class,
            'key'=>'health_id',
            'scope'=>'health'
        ],
        'status'=>[
            Lookup::class,
            'key'=>'status_id',
            'scope'=>'applyStatus'
        ]
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];

    protected $projectModel;
    protected $planModel;
    protected $certificateModel;
    protected $statusModel;




    /**
     *
     * @param $query
     * @param $condition array('modelKey'=>'modelValue')或者array('modelKey'=>array('modelValue1','modelValue2'))
     */
    public function scopeBy($query,$conditions)
    {
        //var_dump($conditions);
        if(count($conditions))
        {
            foreach ($conditions as $condition)
            {
                list($modelKey, $modelValue) = array_divide($condition);
                ///dd($modelKey);
                if(is_array($modelValue[0]) && count($modelValue[0]))
                    $query->whereIn($modelKey[0],$modelValue[0]);
                else
                    $query->where($modelKey[0],$modelValue[0]);
            }
        }

        return $query;
    }



    /**
     * 保存实例前检查申请是否符合培训计划限制条件
     */
    public function beforeSave()
    {
        if(BackendAuth::check())
            $this->apply_user_id = BackendAuth::getUser()->id;

    }

    public function beforeCreate()
    {
        $this->getRelateModel();
        if(!$this->status_id)
            $this->status_id = $this->statusModel->id;
        if(!($this->canNotApply()&&$this->checkType()))
            return false;//新添加时，检查培训计划是否允许报名
        if(!$this->planModel->is_new && $this->checkRecord($this->planModel->is_new))
        {
            $certificatePrintDate = $this->getDateCarbon($this->certificateModel->print_date);

            $filters = $this->projectModel->certiicate_print_date_filter;

            if(count($filters)){
                foreach ($filters as $filter){
                    $startData = $this->getDateCarbon($filter['start'],'Y-m-d');
                    $endData = $this->getDateCarbon($filter['end'],'Y-m-d');

                    if($certificatePrintDate->lessThan($startData) || $certificatePrintDate->greaterThan($endData)){
                        return false;
                        break;
                    }
                }
            }
        }
    }
    protected function getRelateModel()
    {
        $this->projectModel = Project::findOrFail($this->project_id);
        $this->planModel = $this->projectModel->plan;
        $this->certificateModel = Certificate::findOrFail($this->certificate_id);
        $this->statusModel = Lookup::where('type','apply_status')->first();
    }
    /**
     * 培训计划报名申请已被关闭时，抛出错误信息
     * @return boolean
     */
    protected function canNotApply()
    {
        if(!$this->projectModel->can_apply)
        {
            throw new ApplicationException('该培训计划不能申请培训报名！');
            return false;
        }
    }


    /**
     * 检查当前日期是否小于等于受理截止日期
     * @return bool
     */
    protected function checkEndApplyDate()
    {
        $planEndApplyDate = $this->getDateCarbon($this->projectModel->end_apply_date);

        return Carbon::now()->lessThanOrEqualTo($planEndApplyDate);
    }
    /**
     * 检查培训证书是否新训，必须满足三个条件：发证日期为NULL、出领证日期为NULL、是否有效标记为false
     * @param bool $is_new
     * @return bool
     */
    protected function checkRecord($is_new = true)
    {
        if((!is_null($this->certificateModel->first_get_date) && !is_null($this->certificateModel->print_date) &&
                !$this->certificateModel->is_valid) && $is_new)
        {
            throw new ApplicationException('该培训项目为新训，不能选择有效的操作证！');
            return false;
        }else{
            return true;
        }
    }

    protected function checkType()
    {
        if($this->planModel->type_id != $this->certificateModel->type_id && $this->planModel->type_id !=
            $this->certificateModel->type->parent_id)
        {
            throw new ApplicationException('该培训方案培训类别与所选操作证的操作项目不符！');
            return false;
        }
    }
    /**
     * 检测当前培训计划是复训时，操作证是否满足要求
     * @return boolean true则符合复审要求
     */
    protected function checkPlanIsReview()
    {







        $type = $this->certificateModel->type;
        switch ($type->unit)
        {
            case 'Y':
                $recordReviewDate = $certificatePrintDate->addYears($type->validity);
                $recordReprintDate = $recordReviewDate->addYears($type->validity);
                break;
            case 'm':
                $recordReviewDate = $certificatePrintDate->addMonths($type->validity);
                $recordReprintDate = $recordReviewDate->addMonths($type->validity);
                break;
            case 'd':
                $recordReviewDate = $certificatePrintDate->addDays($type->validity);
                $recordReprintDate = $recordReviewDate->addDays($type->validity);
                break;

        }

        if (!$this->checkReviewData($recordReviewDate,$planEndApplyDate , true) && !$this->checkReviewData($recordReprintDate,$planEndApplyDate)) {
            throw new ApplicationException('所选操作证不应该在当前时间复审！');
            return false;
        }
        return true;
    }
    /**
     * 检查复审日期是否在允许的培训日期内，复审日期必须在培训日期前2个月
     * @param  Carbon $date          复审或换证日期对象
     * @param  Carbon $planStartDate 培训开始日期对象
     * @return boolean             true则当前操作证在复审日期范围内
     */
    protected function checkReviewData(Carbon $date, Carbon $planEndApplytDate ,$endOf = false)
    {
        return $endOf ?
            $planEndApplytDate->greaterThanOrEqualTo($date->subMonths(2)) && $planEndApplytDate->lessThanOrEqualTo($date->addMonths(2))
            :$planEndApplytDate->greaterThanOrEqualTo($date->subMonths(2)) && $planEndApplytDate->lessThanOrEqualTo($date->addMonths(2)->endOfMonth());
    }
    /**
     * 返回日期对象
     * @param  init $date 日期
     * @return subject       Carbon日期对象
     */
    protected function getDateCarbon($date,$format = 'Y-m-d H:i:s')
    {
        //list($year,$month,$day) = explode('-',$date);

        return Carbon::createFromFormat($format,$date);
        //return Carbon::instance($date);
    }
}
