<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-13
 * Time: 下午10:31
 */

namespace Samubra\Train\Classes;

use DB;
use Auth;
use Samubra\Train\Models\Certificate;
use Validator;

class ImportCertificateData
{
    public function fire($job, $value)
    {
        //trace_sql();
        //foreach ($data as $row => $value) {
            $user = $this->getLoginUser($value);
                $certificateData = [
                    'identity' => $value['identity'],
                    'user_id' => $user->id,
                    'category_id' => '1',
                    'is_valid' => $value['is_valid']
                ];
                $certificateModel = Certificate::firstOrNew($certificateData);

                $certificateModel->first_get_date = $value['first_get_date'];
                $certificateModel->print_date = $value['print_date'];
                $certificateModel->organ_id = '1';
                $certificateModel->is_reviewed = isset($value['is_reviewed'])?$value['is_reviewed']:false;

                $certificateModel->id_type = isset($value['id_type'])?$value['id_type']:Certificate::TYPE_ID_CARD;

                $certificateModel->profile = [
                    'phone' => $value['user_phone'],
                    'address' => $value['user_address'],
                    'company' => $value['user_company'],
                    'tax_number' => isset($value['user_tax_number'])? $value['user_tax_number']:'123',
                    'edu_type' => $value['user_edu_type'],
                ];
                

                $certificateModel->save();
                trace_log($certificateModel->id);
                unset($certificateModel);
                Auth::logout();

                /**
            if(isset($value['id']) && isset($value['project_id'])) {
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
                
            }
        //}
        **/

        $job->delete();
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
                'password'=>substr($data['identity'], -6),
                'password_confirmation'=>substr($data['identity'], -6),
                'phone'=>$data['user_phone'],
                'name'=>$data['name'],
                'surname'=>$data['name'],
                'identity'=>$data['identity'],
                'address' => $data['user_address'],
                'edu_type' => $data['user_edu_type'],
                'company' =>$data['user_company'],
                'tax_number' =>isset($data['user_tax_number'])?$data['user_tax_number']:'12324',
            ], true, true);
        }
    }
}
