<?php namespace Samubra\Train\Updates;

use Seeder;
use Samubra\Train\Models\Category;

class SeedCategoryTable extends Seeder
{
    protected $categoryList = [
        [
            'name' => '电工作业',
            'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'name' => '低压电工作业',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'name' => '高压电工作业',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'name' => '焊接与热切割作业',
            'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'name' => '熔化焊接与热切割作业',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'name' => '高处作业',
            'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'name' => '高处安装、维护、拆除作业',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'name' => '登高架设作业',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
        ],
        [
            'name' => '场内机动车辆驾驶作业',
            'type' => 'train_type',
            'complete_type' => 'operations_certificate',
            'unit'=>'Y',
            'validity'=>3,
            'organ'=>'重庆市安全生产监督管理局',
            'child' => [
                [
                    'name' => '挖掘机',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'name' => '装载机',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'name' => '压路机',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
                [
                    'name' => '推土机',
                    'type' => 'operation_type',
                    'complete_type' => 'operations_certificate',
                    'unit'=>'Y',
                    'validity'=>3,
                    'organ'=>'重庆市安全生产监督管理局',
                ],
            ]
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
    }

    protected function creteDate($data,$parent_id=null)
    {
        unset($data['type']);
        if(isset($data['child']))
            unset($data['child']);
        if(!is_null($parent_id))
            $data['parent_id']= $parent_id;
        $model = Category::create($data);
        return $model;
    }
}