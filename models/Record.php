<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Record extends Model
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
    public $table = 'samubra_train_records';

    protected $fillable = [
        'order_id',
        'certificate_id',
        'project_id',
        'health',
        'status',
        'profile',
        'amount',
        'price',
        'theory_score',
        'operate_score',
        'remark'
    ];

    protected $casts = [
        'closed'    => 'boolean',
        'profile'     => 'json',
    ];


    public $belongsTo = [
        'order' => [
            Order::class,
            'key' => 'order_id'
        ],
        'certificate' => [
            Certificate::class,
            'key' => 'certificate_id'
        ],
        'project' => [
            Project::class,
            'key' => 'project_id'
        ],
    ];

    const HEALTH_INCONSISTENT = 'inconsistent';
    const HEALTH_QUALIFIED = 'qualified';
    const HEALTH_NO_NEED = 'no_need';
    const HEALTH_FAILED = 'failed';

    const STATUS_WAITING_FOR_ACCEPTANCE = 'waiting_for_acceptance';
    const STATUS_ACCEPTING = 'accepting';
    const STATUS_SUCCESSFUL_ACCEPTANCE = 'successful_acceptance';
    const STATUS_WITHDRAWAL = 'withdrawal';

    public static $healthMap = [
        self::HEALTH_INCONSISTENT    => '无结论性意见',
        self::HEALTH_QUALIFIED    => '体检合格',
        self::HEALTH_NO_NEED => '无需体检',
        self::HEALTH_FAILED    => '体检不合格'
    ];

    public static $statusMap = [
        self::STATUS_WAITING_FOR_ACCEPTANCE => '等待受理',
        self::STATUS_ACCEPTING => '正在受理',
        self::STATUS_SUCCESSFUL_ACCEPTANCE => '受理成功',
        self::STATUS_ACCEPTANCE_FAILURE => '受理失败',
        self::STATUS_WITHDRAWAL => '退会',
    ];
}
