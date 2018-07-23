<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-7-21
 * Time: 下午3:13
 */

namespace Samubra\Train\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Samubra\Train\Classes\CanApply;
use Samubra\Train\Models\Certificate;
use Samubra\Train\Models\Project;
use Samubra\Train\Models\Lookup;
use Validator;
use Redirect;
use Auth;
use ApplicationException;
use Lang;
use Input;
use Request;
use ValidationException;
use Exception;
use AjaxException;

class ApplyForm extends ComponentBase
{
    public $submitPage = null;
    public $project_id = null;
    public $projectModel = null;

    public function componentDetails()
    {
        return [
            'name' => '培训申请',
            'description' => '填写培训申请表单。'
        ];
    }

    public function defineProperties()
    {
        return [
            'projectId'    => [
                'title' => '培训项目ID',
                'description' => 'URL中传入培训项目的标识符',
                'type'  =>  'string',
                'default' => '{{ :project_id }}',
                'validation' => [
                    'required' => ['message' => '必须填写']
                ],
            ],
            'submitPage' => [
                'title' => '跳转页面',
                'description' => '提交成功后跳转的页面',
                'type' => 'dropdown',
                'showExternalParam' => false
            ],
        ];
    }

    public function getSubmitPageOptions()
    {
        $pages = Page::sortBy('baseFileName')->lists('baseFilename','baseFileName');
        $pages = [
            '-' => '无提交页面'
        ]+$pages;

        return $pages;
    }

    /**
     * 页面开始运行时，执行该方法
     * @return $this|void
     */
    public function onRun()
    {
        $this->prepareVars();//加载初始数据
        $can_apply = new CanApply($this->projectModel);
        if(!( $can_apply->checkProjectEndApplyDate()
            &&
            $this->projectModel->can_apply)) //检查当前培训项目是否过了最后申请时间和项目是否允许申请
            return redirect()->back()->withErrors('该培训项目已过期或不允许申请培训！');
    }

    public function onLoadCertificates()
    {
        //<!--data-request-update="'{{ __SELF__ }}::certificates_list': '#result'" data-request-success="$('#identity').val('');console.log(data)"-->
        try{
            $postData = ['identity' => post('identity')];
            $rules = [
                'identity' => ['required','identity']
            ];
            $messages = [
                'identity'=> '身份证号码格式不正确。',
                'required' => '身份证号码必须填写。'
            ];
            $validation = Validator::make($postData,$rules,$messages);
            if( $validation->fails()) {
                throw new ValidationException($validation);
            }else{
                $this->prepareVars();
                $this->findCertificates();
                $this->page['new_record'] = $this->new_record;

            }
        }catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
        
    }
    public function onLoadCertificate()
    {
        $this->prepareVars();
        $certificateModel = Certificate::with('user')->find(post('id'));
        $checkApply = new CanApply($this->projectModel,$certificateModel);
        if(!$checkApply->check())
            throw new ApplicationException('你所选择的证书不符合当前培训项目的要求，请重新选择！');
        $this->page['certificateModel'] = $certificateModel;
    }
    public function onSaveApply()
    {
        try {

            $apply = post();
            $rules = [
                'identity' => 'required|identity',
                'name' => 'required|min:2',
                'phone' => 'required|phone',
                'edu_id' => 'required|exists:train_lookups,id',
                'address' => 'required',
                'company' => 'required',
            ];
            $messages = [
                'required' => '必须填写',
                'phone' => '电话号码格式不正确',
                'identity' => '身份证号码格式不正确',
                'exists' => '你所选择的值不存在',
                'min' => '姓名至少需要2个字'
            ];
            //var_dump($apply);
            $validation = Validator::make($apply,$rules,$messages);
            if( $validation->fails()) {
                throw new ValidationException($validation);
            }else{
                $user = $this->getLoginUser();
                $this->prepareVars();
                $pivotData = [
                    'phone' => $apply['phone'],
                    'address' => $apply['address'],
                    'company' => $apply['company'],
                ];
                if(!isset($apply['certificate_id']) && $this->projectModel->plan->is_new){
                    $certificateModel = $user->certificates()->create([
                        'type_id' => $apply['type_id'],
                        'first_get_date' => null,
                        'print_date' => null,
                        'is_valid' => false,
                        'user_id' => $user->id
                    ] + $pivotData);
                }else{
                    $certificateModel = Certificate::with('user','type','user.edu')
                        ->find
                    ($apply['certificate_id']);
                }
                $this->projectModel->certificates()->add($certificateModel,$pivotData);
                $this->page['certificateModel'] = $certificateModel;
            }
            //else
            //throw new ApplicationException('你填写的部分内容不符合要求，请仔细阅读培训项目相关内容并按照实际要求填写！');
        }catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }


    protected function prepareVars()
    {
        $submitPage = $this->property('submitPage');
        if ($submitPage == '-') {
            $submitPage = null;
        }
        $this->submitPage = $this->page['submitPage'] = $submitPage;
        $this->project_id = $this->page['project_id'] = $this->property('projectId');
        $this->loadProject();
        $this->loadEduOptions();
    }

    protected function loadProject( $project_id = null )
    {
        if(!is_null($project_id))
            $this->project_id = $project_id;
        $this->projectModel = $this->page['projectModel'] = Project::with('status','plan','certificates_count','certificates')->findOrFail($this->project_id);


    }
    protected function saveCertificate()
    {
        try{

            $user = $this->getLoginUser();
            //trace_sql();
            if(!$user)
                throw new ApplicationException('没有你的操作证，请先用身份证号码注册再查询~');

            $certificateModel = new Certificate();
            $certificateModel->name = post('name');
            $certificateModel->identity = post('identity');
            $certificateModel->type_id = post('type_id');
            $this->page['certificateModel'] = $certificateModel->save();
            $this->new_record = true;
        }catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }

    protected function getLoginUser()
    {
        trace_sql();
        if(Auth::check()) {
            return Auth::getUser();
        }elseif($user = Auth::findUserByLogin(post('identity'))){
                return $user;
        }elseif(array_key_exists('name', post())){
            return Auth::register([
                'email'=>post('identity').'@site.com',
                'username'=>post('identity'),
                'password'=>substr(post('identity'),-6),
                'password_confirmation'=>substr(post('identity'),-6),
                'phone'=>post('phone'),
                'name'=>post('name'),
                'surname'=>post('name'),
                'identity'=>post('identity'),
                'address' => post('address'),
                'edu_id' => post('edu_id'),
                'company' =>post('company'),
            ],true,true);
        }
    }
    protected function findCertificates()
    {
        try {
            $user = $this->getLoginUser();
            //trace_sql();
            if(!$user)
                throw new ApplicationException('没有你的操作证，请先用身份证号码注册再查询~');
            $query = Certificate::where('user_id', $user->id)->where('type_id', post('type_id'));
            if($this->projectModel->certificates_count)
                $query->whereNotIn('id', $this->projectModel->certificates->lists('id'));
            $certificates = $query->with('type','user')->get();
            if ($certificates->count()) {
                $this->page['certificates'] = $certificates;
            } else {
                throw new ApplicationException('没有找到你的可以申请该培训项目的操作证，或你已申请培训，请勿重复操作！');
            }
        }catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }
    protected function loadEduOptions()
    {
        $this->page['eduOptions'] = Lookup::edu()->lists('name','id');

    }

}