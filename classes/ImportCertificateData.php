<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-13
 * Time: 下午10:31
 */

namespace Samubra\Train\Classes;

use DB;
use Ramsey\Uuid\Uuid;
use Auth;
use Samubra\Train\Models\Certificate;
use Validator;

class ImportCertificateData
{
    public function fire($job, $data)
    {
        foreach ($data as $row => $value) {
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
                        'is_valid' => $value['is_valid'],
                    ]);
            }else{
                $user = $this->getLoginUser($value);
                $recordData = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'user_id' => $user->id,
                    'type_id' => $value['type_id'],
                    'first_get_date' => $value['first_get_date'],
                    'print_date' => $value['print_date'],
                    'is_valid' => $value['is_valid'],
                    'phone' => $value['phone'],
                    'address' => $value['address'],
                    'company' => $value['company'],
                    'remark' => $value['remark']
                ];
                Certificate::create($recordData);
            }
        }

        $job->delete();
    }

    protected function getLoginUser($data)
    {
        if($user = Auth::findUserByLogin($data['identity']))
            return $user;
        else
            return Auth::register([
                'email'=>$data['identity'].'@site.com',
                'username'=>$data['identity'],
                'password'=>substr($data['identity'],-6),
                'password_confirmation'=>substr($data['identity'],-6),
                'phone'=>$data['phone'],
                'name'=>$data['name'],
                'surname'=>$data['name'],
                'identity'=>$data['identity'],
                'address' => $data['address'],
                'edu_id' => $data['edu_id'],
                'company' =>$data['company'],
            ],true,true);
    }
}
