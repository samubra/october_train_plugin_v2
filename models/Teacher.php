<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Teacher extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'teacher_name' => 'required|between:2,12',
        'teacher_identity' => 'required|between:18,20|identity',
        'teacher_qualification_number' => 'nullable|between:4,12',
        'teacher_job_title' => 'nullable|between:4,12',
        'teacher_phone' => 'required|numeric|between:8,12|phone',
        'teacher_company' => 'required|between:4,12',
        'teacher_edu_id' => 'required|exists:samubra_train_lookups,id',
        'avatar'=>'image|mimes:jpeg,png|size:300',
        'document'=>'image|mimes:jpeg,png|size:1000',
    ];

    public $attributeNames = [
        'teacher_name' => '教师姓名',
        'teacher_identity' => '教师身份证号',
        'teacher_qualification_number' => '资格证号',
        'teacher_job_title' => '教师职称',
        'teacher_phone' => '教师联系电话',
        'teacher_company' => '教师所在工作单位',
        'teacher_edu_id' => '教师学历',
        'avatar'=>'教师照片',
        'document'=>'教师文档照片'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_teachers';


    public $belongsTo = [
        'teacherEdu' => [
            Lookup::class,
            'default'=>['display_name'=>'未设置'],
            'key'=>'teacher_edu_id',
            'scope'=>'edu'
        ]
    ];

    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    public $attachMany = [
        'document' => 'System\Models\File'
    ];

    public $hasMany = [
        'courses'=>[
            Course::class,
            'key'=>'teacher_id'
        ],
        'courses_count'=>[
            Course::class,
            'key'=>'teacher_id',
            'count'=>true
        ]
    ];

}
