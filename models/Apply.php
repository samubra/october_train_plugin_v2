<?php namespace Samubra\Train\Models;

use Model;
use BackendAuth;

use Carbon\Carbon;
use ApplicationException;
use Flash;
use Samubra\Train\Classes\CanApply;

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
    protected $appends = ['apply_status','apply_health'];
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


    public function getApplyStatusAttribute()
    {
        return $this->status ? $this->status->name : "未设置";
    }
    public function getApplyHealthAttribute()
    {
        return $this->health ? $this->health->name : "未设置";
    }

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
        $checkApply = new CanApply($this->projectModel,$this->certificateModel);
        return $checkApply->check();
    }
    protected function getRelateModel()
    {
        $this->projectModel = Project::with('plan','plan.type')->findOrFail($this->project_id);
        $this->certificateModel = Certificate::with('type')->findOrFail($this->certificate_id);
        $this->statusModel = Lookup::where('type','apply_status')->first();
    }

}
