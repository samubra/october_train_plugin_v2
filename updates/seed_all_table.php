<?php namespace Samubra\Train\Updates;

use Seeder;
use Samubra\Train\Models\Category;
use Samubra\Train\Models\Lookup;
use Samubra\Train\Models\Organ;

class SeedAllTable extends Seeder
{
    
    protected $categoryList = [
        [
            'name' => '电工作业',
            'description' => 'operations_certificate',
            'organ_id' => '1',
            'child' => [
                [
                    'name' => '低压电工作业',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
                [
                    'name' => '高压电工作业',
                    'description' => 'operations_certificate',
                    'organ' => '1'
                ],
            ]
        ],
        [
            'name' => '焊接与热切割作业',
            'description'=> 'operations_certificate',
            'organ_id' => '1',
            'child' => [
                [
                    'name' => '熔化焊接与热切割作业',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
            ]
        ],
        [
            'name' => '高处作业',
            //'type' => 't_idrain_type',,
            'description' => 'operations_certificate',
            'organ_id' => '1',
            'child' => [
                [
                    'name' => '高处安装、维护、拆除作业',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
                [
                    'name' => '登高架设作业',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
            ]
        ],
        [
            'name' => '场内机动车辆驾驶作业',
            //'type' => 't_idrain_type',,
            'description' => 'operations_certificate',
            'organ_id' => '1',
            'child' => [
                [
                    'name' => '挖掘机',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
                [
                    'name' => '装载机',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
                [
                    'name' => '压路机',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
                [
                    'name' => '推土机',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ],
            ]
        ],
        [
            'name' => '烟花爆竹安全作业',
            //'type' => 't_idrain_type',,
            'description' => 'operations_certificate',
            'organ_id' => '1',
            'child' => [
                [
                    'name' => '烟花爆竹储存作业',
                    //'type' => 'operation_type',
                    'description' => 'operations_certificate',
                    'organ_id' => '1',
                ]
            ]
        ]
    ];
    protected $organList = [
        [
            'name' => '重庆市安全生产监督管理局',
            'complete_type' => Organ::COMPLETE_TYPE_TRAINING,
            'validity' => '3',
            'unit' => 'Y',
            'need_review' => true
        ],
        [
            'name' => '巫溪县安全生产监督管理局',
            'complete_type' => Organ::COMPLETE_TYPE_TRAINING,
            'validity' => '1',
            'unit' => 'Y',
            'need_review' => true
        ],
    ];

    public function run()
    {

        foreach ($this->organList as $organ)
        {
            Organ::create($organ);
        }
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
