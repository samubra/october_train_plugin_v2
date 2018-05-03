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
       // dd($data);
        if(DB::table('train_members')->where('name',$data['name'])->where('identity',$data['identity'])->count())
        {
            $member = DB::table('train_members')->select('id')->where('name',$data['name'])->where('identity',$data['identity'])->first();
            $memberId = $member->id;
        }else{
            $memberId = DB::table('train_members')->insertGetId(
                [
                    'name'=>$data['name'],
                    'identity'=>$data['identity'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'edu_id' => $data['edu'],
                    'company' => $data['company'],
                ]
            );
        }

        $recordData = [
            //'import_id' => $data['id'],
            //'id' => $data['id'],
            'member_id' => $memberId,
            'type_id' => $data['type'],
            'first_get_date' => $data['first_get_date'],
            'print_date' => $data['print_date'],
            'is_valid' => $data['is_valid'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'company' => $data['company'],
            'remark' => $data['remark']
        ];
        DB::table('train_certificates')->insert($recordData);

        $job->delete();
    }
}
