<?php namespace Samubra\Train;

use System\Classes\PluginBase;
use Illuminate\Support\Facades\Validator;
use App;
use Event;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Samubra\Train\Models\Certificate;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
        Validator::extend('identity', function($attribute, $value, $parameters, $validator) {
            return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
        });
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/',$value);
        });

    }
    public function boot()
    {

        UserModel::extend(function($model){
            $model->hasMany['certificates'] = [\Samubra\Train\Models\Certificate::class,'key'=>'user_id'];
            $model->hasMany['certificates_count'] = [\Samubra\Train\Models\Certificate::class,'key'=>'user_id','count'=>true];
            $model->addFillable([
                'identity',
                'phone',
                'address',
                'edu_type',
                'company',
                'tax_number'
            ]);

            //$model->rules['identity'] = 'identity|unique:users';
            //$model->rules['phone']= 'phone';
            //$model->rules['address']  ='between:2,200';
            
            $model->attributeNames['identity'] = '培训学员身份证号';
            $model->attributeNames['phone']= '培训学员联系电话';
            $model->attributeNames['address']  ='培训学员联系地址';
            $model->attributeNames['edu_type']  ='培训学员文化程度';
            $model->attributeNames['company']  ='培训学员工作单位名称';
            $model->attributeNames['tax_number']  ='纳税识别号';
        });

        UsersController::extendListColumns(function($list,$model){
            if(!$model instanceof UserModel)
                return ;
            
            $list->addColumns([
                'identity' =>[
                    'label' => '身份证号码',
                    'type' => 'text',
                    'searchable' => true,
                    'invisible' => true
                ],
                'phone' =>[
                    'label' => '联系电话',
                    'type' => 'text',
                    'searchable' => true,
                    'invisible' => true
                ],
                'company' =>[
                    'label' => '工作单位',
                    'type' => 'text',
                    'searchable' => true,
                    'invisible' => true
                ],
                'tax_number' =>[
                    'label' => '纳税识别号',
                    'type' => 'text',
                    'searchable' => true,
                    'invisible' => true
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
                'edu_type' => [
                    'label' =>  '文化程度',
                    'span' => 'auto',
                    'required' => '1',
                    'type' => 'dropdown',
                    'options' => Certificate::$eduTypeMap,
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
                'tax_number' => [
                    'label' =>  '纳税识别号',
                    'span' => 'auto',
                    'default' => '个体',
                    'type' => 'text',
                    'tab'   => '培训信息',
                ],
            ]);
        });
    }
}
