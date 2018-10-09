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
     * 'member_name'=>'','member_identity'=>'','member_phone'=>'','member_address'=>'','member_edu_id'=>'','member_user_id'=>'','member_company'=>''
     * 'member_id'=>'','type_id'=>'','edu_id'=>'','first_get_date'=>'','print_date'=>'','is_valid'=>'','phone'=>'','address'=>'','company'=>'','remark'
     * @var array The rules to be applied to the data.
     */
    public $rules = [];
    public function importData($results, $sessionKey = null)
    {

        try {
            foreach ($results as $value) {
                trace_log($results);
                
                //Queue::push(ImportCertificateData::class, $value);
                $this->logCreated();
                exit();
            }
        }
        catch (\Exception $ex) {
            $this->logError(1, $ex->getMessage());
        }
    }
}