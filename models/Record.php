<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Record extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at','first_get_date','print_date'];

    protected $casts = [
        'is_valid' => 'boolean',
    ];
    protected $jsonable = ['remark'];
    public $fillable = ['import_id','member_id','type_id','edu_id','first_get_date','print_date','is_valid','phone','address','company','remark'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'member_id'=>'required|exists:samubra_train_members,id',
        'type_id'=>'required|exists:samubra_train_categories,id',
        'edu_id'=>'required|exists:samubra_train_lookups,id',
        'first_get_date'=>'nullable|date|before:print_date',
        'print_date'=>'nullable|date|after:first_get_date',
        'is_valid'=>'boolean',
        'phone'=>'nullable|phone',
        'address'=>'nullable',
        'company'=>'nullable',
        'remark'=>'nullable'
    ];
    public $attributeNames = [
        'member_id'=>'培训学员',
        'type_id'=>'培训类别',
        'edu_id'=>'文化程度',
        'first_get_date'=>'初领证日期',
        'print_date'=>'发证日期',
        'is_valid'=>'是否有效',
        'phone'=>'联系电话',
        'address'=>'联系地址',
        'company'=>'工作单位',
        'remark'=>'备注'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_records';
    protected $appends = ['records_member_name','record_type'];
    public $belongsTo = [
        'member'=>[
            Member::class,
            'key'=>'member_id'
        ],
        'type'=>[
            Category::class,
            'key'=>'type_id',
        ],
        'edu'=>[
            Lookup::class,
            'key'=>'edu_id',
            'scope'=>'edu'
        ]
    ];

    public $hasMany = [
        'applies'=>[
            Apply::class,
            'key'=>'record_id'
        ],
        'applies_count'=>[
            Apply::class,
            'key'=>'record_id',
            'count'=>true
        ]
    ];

    public function getRecordsMemberNameAttribute()
    {
        return $this->member->member_name.'('.$this->member->member_identity.')';
    }
    public function getRecordTypeAttribute()
    {
        $type = Category::find($this->type_id);
        return $type->name;
    }

    public function scopeFirstGetDate($query,$date)
    {
        if(is_array($date))
        {
            if(isset($date['after']))
                $query->where('first_get_date','>=',$date['after']);
            if(isset($date['before']))
                $query->where('first_get_date','<=',$date['before']);
        }else{
            $query->where('first_get_date',$date);
        }

        return $query;
    }

    public function scopePrintDate($query,$date)
    {
        if(is_array($date))
        {
            if(isset($date['after']))
                $query->where('print_date','>=',$date['after']);
            if(isset($date['before']))
                $query->where('print_date','<=',$date['before']);
        }else{
            $query->where('print_date',$date);
        }

        return $query;
    }
    public function scopeType($query,$type)
    {
        return $query->where('type_id',$type);
    }
    public function scopeEdu($query,$edu)
    {
        return $query->where('edu_id',$edu);
    }
    public function scopeIsValid($query,$is_valid)
    {
        return $query->where('is_valid',$is_valid);
    }
    public function scopeCompany($query,$company)
    {
        return $query->where('company','like',$company);
    }

    public function scopeExport($query,$conditions)
    {
        foreach ($conditions as $key=>$condition)
        {
            $query->$key($condition);
        }
        return $query;
    }
}
