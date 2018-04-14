<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-8
 * Time: 下午7:46
 */

namespace Samubra\Train\Models;


class ProjectCoursePivot extends \October\Rain\Database\Pivot
{
    use \October\Rain\Database\Traits\Validation;

    public $dates = ['start_time','end_time'];
    protected $appends = ['course_teacher_name'];
    public $belongsTo = [
        'teacher'=>[
            Teacher::class,
            'key'=>'teacher_id'
        ]
    ];

    protected $fillable = ['start_time','end_time','teacher_id','hours','teaching_form'];

    public $rules = [
        'start_time'=>'date|before:end_time',
        'end_time'=>'date',
        'teacher_id' => 'exists:train_teachers,id',
        'hours' => 'numeric',
        'teaching_form' =>'between:2,200'
    ];

    public $attributeNames = [
        'start_time'=>'授课开始时间',
        'end_time'=>'授课结束时间',
        'teacher_id' => '授课教师',
        'hours' => '授课学时',
        'teaching_form' =>'授课方式'
    ];

    public function getCourseTeacherNameAttribute()
    {
        return $this->teacher->name;
    }


    public function beforeSave()
    {
        $this->teahcer_id = request('pivot[teacher]');
    }
}