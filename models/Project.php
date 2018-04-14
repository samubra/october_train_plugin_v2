<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at','start_date','end_date','exam_date','end_apply_date','created_at','updated_at'];

    protected $casts = [
        'can_apply' => 'boolean',
    ];
    /**
     * @var array Validation rules
     */
    public $rules = [
        'plan_id'=>'required|exists:train_plans,id',
        'status_id'=>'required|exists:train_lookups,id',
        'can_apply'=>'boolean',
        'end_apply_date'=>'required|date|before:end_date',
        'start_date'=>'required|date|before:end_date',
        'end_date'=>'required|date|after:start_date',
        'exam_date'=>'required|date',
        'cost'=>'required|numeric',
        'train_remark'=>'nullable',
        'title'=>'required|between:4,200',
        'photos' => 'nullable|max:1000'
    ];

    public $attributeNames = [
        'plan_id'=>'培训方案',
        'status_id'=>'培训状态',
        'can_apply'=>'是否允许申请培训',
        'end_apply_date'=>'申请截止日期',
        'start_date'=>'培训开始日期',
        'end_date'=>'培训结束日期',
        'exam_date'=>'计划考试日期',
        'cost'=>'预计收费金额',
        'train_remark'=>'备注信息',
        'title'=>'培训项目标题',
        'photos'=>'培训照片'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'train_projects';
    protected $jsonable = ['train_remark'];

    public $belongsTo = [
        'plan'=>[
            Plan::class,
            'key'=>'plan_id'
        ],
        'status'=>[
            Lookup::class,
            'key'=>'status_id',
            'scope'=>'projectStatus'
        ],
    ];


    public $belongsToMany = [
        'courses' => [
            Course::class,
            'table'    => 'train_course_project',
            'key'      => 'project_id',
            'otherKey' => 'course_id',
            'timestamps' => true,
            'pivotModel'=>ProjectCoursePivot::class,
            'pivot' => ['start_time', 'end_time','teacher_id','hours','teaching_form']
        ]
    ];
    

    public function scopeCanApply($query)
    {
        return $query->where('can_apply',true);
    }
    public $attachMany = [
        'photos' => 'System\Models\File'
    ];

}
