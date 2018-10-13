<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Plan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    protected $dates = ['created_at','updated_at'];

    protected $jsonable = ['material','claim','document','other'];

    public $fillable = ['title','category_id','is_new','operate_hours','theory_hours','address','contact_name','contact_phone','target','result','material','claim','document','other'];

    public $guarded = ['parent_type'];

    protected $casts = [
        'is_new' => 'boolean',
    ];
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_plans';
    public $belongsTo = [
        'category' =>[
            Category::class,
            'key'=>'category_id',

        ]
    ];

    public function getParentTypeOptions()
    {
        return Category::parentList()->lists('name','id');
    }

    public function beforeSave()
    {
        return $this->ignore['parent_type'];
    }
    public function getCategoryIdOptions()
    {
        if($this->category || $this->parent_type){
            $parent_id = $this->parent_type?$this->parent_type:$this->category->parent_id;
            return Category::parentList($parent_id)->lists('name', 'id');
        }else{
            return [];
        }
    }
}
