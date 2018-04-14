<?php namespace Samubra\Train\Updates;

use Seeder;
use Samubra\Train\Models\Lookup;

class SeedLookupTable extends Seeder
{
    protected $lookupList = [
        [
            'display_name' => '接受报名申请',
            'lookup_type' => 'plan_status',
        ],
        [
            'display_name' => '已停止报名申请',
            'lookup_type' => 'plan_status',
        ],
        [
            'display_name' => '培训资料已存档',
            'lookup_type' => 'plan_status',
        ],
        [
            'display_name' => '等待受理',
            'lookup_type' => 'apply_status',
        ],
        [
            'display_name' => '正在受理',
            'lookup_type' => 'apply_status',
        ],
        [
            'display_name' => '申请审核已通过，等待考试',
            'lookup_type' => 'apply_status',
        ],
        [
            'display_name' => '申请未审核通过',
            'lookup_type' => 'apply_status',
        ],
        [
            'display_name' => '考试未通过',
            'lookup_type' => 'apply_status',
        ],
        [
            'display_name' => '考试通过，等待领证',
            'lookup_type' => 'apply_status',
        ],
        [
            'display_name' => '已领证',
            'lookup_type' => 'apply_status',
        ],
        [
            'display_name' => '初中',
            'lookup_type' => 'edu_type',
        ],
        [
            'display_name' => '中专或同等学历',
            'lookup_type' => 'edu_type',
        ],
        [
            'display_name' => '高中或同等学历',
            'lookup_type' => 'edu_type',
        ],
        [
            'display_name' => '大专或同等学历',
            'lookup_type' => 'edu_type',
        ],
        [
            'display_name' => '本科及其以上',
            'lookup_type' => 'edu_type',
        ],
        [
            'display_name' => '无需体检',
            'lookup_type' => 'health_type',
        ],
        [
            'display_name' => '无意见性结论',
            'lookup_type' => 'health_type',
        ],
        [
            'display_name' => '体检合格',
            'lookup_type' => 'health_type',
        ],
        [
            'display_name' => '电工作业',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '登高架设作业',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '焊接与热切割作业',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '企业内机动车辆',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '非煤矿山',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '法律法规及案例分析',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '烟花爆竹',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '危险化学品',
            'lookup_type' => 'teacher_type',
        ],
        [
            'display_name' => '其他',
            'lookup_type' => 'teacher_type',
        ],
    ];

    public function run()
    {
        foreach ($this->lookupList as $key=>$lookup)
        {
            Lookup::create([
                'display_name'                 => $lookup['display_name'],
                'lookup_type'                 => $lookup['lookup_type'],
                'sort'=> $key+1
            ]);
        }
    }
}