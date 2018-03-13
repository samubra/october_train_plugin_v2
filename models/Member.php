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

    protected $dates = ['deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'member_name'    => 'required|between:2,255',
        'member_identity'=>'required|identity|unique:samubra_train_members',
        'member_phone'=>'nullable|phone',
        'member_address'  =>'nullable|between:3,200',
        'member_edu_id'  =>'nullable|exists:samubra_train_lookups,id',
        'member_company'  =>'nullable',
        //'member_user_id'    => 'nullable',
        'avatar' => 'nullable|max:300'
    ];

    public $attributeNames = [
        'member_name'    => '培训学员姓名',
        'member_identity'=>'培训学员身份证号',
        'member_phone'=>'培训学员联系电话',
        'member_address'  =>'培训学员联系地址',
        'member_edu_id'  =>'培训学员文化程度',
        'member_company'  =>'培训学员工作单位名称',
        'avatar'   => '培训学员照片',
    ];

    public $fillable = ['member_name','member_identity','member_phone','member_address','member_edu_id','member_user_id','member_company'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_members';

    //protected $appends = ['name_and_identity','type_text'];

    public $belongsTo = [
        'member_edu' => [
            Lookup::class,
            'key'=>'member_edu_id',
            'scope'=>'edu'
        ],
        'member_user' => [
            User::class,
            'key'=>'member_user_id'
        ]
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];
    public $hasMany = [
        'records'=>[
            Record::class,
            'key'=>'member_id'
        ],
        'records_count'=>[
            Record::class,
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
