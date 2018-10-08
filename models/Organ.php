<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Organ extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|min:2',
        'complete_type' => 'required',
        'need_review' => 'boolean',
        'validity' => 'required_if:need_review,1',
        'unit' => 'required_if:need_review,1',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_organs';

    const COMPLETE_TYPE_GRADUATION = 'graduation';
    const COMPLETE_TYPE_TRAINING = 'training_certificate';
    const COMPLETE_TYPE_OPERATIONS = 'operations_certificate';

    const UNIT_Y = 'Y';
    const UNIT_M = 'M';
    const UNIT_D = 'D';

    public static $completeTypeMap = [
        self::COMPLETE_TYPE_GRADUATION => '培训结业证',
        self::COMPLETE_TYPE_TRAINING => '培训合格证',
        self::COMPLETE_TYPE_OPERATIONS => '特种作业操作证'
    ];

    public static $unitMap = [
        self::UNIT_Y => '年',
        self::UNIT_M => '月',
        self::UNIT_D => '日',
    ];

    protected $fillable = [
        'name','complete_type','validity','unit','need_review'
    ];
    protected $casts = [
        'need_review' => 'boolean', // on_sale 是一个布尔类型的字段
    ];

    public $hasMany = [
        'categories' => Category::class
    ];

    public function getCompleteTextAttribute()
    {
        $type = $this->complete_type;
        return self::$completeTypeMap[$type];
    }

    public function getUnitTextAttribute()
    {
        if($this->need_review){
            $unit = $this->unit;
            return $this->validity . self::$unitMap[$unit];
        }else{
            return '不需要年审';
        }

    }

    public function getCompleteTypeOptions()
    {
        return self::$completeTypeMap;
    }

    public function getUnitOptions()
    {
        return self::$unitMap;
    }
}
