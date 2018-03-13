<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Lookup extends Model
{
    use \October\Rain\Database\Traits\Validation;

    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'sort' => 'nullable|numeric',
        'display_name' => 'required|between:2,255',
        'lookup_type' => 'required|between:2,255'
    ];

    public $attributeNames = [
        'sort' => '排序',
        'display_name'=>'显示名称',
        'lookup_type' => '类别'
    ];



    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_lookups';

    const typeList = [
        'plan_status'   =>  '培训计划状态',
        'apply_status'  =>  '培训申请受理状态',
        'edu_type'      =>  '文化程度',
        'health_type' =>  '健康状况',
        'teacher_type' => '教师类别'
    ];

    public static function getLookupTypeOptions()
    {
        return self::typeList;
    }

    public function scopeType($query,$type)
    {
        if(is_array($type))
            return $query->whereIn('lookup_type', $type)->orderBy('sort');
        return $query->where('lookup_type',$type);
    }

    public function scopeEdu($query)
    {
        return $query->type('edu_type');
    }

    public function scopePlanStatus($query)
    {
        return $query->type('plan_status');
    }
    public function scopeHealth($query)
    {
        return $query->type('health_type');
    }
    public function scopeApplyStatus($query)
    {
        return $query->type('apply_status');
    }

    public static function getOptionList($type)
    {
        return self::type($type)->orderBy('sort')->lists('display_name','id');
    }

    public static function getHealthOptions()
    {
        return self::getOptionList('health_type');
    }
    public static function getStatusOptions()
    {
        return self::getOptionList('apply_status');
    }
    public static function getEduOptions()
    {
        return self::getOptionList('edu_type');
    }



}
