<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    
    protected $dates = ['created_at','updated_at','deleted_at'];
    /**
     * @var array Validation rules
     */
    public $rules = [
        'title'=>'required|between:2,200',
        'type'=>'required|in:theory,operate',
    ];

    public $attributeNames = [
        'title' => '课程标题',
        'type' => '课程类型'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'train_courses';

    public $belongsToMany = [
        'projects' => [
            Project::class,
            'table'    => 'train_course_project',
            'otherKey'      => 'project_id',
            'key' => 'course_id',
            'timestamps' => true,
            'pivotModel'=>ProjectCoursePivot::class,
            'pivot' => ['start_time', 'end_time','teacher_id','hours','teaching_form']
        ]
    ];

    protected $appends = ['course_type_text'];

    public static function getTypeOptions()
    {
        return [
            'theory'=>'理论课',
            'operate'=>'操作课'
        ];
    }

    public function getCourseTypeTextAttribute()
    {

        $list = self::getTypeOptions();
        //$list = [
        //    'theory'=>'理论课',
        //    'operate'=>'操作课'
        //];
        return isset($list[$this->type])?$list[$this->type]:$this->type;

    }



}
