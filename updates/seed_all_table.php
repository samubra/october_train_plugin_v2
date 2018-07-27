<?php namespace Samubra\Train\Updates;

use Seeder;
use Samubra\Train\Models\Category;
use Samubra\Train\Models\Lookup;

class SeedAllTable extends Seeder
{
    
    protected $categoryList = [
        [
            'title' => '电工作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '低压电工作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '高压电工作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '焊接与热切割作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '熔化焊接与热切割作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '高处作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '高处安装、维护、拆除作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '登高架设作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '场内机动车辆驾驶作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '挖掘机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '装载机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '压路机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'title' => '推土机',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'title' => '烟花爆竹安全作业',
            //'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'title' => '烟花爆竹储存作业',
                    //'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ]
            ]
        ],
    ];
    protected $lookupList = [
        [
            'title' => '接受报名申请',
            'type' => 'project_status',
        ],
        [
            'title' => '已停止报名申请',
            'type' => 'project_status',
        ],
        [
            'title' => '培训资料已存档',
            'type' => 'project_status',
        ],
        [
            'title' => '等待受理',
            'type' => 'apply_status',
        ],
        [
            'title' => '正在受理',
            'type' => 'apply_status',
        ],
        [
            'title' => '申请审核已通过，等待考试',
            'type' => 'apply_status',
        ],
        [
            'title' => '申请未审核通过',
            'type' => 'apply_status',
        ],
        [
            'title' => '考试未通过',
            'type' => 'apply_status',
        ],
        [
            'title' => '考试通过，等待领证',
            'type' => 'apply_status',
        ],
        [
            'title' => '已领证',
            'type' => 'apply_status',
        ],
        [
            'title' => '初中',
            'type' => 'edu_type',
        ],
        [
            'title' => '中专或同等学历',
            'type' => 'edu_type',
        ],
        [
            'title' => '高中或同等学历',
            'type' => 'edu_type',
        ],
        [
            'title' => '大专或同等学历',
            'type' => 'edu_type',
        ],
        [
            'title' => '本科及其以上',
            'type' => 'edu_type',
        ],
        [
            'title' => '无需体检',
            'type' => 'health_type',
        ],
        [
            'title' => '无意见性结论',
            'type' => 'health_type',
        ],
        [
            'title' => '体检合格',
            'type' => 'health_type',
        ],
        [
            'title' => '电工作业',
            'type' => 'teacher_type',
        ],
        [
            'title' => '登高架设作业',
            'type' => 'teacher_type',
        ],
        [
            'title' => '焊接与热切割作业',
            'type' => 'teacher_type',
        ],
        [
            'title' => '企业内机动车辆',
            'type' => 'teacher_type',
        ],
        [
            'title' => '非煤矿山',
            'type' => 'teacher_type',
        ],
        [
            'title' => '法律法规及案例分析',
            'type' => 'teacher_type',
        ],
        [
            'title' => '烟花爆竹',
            'type' => 'teacher_type',
        ],
        [
            'title' => '危险化学品',
            'type' => 'teacher_type',
        ],
        [
            'title' => '其他',
            'type' => 'teacher_type',
        ],
    ];

    public function run()
    {
        foreach ($this->categoryList as $category)
        {
            $model = $this->creteDate($category);
            if(isset($category['child']))
            {
                foreach ( $category['child'] as $item) {
                    $this->creteDate($item,$model->id);
                }
            }
        }
        foreach ($this->lookupList as $key=>$lookup)
        {
            Lookup::create([
                'name'                 => $lookup['title'],
                'type'                 => $lookup['type'],
                //'order'=> $key+1
            ]);
        }
    }

    protected function creteDate($data,$parent_id=null)
    {
        //unset($data['type']);
        if(isset($data['child']))
            unset($data['child']);
        if(!is_null($parent_id))
            $data['parent_id']= $parent_id;
        $model = Category::create($data);
        return $model;
    }
}