<?php namespace Samubra\Train\Models;

use Samubra\Train\Classes\ExportXlsModel;
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午2:34
 */

class CertificatesExport extends ExportXlsModel
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
    0 => "id"
    1 => "member_id"
    2 => "type_id"
    3 => "edu_id"
    4 => "member_name"
    5 => "member_identity"
    6 => "type"
    7 => "edu"
    8 => "first_get_date"
    9 => "print_date"
    10 => "is_valid"
    11 => "phone"
    12 => "address"
    13 => "company"
    14 => "remark"
    15 => "created_at"
    16 => "updated_at"
    17 => "deleted_at"
    ]
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    protected $appends = ['type','first_get_date_start','first_get_date_end','print_date_start','print_date_end','company','is_valid'];
    protected $fillable = [
        'type','first_get_date_start','first_get_date_end','print_date_start','print_date_end','company','is_valid'
    ];

    public function exportData($columns, $sessionKey = null)
    {
        $condition = [];
        if($this->type)
            $condition['type'] = $this->type;
        if($this->is_valid)
            $condition['isValid'] = $this->is_valid;
        if($this->first_get_date_start)
            $condition['firstGetDate']['after'] = $this->first_get_date_start;
        if($this->first_get_date_end)
            $condition['firstGetDate']['before'] = $this->first_get_date_end;
        if($this->print_date_start)
            $condition['printDate']['after'] = $this->print_date_start;
        if($this->print_date_end)
            $condition['printDate']['before'] = $this->print_date_end;
        if($this->company)
            $condition['company'] = $this->company;
        $certificateModel = Certificate::export($condition)->with('member','type')->get();
        $certificateModel->each(function($certificate) use ($columns) {
            $certificate->remark = json_encode($certificate->remark);
            $certificate->user_name = $certificate->user->name;
            $certificate->user_identity = '\''.$certificate->user->identity;
            $certificate->type_name = $certificate->type->title;
            $certificate->type_id = $certificate->type_id;
            $certificate->addVisible(array_keys($columns));

        });
        $list[] = $columns;
        //$list = [];
        foreach ($certificateModel as $k=>$certificate)
        {
            $row = [];
            foreach ($columns as $key=>$column)
            {
                $row[$key] = $certificate->$key;
            }
            $list[]=$row;
        }
        return $list;

    }

    public function getTypeOptions()
    {
        return Category::depth(1)->lists('title','id');
    }
    public function getEduOptions()
    {
        return Lookup::getEduOptions();
    }

}
