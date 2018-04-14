<?php namespace Samubra\Train\Models;

use Model;
use RainLab\User\Models\User;
use Samubra\Train\Classes\Identity;
use Samubra\Train\Classes\Phone;

/**
 * Model
 */
class Member extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    
    protected $dates = ['created_at','updated_at','deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'    => 'required|between:2,255',
        'identity'=>'required|identity|unique:train_members',
        'phone'=>'nullable|phone',
        'address'  =>'nullable|between:3,200',
        'edu_id'  =>'nullable|exists:train_lookups,id',
        'company'  =>'nullable',
        //'user_id'    => 'nullable',
    ];

    public $attributeNames = [
        'name'    => '培训学员姓名',
        'identity'=>'培训学员身份证号',
        'phone'=>'培训学员联系电话',
        'address'  =>'培训学员联系地址',
        'edu_id'  =>'培训学员文化程度',
        'company'  =>'培训学员工作单位名称',
        'avatar'   => '培训学员照片',
    ];

    public $fillable = ['name','identity','phone','address','edu_id','user_id','company'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'train_members';

    //protected $appends = ['name_and_identity','type_text'];

    public $belongsTo = [
        'edu' => [
            Lookup::class,
            'key'=>'edu_id',
            'scope'=>'edu'
        ],
        'user' => [
            User::class,
            'key'=>'user_id'
        ]
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];
    public $hasMany = [
        'certificates'=>[
            Certificate::class,
            'key'=>'member_id'
        ],
        'certificates_count'=>[
            Certificate::class,
            'key'=>'member_id',
            'count'=>true
        ]
    ];

    public function beforeCreate()
    {
        //if(Auth::check())
         //   $this->member_user_id = Auth::getUser()->id;
    }
}
