<?php namespace Samubra\Train\Models;

use Samubra\Train\Classes\ExportXlsModel;
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午2:34
 */

class RecordsExport extends ExportXlsModel
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

    protected $appends = ['type','edu','first_get_date_start','first_get_date_end','print_date_start','print_date_end','company','is_valid'];
    protected $fillable = [
        'type','edu','first_get_date_start','first_get_date_end','print_date_start','print_date_end','company','is_valid'
    ];

    public function exportData($columns, $sessionKey = null)
    {
        $condition = [];
        if($this->type)
            $condition['type'] = $this->type;
        if($this->edu)
            $condition['edu'] = $this->edu;
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
        $recordModel = Record::export($condition)->with('member','type','edu')->get();
        $recordModel->each(function($record) use ($columns) {
            $record->remark = json_encode($record->remark);
            $record->member_name = $record->member->member_name;
            $record->member_identity = '\''.$record->member->member_identity;
            $record->edu_name = $record->edu->display_name;
            $record->edu_id = $record->edu_id;
            $record->type_name = $record->type->name;
            $record->type_id = $record->type_id;
            $record->addVisible(array_keys($columns));

        });
        $list[] = $columns;
        //$list = [];
        foreach ($recordModel as $k=>$record)
        {
            $row = [];
            foreach ($columns as $key=>$column)
            {
                $row[$key] = $record->$key;
            }
            $list[]=$row;
        }
        return $list;

    }

    public function getTypeOptions()
    {
        return Category::depth(1)->lists('name','id');
    }
    public function getEduOptions()
    {
        return Lookup::getEduOptions();
    }

}