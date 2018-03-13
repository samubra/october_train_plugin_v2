<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|between:2,200',
        'complete_type' => 'required|in:graduation,training_certificate,operations_certificate',
        'validity' => 'nullable|numeric',
        'unit' => 'nullable|in:Y,m,d',
        'organ' => 'nullable'
    ];

    public $attributeNames = [
        'name' => '分类名称',
        'complete_type' => '结业类型',
        'validity' => '有效期限',
        'unit' => '有效期限单位',
        'organ' => '发证机关'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_categories';

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

    public function scopeDepth($query)
    {
        return $query->where('nest_depth',0);
    }
}
