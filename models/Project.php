<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Project extends Model
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
    public $table = 'samubra_train_projects';

    const STATUS_ACCEPT_APPLICATION = 'accept_application';
    const STATUS_END_APPLICATION = 'end_application';
    const STATUS_START_TRAINING = 'start_training';
    const STATUS_END_TRAINING = 'end_training';
    const STATUS_ARCHIVE = 'archive';

    public static $statusMap = [
        self::STATUS_ACCEPT_APPLICATION => '接受报名申请',
        self::STATUS_END_APPLICATION => '停止报名申请',
        self::STATUS_START_TRAINING => '开始培训',
        self::STATUS_END_TRAINING => '培训结束',
        self::STATUS_ARCHIVE => '培训存档',
    ];
    
    protected $fillable = [
        'title','plan_id','status','on_apply','start_date','end_date','exam_date','end_apply_date','price','remark','certificate_filter',
    ];

    protected $casts = [
        'on_apply' => 'boolean',
        'certificate_filter' => 'array',
    ];
    protected $dates = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'exam_date' => 'date:Y-m-d',
        'end_apply_date' => 'date:Y-m-d H:i',
    ];

    public $belongsTo = [
        'plan' => [
            Plan::class,
            'key' => 'plan_id'
        ]
    ];
    public $belongsToMany = [
        'courses' => [
                Course::class,
                'table' => 'samubra_train_project_courses',
                'key'      => 'project_id',
                'otherKey' => 'course_id',
                'pivot' => ['start','end','teacher_id','hours','teaching_type'],
                'timestamps' => true,
                'pivotModel'=>ProjectCoursePivot::class,
            ]
    ];

    public function getCoursesCountAttribute()
    {
        return $this->courses()->sum('hours').'学时';
    }

    public function getStatusOptions()
    {
        return self::$statusMap;
    }

    public function getStatusTextAttribute()
    {
        $list = self::$statusMap;
        return $list[$this->status];
    }
}
