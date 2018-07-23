<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-7-22
 * Time: 下午3:05
 *
 * 检查证书和培训项目是否符合
 */

namespace Samubra\Train\Classes;

use October\Rain\Exception\ApplicationException;
use Samubra\Train\Models\Certificate;
use Samubra\Train\Models\Project;
use Carbon\Carbon;

class CanApply
{
    protected $certificateModel;
    protected $projectModel;

    public $projectEndApplyDate;
    protected $certificatePrintDate;

    protected $filter = [];

    public function __construct($project, $certificate = null)
    {
            $this->projectModel = $project;
            $this->certificateModel = $certificate;
    }

    public function setCertificateModel($certificate)
    {
        $this->certificateModel = $certificate;
    }

    /**
     * 执行最后的检查
     * @return bool
     */
    public function check()
    {
        $this->checkCertificateModel();
        return $this->checkType() && $this->projectModel->can_apply && ( $this->checkCertificateAndProjectIsNew() || $this->checkCertificateAndProjectIsReview() ) && $this->checkProjectEndApplyDate();
    }

    /**
     * 检查当前日期在受理截止日期之前或等于
     * @return bool
     */
    public function checkProjectEndApplyDate()
    {
        $this->getProjectEndApplyDate();
        return $this->getNow()->lessThanOrEqualTo($this->projectEndApplyDate);
    }
    /**
     * 检查证书和培训项目都是复训,并检查
     * @return bool
     */
    public function checkCertificateAndProjectIsReview()
    {
        $this->checkCertificateModel();
        return !$this->checkCertificateAndProjectIsNew() && $this->checkCertificateByProjectFilterDate();
    }
    /**
     * 检查证书和培训项目是新训
     * @return bool
     */
    public function checkCertificateAndProjectIsNew()
    {
        $this->checkCertificateModel();
        return $this->projectIsNew() && $this->certificateIsNew();
    }

    /**
     * 检查当前证书是否符合培训项目的所设定的时间段
     * @return bool
     */
    public function checkCertificateByProjectFilterDate()
    {
        $this->checkCertificateModel();
        //初始化printDate
        $this->getCertificatePrintDate();

        $this->getProjectFilterData();

        $can_apply_in = false;
        foreach ($this->filter as $filter)
        {
            if($can_apply_in = $this->certificatePrintDate->between($filter['start'],$filter['end']))
                return $can_apply_in;
        }
        return $can_apply_in;
    }

    /**
     * 简单判断证书失效，未判断证书日期过期
     * @return bool
     */
    public function certificateIsNotValid()
    {
        $this->checkCertificateModel();
        return !$this->$this->certificateIsNew() && !$this->certificateIsReview();
    }
    /**
     * 判断证书是复训
     * @return bool
     */
    public function certificateIsReview()
    {
        $this->checkCertificateModel();
        return ($this->certificateModel->is_valid && !is_null($this->certificateModel->prin_date));
    }
    /**
     * 判断证书是否新训
     * @return bool
     */
    public function certificateIsNew()
    {
        $this->checkCertificateModel();
        return (!$this->certificateModel->is_valid && is_null($this->certificateModel->prin_date));
    }
    /**
     * 判断培训项目是复训
     * @return bool
     */
    public function projectIsNotNew()
    {
        return !$this->projectIsNew();
    }

    /**
     *判断培训项目是新训
     * @return bool
     */
    public function projectIsNew()
    {
        return $this->projectModel->plan->is_new;
    }

    /**
     * 检查培训项目和证书的类别是否一致
     * @return bool
     */
    public function checkType()
    {
        $this->checkCertificateModel();
        return $this->certificateModel->type_id === $this->projectModel->plan->type_id;
    }



    protected function getProjectFilterData()
    {
        $filter = [];
        if(count($this->projectModel->certiicate_print_date_filter)){
            foreach ($this->projectModel->certiicate_print_date_filter as $data) {
                $filter[] = [
                    'start' => Carbon::createFromFormat('Y-m-d', substr($data['start'],0,-9)),
                    'end' => Carbon::createFromFormat('Y-m-d', substr($data['end'],0,-9))
                ];
            }
        }
        $this->filter = $filter;
    }
    /**
     * @return $this
     */
    protected function getProjectEndApplyDate()
    {
        $this->projectEndApplyDate = Carbon::createFromFormat('Y-m-d H:i:s',$this->projectModel->end_apply_date);
    }

    /**
     * @return $this
     */
    protected function getCertificatePrintDate()
    {
        $this->checkCertificateModel();
        if(!is_null($this->certificateModel->print_date))
            $this->certificatePrintDate = Carbon::createFromFormat('Y-m-d',$this->certificateModel->print_date);
        return $this;
    }

    /**
     * @return static
     */
    protected function getNow()
    {
        return Carbon::now();
    }

    protected function checkCertificateModel()
    {
        if(is_null($this->certificateModel))
            abort(500);
    }

}