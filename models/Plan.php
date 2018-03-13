<?php namespace Samubra\Train\Models;

use Model;
use BackendAuth;

/**
 * Model
 */
class Plan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at','created_at','updated_at','deleted_at',];

    protected $jsonable = ['target','result','material','claim','remark','document'];

    public $fillable = ['type_id','create_user_id','is_new','target','result','material','claim','operate_hours','theory_hours','address','contact_person','contact_phone','remark','title','document'];

    protected $casts = [
        'is_new' => 'boolean',
    ];
    /**
     * @var array Validation rules
     */
    public $rules = [
        'type_id'=>'required|exists:samubra_train_categories,id',
        'is_new'=>'boolean',
        'target'=>'nullable',
        'result'=>'nullable',
        'material'=>'nullable',
        'claim'=>'nullable',
        'operate_hours'=>'numeric',
        'theory_hours'=>'numeric',
        'address'=>'nullable|between:3,255',
        'contact_person'=>'nullable',
        'contact_phone'=>'nullable|phone',
        'title'=>'required|between:4,255',
    ];

    public $attributeNames = [
        'type_id'=>'培训类别',
        'is_new'=>'是否新训',
        'target'=>'培训对象',
        'result'=>'培训目的',
        'material'=>'使用教材',
        'claim'=>'培训要求',
        'operate_hours'=>'操作学时',
        'theory_hours'=>'理论学时',
        'address'=>'培训地址',
        'contact_person'=>'联系人',
        'contact_phone'=>'联系电话',
        'title'=>'培训方案标题',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_plans';

    public $belongsTo = [
        'type' =>[
            Category::class,
            'key'=>'type_id',

        ]
    ];



    public $belongsToMany = [
        'courses' => [
            Course::class,
            'table'    => 'samubra_train_plan_course',
            'key'      => 'plan_id',
            'otherKey' => 'course_id',
            'timestamps' => true,
            'pivotModel'=>PlanCoursePivot::class,
            'pivot' => ['start_time', 'end_time','teacher_id','hours','teaching_form']
        ]
    ];

    public function beforeCreate()
    {
        if(BackendAuth::check())
           $this->create_user_id = BackendAuth::getUser()->id;
    }


}
