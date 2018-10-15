<?php namespace Samubra\Train\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class Certificate extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'identity' => 'required',
        'id_type' => 'required',
        'user_id' => 'required|exists:users,id',
        'category_id' => 'required|exists:samubra_train_categories,id',
        'organ_id' => 'required|exists:samubra_train_organs,id',
        'first_get_date' => 'required',
        'print_date' => 'required|after_or_equal:first_get_date',
        'is_valid' => 'boolean',
        'is_reviewed' => 'boolean',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_certificates';

    const TYPE_ID_CARD = 'id_card';
    const TYPE_PASSPORT = 'passport';
    const TYPE_OTHER = 'other';
    const TYPE_MILITARY_OFFICER_CARD = 'military_officer_card';

    const EDU_TYPE_ELEMENTAR_SCHOOL = 'elementary_school';
    const EDU_TYPE_JUNIOR_HIGH_SCHOOL = 'junior_high_school';
    const EDU_TYPE_SENIOR_HIGH_SCHOOL = 'senior_high_school';
    const EDU_TYPE_TECHNICAL_SECONDARY_SCHOOL = 'technical_secondary_school';
    const EDU_TYPE_JUNIOR_SCHOOL = 'junior_school';
    const EDU_TYPE_UNDERGRADUATE = 'undergraduate';
    const EDU_TYPE_POSTGRADUATE = 'postgraduate';
    const EDU_TYPE_BACHELOR = 'bachelor';
    const EDU_TYPE_MASTER = 'master';
    const EDU_TYPE_DOCTOR = 'doctor';

    public static $eduTypeMap = [
        self::EDU_TYPE_DOCTOR    => '博士',
        self::EDU_TYPE_MASTER    => '硕士',
        self::EDU_TYPE_BACHELOR    => '学士',
        self::EDU_TYPE_POSTGRADUATE => '研究生',
        self::EDU_TYPE_UNDERGRADUATE    => '本科',
        self::EDU_TYPE_JUNIOR_SCHOOL    => '大学专科',
        self::EDU_TYPE_TECHNICAL_SECONDARY_SCHOOL    => '中等专业学校',
        self::EDU_TYPE_SENIOR_HIGH_SCHOOL    => '高中',
        self::EDU_TYPE_JUNIOR_HIGH_SCHOOL    => '初中',
        self::EDU_TYPE_ELEMENTAR_SCHOOL    => '小学',
    ];

    public static $idTypeMap = [
        self::TYPE_ID_CARD    => '身份证',
        self::TYPE_PASSPORT    => '护照',
        self::TYPE_MILITARY_OFFICER_CARD => '军官证',
        self::TYPE_OTHER    => '其他',
    ];
    
    protected $casts = [
        'is_valid'    => 'boolean',
        'is_reviewed'    => 'boolean',
        'profile'     => 'json',
    ];

    protected $dates = [
        'first_get_date' => 'date:Y-m-d',
        'print_date' => 'date:Y-m-d',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'identity',
        'id_type',
        'user_id',
        'category_id',
        'organ_id',
        'first_get_date',
        'print_date',
        'is_valid',
        'is_reviewed',
        'profile',
    ];

    public $belongsTo = [
        'user' => [
            User::class,
            'key' => 'user_id'
        ],                                                                                                                  

        'category' => [
            Category::class,
            'key' => 'category_id'
        ],
        'organ' => [
            Organ::class,
            'key' => 'organ_id'
        ],

    ];

    protected $appends = [
        'id_type_text'
    ];

    public function getIdTypeOptions()
    {
        return self::$idTypeMap;
    }

    public function getIdTypeTextAttribute()
    {
        $type = self::$idTypeMap;

        return $type[$this->id_type];
    }
}
