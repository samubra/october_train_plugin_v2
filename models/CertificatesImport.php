<?php namespace Samubra\Train\Models;

use \Backend\Models\ImportModel;
use Samubra\Train\Classes\ImportCertificateData;
use Queue;
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
        foreach ($results as $row => $data) {
            //dd($data);
            try {
                Queue::push(ImportCertificateData::class,$data);
                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }

}