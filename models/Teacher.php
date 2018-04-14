<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Teacher extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at','created_at','updated_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|between:2,12',
        'identity' => 'required|identity',
        'qualification_number' => 'nullable|between:4,12',
        'job_title' => 'nullable|between:4,12',
        'phone' => 'required|numeric|phone',
        'company' => 'required|between:4,12',
        'edu_id' => 'required|exists:train_lookups,id',
        //'avatar'=>'image|mimes:jpeg,png|size:300',
       // 'document'=>'image|mimes:jpeg,png|size:1000',
    ];

    public $attributeNames = [
        'name' => '教师姓名',
        'identity' => '教师身份证号',
        'qualification_number' => '资格证号',
        'job_title' => '教师职称',
        'phone' => '教师联系电话',
        'company' => '教师所在工作单位',
        'edu_id' => '教师学历',
        'avatar'=>'教师照片',
        'document'=>'教师文档照片'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'train_teachers';


    public $belongsTo = [
        'teacherEdu' => [
            Lookup::class,
            'default'=>['display_name'=>'未设置'],
            'key'=>'edu_id',
            'scope'=>'edu'
        ]
    ];

    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    public $attachMany = [
        'document' => 'System\Models\File'
    ];


}
