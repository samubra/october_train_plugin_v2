<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_courses';

    public $belongsTo = [
        'defaultTeacher' => Teacher::class,
        'key' => 'default_teacher_id'
    ];

    protected $fillable = [
        'title',
        'course_type',
        'default_teacher_id',
        'default_hours',
    ];

    const COURSE_TYPE_THEORY = 'theory';
    const COURSE_TYPE_OPERATE = 'operate';

    public static $courseTypeMap = [
        self::COURSE_TYPE_OPERATE => '操作课',
        self::COURSE_TYPE_THEORY => '理论课',
    ];

    public function getCourseTypeOptions()
    {
        return self::$courseTypeMap;
    }

    public function getCourseTypeTextAttribute()
    {
        $type = self::$courseTypeMap;
        return $type[$this->course_type];
    }
}
