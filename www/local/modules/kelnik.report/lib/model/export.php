<?php


namespace Kelnik\Report\Model;


use Bitrix\Main\Localization\Loc;
use PhpOffice\PhpSpreadsheet\IOFactory;

Loc::loadMessages(__FILE__);

class Export
{
    /**
     * @var int
     */
    protected $year;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var array
     */
    protected $companies = [];

    /**
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    protected $spreadsheet;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(int $year, int $type, array $companies = [])
    {
        $this->year = $year;
        $this->type = $type;
        $this->companies = $companies;

        $this->companies = array_map(function ($el) {
            return (int)$el;
        }, $this->companies);

        if (!$this->companies || !$this->year || !array_key_exists($this->type, ReportsTable::getTypes())) {
            throw new \Exception(Loc::getMessage('KELNIK_REPORT_EXPORT_INVALID_PARAMS'));
        }

        $this->spreadsheet = IOFactory::load(realpath(
            implode(
                DIRECTORY_SEPARATOR,
                [
                    __DIR__,
                    '..',
                    '..',
                    'export_tmpl',
                    'tables.xlsx'
                ]
            )
        ));
    }

    public function getFile()
    {
        $this->importData();
        $this->sendFile();
    }

    protected function importData()
    {
        $config = ReportFieldsTable::getFormConfig();
        $forms  = array_keys($config);

        // Т.к. резидентов может быть много,
        // то для каждой формы делаем отдельный запрос
        //
        foreach ($forms as $form) {
            $this->loadData($form);
            $this->spreadsheet->setActiveSheetIndex($form);
            $this->processForm($form);
        }
    }

    protected function loadData($formNum)
    {
        $this->data = [];

        try {
            $res = ReportsTable::getAssoc([
                'select' => [
                    'COMPANY_ID', 'YEAR', 'TYPE', 'NAME', 'NAME_SEZ',
                    'REPORT_FIELD_' => 'FIELDS',
                    'REPORT_FIELD_GROUP_' => 'FIELDS.GROUP'
                ],
                'filter' => [
                    '=COMPANY_ID' => $this->companies,
                    '=TYPE' => $this->type,
                    '=YEAR' => $this->year,
                    '=FIELDS.FORM_NUM' => $$formNum
                ]
            ]);
        } catch (\Exception $e) {
            $res = [];
        }

        if (!$res) {
            return;
        }

        echo '<pre>';

        foreach ($res as $k => $v) {
            unset($res[$k]);
            print_r($v);

//            $this->data['']
//            $this->data['fields'] = [];
        }
        die;
    }

    protected function processForm($formNum)
    {

    }

    protected function sendFile()
    {
        ob_clean();
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="export_' . date('Y-m-d_H-i') . '.xlsx"');

        $writer->save('php://output');
        exit;
    }
}
