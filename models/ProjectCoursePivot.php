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

    const TEACHING_TYPE_DIRECT = 'direct';
    const TEACHING_TYPE_SIMULATION = 'simulation';
    const TEACHING_TYPE_DEMOSTRATE = 'demonstrate';
    const TEACHING_TYPE_COSPLAY = 'cosplay';
    const TEACHING_TYPE_AUDIOVISUAL = 'audiovisual';
    const TEACHING_TYPE_DISCUSS = 'discuss';

    public static $teachingTypeMap = [
        self::TEACHING_TYPE_DIRECT => '讲授法',
        self::TEACHING_TYPE_SIMULATION => '模拟法',
        self::TEACHING_TYPE_DEMOSTRATE => '演示法',
        self::TEACHING_TYPE_COSPLAY => '角色扮演法',
        self::TEACHING_TYPE_AUDIOVISUAL => '网络视频教学',
        self::TEACHING_TYPE_DISCUSS => '小组讨论',
    ];

    public $dates = ['start','end'];
    protected $appends = ['course_teacher_name'];
    protected $jsonable = ['teaching_type'];
    public $belongsTo = [
        'teacher'=>[
            Teacher::class,
            'key'=>'teacher_id'
        ]
    ];

    protected $fillable = ['start','end','teacher_id','hours','teaching_type'];

    public $rules = [
        //'start'=>'date|before:end_time',
        //'end'=>'date',
        'teacher_id' => 'exists:samubra_train_teachers,id',
        'hours' => 'numeric',
        //'teaching_type' =>'between:2,200'
    ];

    public $attributeNames = [
        'start'=>'授课开始时间',
        'end'=>'授课结束时间',
        'teacher_id' => '授课教师',
        'hours' => '授课学时',
        'teaching_type' =>'授课方式'
    ];

    public function getCourseTeacherNameAttribute()
    {
        return $this->teacher->name;
    }

    public function getTeachingTypeOptions()
    {
        return self::$teachingTypeMap;
    }

    public function getTeachingTypeTextAttribute()
    {
        $type = self::$teachingTypeMap;
        return $type[$this->teaching_type];
    }


}