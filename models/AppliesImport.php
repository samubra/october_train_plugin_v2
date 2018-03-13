<?php namespace Samubra\Train\Models;
use Samubra\Train\Classes\ImportXlsModel;

/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午2:34
 */

class AppliesImport extends ImportXlsModel
{

    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {
                $subscriber = new Subscriber;
                $subscriber->fill($data);
                $subscriber->save();

                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }

}