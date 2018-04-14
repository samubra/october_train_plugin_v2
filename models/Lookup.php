<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Lookup extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Sortable;

    const SORT_ORDER = 'order';
    protected $dates = ['created_at','updated_at','deleted_at'];
    
    /**
     * @var array Validation rules
     */
    public $rules = [
        'order' => 'nullable|numeric',
        'name' => 'required|between:2,255',
        'type' => 'required|between:2,255'
    ];

    public $attributeNames = [
        'order' => '排序',
        'name'=>'显示名称',
        'type' => '类别'
    ];



    /**
     * @var string The database table used by the model.
     */
    public $table = 'train_lookups';

    const typeList = [
        'project_status'   =>  '培训计划状态',
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
            return $query->whereIn('type', $type)->orderBy('order');
        return $query->where('type',$type);
    }

    public function scopeEdu($query)
    {
        return $query->type('edu_type');
    }

    public function scopeProjectStatus($query)
    {
        return $query->type('project_status');
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
        return self::type($type)->orderBy('order')->lists('name','id');
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
