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

class ImportRecordData
{
    public function fire($job, $data)
    {

        $memberModel = Member::firstOrCreate(['member_name'=>$data['member_name'],'member_identity'=>$data['member_identity']]);
        $memberModel->member_phone = $data['phone'];
        $memberModel->member_address = $data['address'];
        $memberModel->member_edu_id = $data['edu'];
        $memberModel->member_company = $data['company'];
        $memberModel->save();

        $recordData = [
            'import_id' => $data['id'],
            'member_id' => $memberModel->id,
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
        Record::create($recordData);
        $job->delete();
    }
}