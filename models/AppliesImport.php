<?php namespace Samubra\Train\Models;
use Samubra\Train\Classes\ImportXlsModel;
use \Backend\Models\ImportModel;
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午2:34
 */

class AppliesImport extends ImportModel
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
    "member_name" => "彭勇"
    "member_identity" => "'500233198608028136"
    "training_id" => "低压电工作业"
    "record_id" => "2018年巫溪县特种作业低压电工作业新训1期次"
    "status_id" => "正在受理"
    "health_id" => "体检合格"
    "address" => "重庆市巫溪县"
    "company" => "巫溪县职业教育中心"
    "pay" => "1000"
    "phone" => "15223565566"
    "operate_score" => "0"
    "theory_score" => "0"
    "record_print_date" => "2018-03-12 06:43:04"
    "record_is_valid" => "2018-03-12 06:43:04"
    ]
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {
            try {
                switch ($this->import_type)
                {
                    case 'by_create':
                        if(isset($data['training_id']))
                        {
                            if(isset($data['id']))
                                $data = array_except($data, ['id']);
                            $this->createApply($data);
                        }
                        break;
                    case 'by_update':
                        if(isset($data['id']) && is_int($data['id']))
                        {
                            $this->updateApply($data);
                        }
                        break;
                    default:
                        if(isset($data['id']) && is_int($data['id']))
                        {
                            $this->updateApply($data);
                        }else{
                            if(isset($data['training_id']))
                            {
                                if(isset($data['id']))
                                    $data = array_except($data, ['id']);
                                $this->createApply($data);
                            }
                        }
                }
                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }

    public function getImportTypeOptions()
    {
        return [
            'auto' => '自动识别(推荐，如匹配到ID字段则为更新式导入，无ID则为添加式导入)',
            'by_create' => '添加导入(不管有无ID，均自动忽略)',
            'by_update' => '更新导入(没有匹配到ID的行则主动忽略)',
        ];
    }

    protected function updateApply($data)
    {
        $applyModel = Apply::with('record')->find($data['id']);
        if(isset($data['status_id']) && is_int($data['status_id']))
            $applyModel->status_id = $data['status_id'];
        if(isset($data['health_id']) && is_int($data['health_id']))
            $applyModel->health_id = $data['health_id'];
        if(isset($data['address']))
            $applyModel->address = $data['address'];
        if(isset($data['company']))
            $applyModel->company = $data['company'];
        if(isset($data['pay']))
            $applyModel->pay = $data['pay'];
        if(isset($data['phone']))
            $applyModel->phone = $data['phone'];
        if(isset($data['operate_score']))
            $applyModel->operate_score = $data['operate_score'];
        if(isset($data['theory_score']))
            $applyModel->theory_score = $data['theory_score'];

        $applyModel->save();

        if(isset($data['record_print_date']) || isset($data['record_is_valid']))
        {
            $recordModel = $applyModel->record;
            if(isset($data['record_print_date']))
                $recordModel->print_date = $data['record_print_date'];
            else
                $recordModel->is_valid = $data['record_is_valid'];
            $recordModel->save();
        }

        return $applyModel;
    }

    protected function createApply($data)
    {
        $applyModel = new Apply;
        $applyModel->fill($data);
        $applyModel->save();
        return $applyModel;
    }

}