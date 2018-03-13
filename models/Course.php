<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['created_at','updated_at'];
    /**
     * @var array Validation rules
     */
    public $rules = [
        'course_title'=>'required|between:2,200',
        'course_type'=>'required|in:theory,operate',
    ];

    public $attributeNames = [
        'course_title' => '课程标题',
        'course_type' => '课程类型'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_courses';

    public $belongsToMany = [
        'plans' => [
            Plan::class,
            'table'    => 'samubra_train_plan_course',
            'otherKey'      => 'plan_id',
            'key' => 'course_id',
            'timestamps' => true,
            'pivotModel'=>PlanCoursePivot::class,
            'pivot' => ['start_time', 'end_time','teacher_id','hours','teaching_form']
        ]
    ];

    protected $appends = ['course_type_text'];

    public static function getCourseTypeOptions()
    {
        return [
            'theory'=>'理论课',
            'operate'=>'操作课'
        ];
    }

    public function getCourseTypeTextAttribute()
    {

        $list = self::getCourseTypeOptions();
        //$list = [
        //    'theory'=>'理论课',
        //    'operate'=>'操作课'
        //];
        return isset($list[$this->course_type])?$list[$this->course_type]:$this->course_type;

    }



}
