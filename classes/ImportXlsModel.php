<?php namespace Samubra\Train\Classes;
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 18-3-12
 * Time: 下午2:34
 */
use \Backend\Models\ImportModel;

class ImportXlsModel extends ImportModel
{

    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        return '';
    }

    /**
     * Converts column index to database column map to an array containing
     * database column names and values pulled from the CSV file. Eg:
     *
     *   [0 => [first_name], 1 => [last_name]]
     *
     * Will return:
     *
     *   [first_name => Joe, last_name => Blogs],
     *   [first_name => Harry, last_name => Potter],
     *   [...]
     *
     * @return array
     */
    protected function processImportData($filePath, $matches, $options)
    {
        /*
         * Parse options
         */
        $defaultOptions = [
            'firstRowTitles' => true,
            'delimiter' => null,
            'enclosure' => null,
            'escape' => null,
            'encoding' => null
        ];

        $options = array_merge($defaultOptions, $options);
        dd($filePath);
        /*
         * Read CSV
         */
        $reader = CsvReader::createFromPath($filePath, 'r');

        // Filter out empty rows
        $reader->addFilter(function (array $row) {
            return count($row) > 1 || reset($row) !== null;
        });

        if ($options['delimiter'] !== null) {
            $reader->setDelimiter($options['delimiter']);
        }

        if ($options['enclosure'] !== null) {
            $reader->setEnclosure($options['enclosure']);
        }

        if ($options['escape'] !== null) {
            $reader->setEscape($options['escape']);
        }

        if ($options['firstRowTitles']) {
            $reader->setOffset(1);
        }

        if (
            $options['encoding'] !== null &&
            $reader->isActiveStreamFilter()
        ) {
            $reader->appendStreamFilter(sprintf(
                '%s%s:%s',
                TranscodeFilter::FILTER_NAME,
                strtolower($options['encoding']),
                'utf-8'
            ));
        }

        $result = [];
        $contents = $reader->fetchAll();
        foreach ($contents as $row) {
            $result[] = $this->processImportRow($row, $matches);
        }

        return $result;
    }

}