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

}
