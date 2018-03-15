<?php namespace Samubra\Train\Classes;

use \Backend\Models\ExportModel;
use Samubra\Train\Classes\Excel;
use ApplicationException;
use Response;
use File;
use Lang;

class ExportXlsModel extends ExportModel
{

    /**
     * Called when data is being exported.
     * The return value should be an array in the format of:
     *
     *   [
     *       'db_name1' => 'Some attribute value',
     *       'db_name2' => 'Another attribute value'
     *   ],
     *   [...]
     *
     */
    public function exportData($columns, $sessionKey = null)
    {
        return [];
    }

    public function export($columns, $options)
    {
        $sessionKey = array_get($options, 'sessionKey');
        $data = $this->exportData($columns, $sessionKey);
        return $this->processExportData($columns, $data, $options);
    }

    /**
     * Converts a data collection to a xls file.
     */
    protected function processExportData($columns, $results, $options)
    {
        /*
         * Validate
         */
        if (!(count($results)-1)) {
            throw new ApplicationException(Lang::get('backend::lang.import_export.empty_error'));
        }

        /*
         * Parse options
         */
        $defaultOptions = [
            'firstRowTitles' => false,
            'fileName' => 'export',
        ];

        $options = array_merge($defaultOptions, $options);
        $columns = $this->exportExtendColumns($columns);
        $thisModel = $this;


        $exportPath = Excel::excel()->create($options['fileName'], function($excel) use($options,$columns,$results,$thisModel) {

            $excel->sheet('申请记录', function($sheet) use ($options,$columns,$results,$thisModel){
                if($options['firstRowTitles']) {
                    $sheet->prependRow(1, $thisModel->getColumnHeaders($columns));
                }
                $sheet->rows($results)->setFontSize(12)->freezeFirstRow();
            });

        })->store('xls', temp_path(),true);


        return $exportPath['file'];
    }

    /**
     * Download a previously compiled export file.
     * @return void
     */
    public function download($name, $outputName = null)
    {

        $csvPath = temp_path() . '/' . $name;
        if (!file_exists($csvPath)) {
            throw new ApplicationException(Lang::get('backend::lang.import_export.file_not_found_error'));
        }

        $headers = Response::download($csvPath, $outputName)->headers->all();
        $result = Response::make(File::get($csvPath), 200, $headers);

        @unlink($csvPath);

        return $result;
    }

}
