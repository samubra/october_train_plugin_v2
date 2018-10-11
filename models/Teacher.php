<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Teacher extends Model
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
    public $table = 'samubra_train_teachers';

    public $attachOne = [
        'photo' => 'System\Models\File'
    ];

    protected $fillable = [
        'name',
        'identity',
        'qualification_number',
        'job_title',
        'phone',
        'edu_type',
        'company',
        'photo'
    ];

    protected $appends = [
        'job_title_text',
        'edu_type_text'
    ];

    const JOB_TITLE_NONE = 'none';
    const JOB_TITLE_ELEMENTARY = 'elementary';
    const JOB_TITLE_INTERMEDIATE = 'intermediate';
    const JOB_TITLE_HIGH_GRADE = 'high_grade';

    public static $jobTitleMap = [
        self::JOB_TITLE_NONE => '无',
        self::JOB_TITLE_ELEMENTARY => '初级',
        self::JOB_TITLE_INTERMEDIATE => '中级',
        self::JOB_TITLE_HIGH_GRADE => '高级',
    ];

    public function getJobTitleOptions()
    {
        return self::$jobTitleMap;
    }

    public function getJobTitleTextAttribute()
    {
        return self::$jobTitleMap[$this->job_title];
    }

    public function getEduTypeOptions()
    {
        return Certificate::$eduTypeMap;
    }

    public function getEduTypeTextAttribute()
    {
        return Certificate::$eduTypeMap[$this->edu_type];
    }
}
