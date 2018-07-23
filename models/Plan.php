<?php namespace Samubra\Train\Models;

use Model;
use BackendAuth;

/**
 * Model
 */
class Plan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at','created_at','updated_at'];

    protected $jsonable = ['target','result','train_material','train_claim','train_remark'];

    public $fillable = ['type_id','create_user_id','is_new','target','result','train_material','train_claim','operate_hours','theory_hours','address','contact_person','contact_phone','train_remark','title','content'];

    public $guarded = ['parent_type'];

    protected $casts = [
        'is_new' => 'boolean',
    ];
    /**
     * @var array Validation rules
     */
    public $rules = [
        'type_id'=>'required|exists:train_categories,id',
        'is_new'=>'boolean',
        'target'=>'nullable',
        'result'=>'nullable',
        'material'=>'nullable',
        'claim'=>'nullable',
        'operate_hours'=>'numeric',
        'theory_hours'=>'numeric',
        'address'=>'nullable|between:3,255',
        'contact_person'=>'nullable',
        'contact_phone'=>'nullable|phone',
        'title'=>'required|between:4,255',
    ];

    public $attributeNames = [
        'type_id'=>'操作项目',
        'is_new'=>'是否新训',
        'target'=>'培训对象',
        'result'=>'培训目的',
        'material'=>'使用教材',
        'claim'=>'培训要求',
        'operate_hours'=>'操作学时',
        'theory_hours'=>'理论学时',
        'address'=>'培训地址',
        'contact_person'=>'联系人',
        'contact_phone'=>'联系电话',
        'title'=>'培训方案标题',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'train_plans';

    public $belongsTo = [
        'type' =>[
            Category::class,
            'key'=>'type_id',

        ]
    ];



    
    public function beforeSave()
    {
        return $this->ignore['parent_type'];
    }
    public function beforeCreate()
    {
        if(BackendAuth::check())
           $this->create_user_id = BackendAuth::getUser()->id;
    }

    public function getParentTypeOptions()
    {
        return Category::parentList()->lists('title','id');
    }

    public function getTypeIdOptions()
    {
        if($this->type || $this->parent_type){
            $parent_id = $this->parent_type?$this->parent_type:$this->type->parent_id;
            return Category::parentList($parent_id)->lists('title', 'id');
        }else{
            return [];
        }
    }


}
