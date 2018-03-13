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

        $recordModel = new Record;
        $recordModel->id = $data['id'];
        $recordModel->member_id = $memberModel->id;
        $recordModel->type_id = $data['type'];
        $recordModel->edu_id = $data['edu'];
        $recordModel->first_get_date = $data['first_get_date'];
        $recordModel->print_date = $data['print_date'];
        $recordModel->is_valid = $data['is_valid'];
        $recordModel->phone = $data['phone'];
        $recordModel->address = $data['address'];
        $recordModel->company = $data['company'];
        $recordModel->remark = $data['remark'];

        //dd($recordModel);
        $recordModel->save();
//echo '1';
        $job->delete();
    }
}