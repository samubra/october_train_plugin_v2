<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-13
 * Time: 下午10:31
 */

namespace Samubra\Train\Classes;

use Samubra\Train\Models\Member;
use Samubra\Train\Models\Certificate;
use DB;

class ImportCertificateData
{
    public function fire($job, $data)
    {
        foreach ($data as $row => $value) {
            //dd($data);
            if(DB::table('train_members')->where('name',$value['name'])->where('identity',$value['identity'])->count())
            {
                $member = DB::table('train_members')->select('id')->where('name',$value['name'])->where('identity',$value['identity'])->first();
                $memberId = $member->id;
            }else{
                $memberId = DB::table('train_members')->insertGetId(
                    [
                        'name'=>$value['name'],
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
                //'id' => $data['id'],
                'member_id' => $memberId,
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
