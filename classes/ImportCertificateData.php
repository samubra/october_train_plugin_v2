<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-13
 * Time: 下午10:31
 */

namespace Samubra\Train\Classes;

use Samubra\Train\Models\Certificate;
use DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

class ImportCertificateData
{
    public function fire($job, $data)
    {
        foreach ($data as $row => $value) {
            //dd($data);
            $name = $value['name'];//substr($value['name'],0,1);
            $surname = $value['name'];//substr($value['name'],1,strlen($value['name'])-1);
            if(DB::table('users')->where('name',$name)->where('surname',$surname)->where('identity',$value['identity'])->count())
            {
                $user = DB::table('users')->where('name',$name)->where('surname',$surname)->where('identity',$value['identity'])->first();
                $user_id = $user->id;
            }else{
                $user_id = DB::table('users')->insertGetId(
                    [
                        'name'=>$name,
                        'surname'=>$surname,
                        'username'=>$value['identity'],
                        'email'=>$value['identity'].'@site.com',
                        'password' => Hash::make(substr($value['identity'],-6)),
                        'identity'=>$value['identity'],
                        'phone' => $value['phone'],
                        'address' => $value['address'],
                        'edu_id' => $value['edu_id'],
                        'company' => $value['company'],
                    ]
                );
            }

            $recordData = [
                //'import_id' => $data['id'],
                'uuid' => Uuid::uuid4()->toString(),
                'user_id' => $user_id,
                'type_id' => $value['type_id'],
                'first_get_date' => $value['first_get_date'],
                'print_date' => $value['print_date'],
                'is_valid' => $value['is_valid'],
                'phone' => $value['phone'],
                'address' => $value['address'],
                'company' => $value['company'],
                'remark' => $value['remark']
            ];
            DB::table('train_certificates')->insert($recordData);
        }
        $job->delete();
    }
}
