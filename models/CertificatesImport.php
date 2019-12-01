<?php namespace Samubra\Train\Models;

use \Backend\Models\ImportModel;
use Samubra\Train\Classes\ImportCertificateData;
use Queue;
use DB;
use Auth;
use Samubra\Train\Models\Certificate;
use Validator;
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午2:34
 */

class CertificatesImport extends ImportModel
{

    /**
     * Called when data is being imported.
     * The $results array should be in the format of:
     *
     *    [
     *        'db_name1' => 'Some value',
     *        'db_name2' => 'Another value'
     *    ],
     *    [...]
     *[
    "id" => "1"
    "member_name" => "杨云"
    "member_identity" => "512228197303140029"
    "type" => "3"
    "edu" => "14"
    "phone" => "15978917379"
    "address" => "城厢镇南山坡21号附1号"
    "company" => "远大公司刘家沟电站"
    "first_get_date" => "2011-01-21"
    "print_date" => "2017-01-03"
    "is_valid" => "1"
    "remark" => "["479691"]"
    ] 
     * 'member_name'=>'','member_identity'=>'','member_phone'=>'','member_address'=>'','member_edu_id'=>'','member_user_id'=>'','member_company'=>''
     * 'member_id'=>'','type_id'=>'','edu_id'=>'','first_get_date'=>'','print_date'=>'','is_valid'=>'','phone'=>'','address'=>'','company'=>'','remark'
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        //dd($results);
        try {
            foreach ($results as $value) {
                //Queue::push(ImportCertificateData::class, $value);
                $this->insertData($value);
                $this->logCreated();
            }
        }
        catch (\Exception $ex) {
            $this->logError(1, $ex->getMessage());
        }
    }
    protected function insertData($value)
    {
        trace_sql();
        //foreach ($data as $row => $value) {
            if(isset($value['certificate_id']) && isset($value['project_id'])) {
                Db::table('train_certificate_project')
                    ->where('certificate_id',$value['certificate_id'])
                    ->where('project_id',$value['project_id'])
                    ->update([
                        'phone' => $value['phone'],
                        'address' => $value['address'],
                        'company' => $value['company'],
                        'remark' => $value['remark'],
                        'status_id' => $value['status_id'],
                        'theory_score' => $value['theory_score'],
                        'operate_score' => $value['operate_score'],
                        'pay' => $value['pay'],
                    ]);
                Db::table('train_certificates')
                    ->where('id',$value['certificate_id'])
                    ->update([
                        'phone' => $value['phone'],
                        'address' => $value['address'],
                        'company' => $value['company'],
                        'print_date' => $value['print_date'],
                        'is_valid' => [$value['is_valid']],
                    ]);
            }else{
                $user = $this->getLoginUser($value);
                if (!Db::table('train_certificates')->where('user_id', $user->id)->where('type_id', $value['type_id'])->where('is_valid', $value['is_valid'])->count()) {
                    $recordData = [
                   // 'uuid' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'type_id' => $value['type_id'],
                    'first_get_date' => $value['first_get_date'],
                    'print_date' => $value['print_date'],
                    'is_valid' => $value['is_valid'],
                    'phone' => $value['phone'],
                    'address' => $value['address'],
                    'company' => $value['company'],
                    //'remark' => [$value['remark']]
                ];
                
                    DB::table('train_certificates')->insert($recordData);
                    //trace_log($recordData);
                    Auth::logout();
                }
            }
        //}
        //$job->delete();
    }
    protected function getLoginUser($data)
    {
        if (Auth::check()) {
            return Auth::getUser();
        } elseif ($user = Auth::findUserByLogin($data['identity'])) {
            return $user;
        }else{
            return Auth::register([
                'email'=>$data['identity'].'@site.com',
                'username'=>$data['identity'],
                'password'=>substr($data['identity'], -8),
                'password_confirmation'=>substr($data['identity'], -8),
                'phone'=>$data['phone'],
                'name'=>$data['name'],
                'surname'=>$data['name'],
                'identity'=>$data['identity'],
                'address' => $data['address'],
                'edu_id' => $data['edu_id'],
                'company' =>$data['company'],
            ], true, true);
        }
    }
}
