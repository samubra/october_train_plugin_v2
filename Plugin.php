<?php namespace Samubra\Train;

use System\Classes\PluginBase;
use Illuminate\Support\Facades\Validator;
use App;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;

use Samubra\Train\Models\Member as MemberModel;

class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];

    public function registerComponents()
    {
        return [
            'Samubra\Train\Components\ApplyForm' => 'applyform'
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Validator::extend('identity', function($attribute, $value, $parameters, $validator) {
            return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
        });
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            //return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/',$value);
		return preg_match('/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|191|(147))\\d{8}$/',$value);
        });

        UserModel::extend(function($model){
            $model->belongsTo['edu'] = [\Samubra\Train\Models\Lookup::class,'key'=>'edu_id','scope'=>'edu'];
            $model->hasMany['certificates'] = [\Samubra\Train\Models\Certificate::class,'key'=>'user_id'];
            $model->hasMany['certificates_count'] = [\Samubra\Train\Models\Certificate::class,'key'=>'user_id','count'=>true];
            $model->addFillable([
                'identity',
                'phone',
                'address',
                'edu_id',
                'company',
            ]);

            $model->rules['identity'] = 'identity|unique:users';
            //$model->rules['phone']= 'phone';
            //$model->rules['address']  ='between:2,200';
            $model->rules['edu_id']  ='required|exists:train_lookups,id';
            
            $model->attributeNames['identity'] = '培训学员身份证号';
            $model->attributeNames['phone']= '培训学员联系电话';
            $model->attributeNames['address']  ='培训学员联系地址';
            $model->attributeNames['edu_id']  ='培训学员文化程度';
            $model->attributeNames['company']  ='培训学员工作单位名称';
        });

        UsersController::extendListColumns(function($list,$model){
            if(!$model instanceof UserModel)
                return ;

            $list->addColumns([
                'identity' =>[
                    'label' => '身份证号码',
                    'type' => 'text',
                    'searchable' => true,
                ],
                'phone' =>[
                    'label' => '联系电话',
                    'type' => 'text',
                    'searchable' => true,
                ],
                'company' =>[
                    'label' => '工作单位',
                    'type' => 'text',
                    'searchable' => true,
                ],
            ]);
        });
        UsersController::extendFormFields(function($form,$model,$context){
            
            if(!$model instanceof UserModel)
                return ;

            $form->addTabFields([
                'identity' => [
                    'label' => '身份证号码',
                    'tab'   => '培训信息',
                    'span' => 'auto',
                    'required' => '1',
                    'type' => 'text',
                ],
                'phone' => [
                    'label' =>  '联系电话',
                    'span' => 'auto',
                    'required' => '1',
                    'type' => 'text',
                    'tab'   => '培训信息',
                ],
                'edu' => [
                    'label' =>  '文化程度',
                    'span' => 'auto',
                    'nameFrom' => 'name',
                    'required' => '1',
                    'type' => 'relation',
                    'tab'   => '培训信息',
                ],
                'address' => [
                    'label' =>  '联系地址',
                    'span' => 'auto',
                    'default' => '重庆市',
                    'type' => 'text',
                    'tab'   => '培训信息',
                ],
                'company' => [
                    'label' =>  '工作单位',
                    'span' => 'auto',
                    'default' => '个体',
                    'type' => 'text',
                    'tab'   => '培训信息',
                ],
            ]);
        });
    }

    public function registerReportWidgets()
    {
        return [
            'Samubra\Train\ReportWidgets\CertificatesCount' => [
                'label'   => '培训证书统计',
                'context' => 'dashboard'
            ],
            'Samubra\Train\ReportWidgets\CertificatesReport' => [
                'label'   => '培训证书报告',
                'context' => 'dashboard'
            ]
        ];
    }
}
