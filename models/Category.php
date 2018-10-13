<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;

    const PARENT_ID = 'parent_id';
    const NEST_LEFT = 'lft';
    const NEST_RIGHT = 'rgt';
    const NEST_DEPTH = 'depth';


    /**
     * @var array Validation rules
     */
    public $rules = [
        'organ_id' => 'exists:samubra_train_organs,id',
        'name' => 'required|min:2'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_categories';

    protected $guarded = array('id', 'parent_id', 'lid', 'rid', 'depth');

    public $belongsTo = [
        'organ' => [
            Organ::class,
            'key' => 'organ_id',
        ]
    ];

    protected $fillable = [
        'organ_id',
        'name',
        'description'
    ];

    public function scopeDepth($query,$depth)
    {
        return $query->where('depth',$depth);
    }

    public function scopeParentList($query,$parent_id = null)
    {
        if(is_null($parent_id))
            return $query->whereNull(self::PARENT_ID);
        else
            return $query->where(self::PARENT_ID,$parent_id);
    }
}
