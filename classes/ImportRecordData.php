<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-13
 * Time: 下午10:31
 */

namespace Samubra\Train\Classes;

use Samubra\Train\Models\Member;
use Samubra\Train\Models\Record;
use DB;

class ImportRecordData
{
    public function fire($job, $data)
    {
        if(DB::table('samubra_train_members')->where('member_name',$data['member_name'])->where('member_identity',$data['member_identity'])->count())
        {
            $member = DB::table('samubra_train_members')->select('id')->where('member_name',$data['member_name'])->where('member_identity',$data['member_identity'])->first();
            $memberId = $member->id;
        }else{
            $memberId = DB::table('samubra_train_members')->insertGetId(
                [
                    'member_name'=>$data['member_name'],
                    'member_identity'=>$data['member_identity'],
                    'member_phone' => $data['phone'],
                    'member_address' => $data['address'],
                    'member_edu_id' => $data['edu'],
                    'member_company' => $data['company'],
                ]
            );
        }

        $recordData = [
            'import_id' => $data['id'],
            'member_id' => $memberId,
            'type_id' => $data['type'],
            'edu_id' => $data['edu'],
            'first_get_date' => $data['first_get_date'],
            'print_date' => $data['print_date'],
            'is_valid' => $data['is_valid'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'company' => $data['company'],
            'remark' => $data['remark']
        ];
        DB::table('samubra_train_records')->insert($recordData);

        $job->delete();
    }
}