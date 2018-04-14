<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;
    use \October\Rain\Database\Traits\SoftDelete;
    
    protected $dates = ['created_at','updated_at','deleted_at'];

    const PARENT_ID = 'parent_id';
    const NEST_LEFT = '_lft';
    const NEST_RIGHT = '_rgt';
    const NEST_DEPTH = 'depth';

    
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required|between:2,200',
        'complete_type' => 'required|in:graduation,training_certificate,operations_certificate',
        'validity' => 'nullable|numeric',
        'unit' => 'nullable|in:Y,m,d',
        'organ' => 'nullable'
    ];

    public $attributeNames = [
        'title' => '分类名称',
        'complete_type' => '结业类型',
        'validity' => '有效期限',
        'unit' => '有效期限单位',
        'organ' => '发证机关'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'train_categories';

    public function getCompleteTypeOptions()
    {
        return [
            'graduation' => '培训结业证',
            'training_certificate' => '培训合格证',
            'operations_certificate' => '特种作业操作证'
        ];
    }
    public function getUnitOptions()
    {
        return [
            'Y'=>'年',
            'm'=>'月',
            'd'=>'日',
        ];
    }

    public function scopeDepth($query,$depth = 0)
    {
        return $query->where(self::NEST_DEPTH,$depth);
    }
}
