<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午2:36
 */

namespace Samubra\Train\Models;

use Samubra\Train\Classes\ExportXlsModel;


class AppliesExport extends ExportXlsModel
{
    use \October\Rain\Database\Traits\Validation;

    protected $appends = ['export_by','record','member','training','status','health'];
    protected $fillable = [
        'export_by','record','member','training','status','health'
    ];

    public $rules = [
        'export_by' => 'required|in:all,record,training,member',
        'record' => 'required_if:export_by,record|exists:samubra_train_records,id',
        'member' => 'required_if:export_by,member|exists:samubra_train_members,id',
        'training' => 'required_if:export_by,training|exists:samubra_train_training,id',
        'status' => 'nullable|exists:samubra_train_lookups,id',
        'health' => 'nullable|exists:samubra_train_lookups,id',
    ];

    public function exportData($columns, $sessionKey = null)
    {

        //\DB::enableQueryLog();
        if($this->member)
            $recordIds = Member::find($this->member)->records->pluck('id')->toArray();
        else
            $recordIds = [];
        $conditonsList = [
            'record'=>['record_id'=>$this->record],
            'training'=>['training_id'=>$this->training],
            'member'=>['record_id'=>$recordIds],//有问题，需要调整
            'status'=>['status_id'=>$this->status],
            'health'=>['health_id'=>$this->health],
        ];
        $conditions = [];
        if($this->export_by != 'all')
            $conditions[] = $conditonsList[$this->export_by];
        if($this->status)
            $conditions[] = $conditonsList['status'];
        if($this->health)
            $conditions[] = $conditonsList['health'];


        $applies = Apply::by($conditions)->with('record','record.member','training','training.plan.type','status','health')->get();


        //dd(\DB::getQueryLog());
        $applies->each(function($apply) use ($columns) {

            $apply->remark = json_encode($apply->remark);
            $apply->record_identity = '\''.$apply->record->member->member_identity;

            $apply->addVisible($columns);

        });
        $list = [];
        foreach ($applies as $apply)
        {
            $row = [];
            foreach ($columns as $column)
            {

                switch ($column)
                {
                    case 'record_id':
                        $row[$column] = $apply->record->member->member_name;
                        break;
                    case 'training_id':
                        $row[$column] = $apply->training->plan->type->name;
                        break;
                    case 'status_id':
                        $row[$column] = $apply->status->display_name;
                        break;
                    case 'training':
                        $row[$column] = $apply->training->title;
                        break;
                    case 'health_id':
                        $row[$column] = $apply->health->display_name;
                        break;
                    default :
                        $row[$column] = $apply->$column;
                }

            }
            $list[]=$row;
        }

        return $list;
    }

    public function getExportByOptions()
    {
        return [
            'all'=>'导出所有记录',
            'training'=>'按照培训项目导出',
            'record'=>'按照培训证书导出',
            'member'=>'按照某个培训学员导出',
        ];
    }

    public function getTrainingOptions()
    {
        return Training::lists('title','id');
    }

    public function getRecordOptions()
    {
        return Record::with('member','type')->lists('member_id','id');
    }

    public function getMemberOptions()
    {
        return Member::lists('member_name','id');
    }
    public function getStatusOptions()
    {
        return Lookup::getStatusOptions();
    }
    public function getHealthOptions()
    {
        return Lookup::getHealthOptions();
    }




}