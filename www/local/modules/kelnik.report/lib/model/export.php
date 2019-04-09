<?php


namespace Kelnik\Report\Model;


use Bitrix\Main\Localization\Loc;
use Kelnik\Helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
            $methodName = 'processForm' . $form;

            if ($this->data && method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        $this->spreadsheet->setActiveSheetIndex(ReportFieldsTable::FORM_COMMON);
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
                    '=FIELDS.FORM_NUM' => $formNum
                ]
            ]);
        } catch (\Exception $e) {
            $res = [];
        }

        if (!$res) {
            return;
        }

        foreach ($res as $k => $v) {
            unset($res[$k]);

            if (empty($this->data[$v['COMPANY_ID']])) {
                $this->data[$v['COMPANY_ID']] = [
                    'NAME' => $v['NAME'],
                    'NAME_SEZ' => $v['NAME_SEZ'],
                    'GROUPS' => [],
                    'FIELDS' => []
                ];
            }
            $this->data[$v['COMPANY_ID']]['FIELDS'][$v['REPORT_FIELD_GROUP_ID']][$v['REPORT_FIELD_NAME']] = $v['REPORT_FIELD_VALUE'];

            if (!$v['REPORT_FIELD_GROUP_ID']) {
                continue;
            }

            $this->data[$v['COMPANY_ID']]['GROUPS'][$v['REPORT_FIELD_GROUP_ID']] = $v['REPORT_FIELD_GROUP_TYPE'];
        }
    }

    protected function processForm0()
    {
        $sheet = &$this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('O3', self::getCurrentDate());
        
        $valueToCell = [
            'F' => 'foreign-investors',
            'H' => 'investors-countries',
            'I' => 'jobs-plan-all',
            'J' => 'jobs-plan-year',
            'K' => 'jobs-actual-all',
            'L' => 'jobs-actual-year',
            'M' => 'invests-plan-all',
            'N' => 'invests-plan-year',
            'O' => 'capital-invests-plan-all',
            'P' => 'capital-invests-plan-year',
            'Q' => 'invests-all',
            'R' => 'invests-year',
            'S' => 'capital-invests-all',
            'T' => 'capital-invests-year',
            'U' => 'revenue-all',
            'V' => 'revenue-year',
            'W' => 'produce-all',
            'X' => 'produce-year',
            'Y' => 'salary'
        ];

        $rowNum = 10;
        foreach ($this->data as $company) {

            $sheet->setCellValue('A' . $rowNum, $rowNum - 9);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('E' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                if ($valueName === 'foreign-investors') {
                    $val = $val == 'yes' ? 'да' : 'нет';
                } elseif ($valueName === 'investors-countries' && !$val) {
                    $val = 'Россия';
                } else {
                    $val = self::normalizeFloat($val);
                }

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            $rowNum++;
        }
    }

    protected function processForm1()
    {
        $sheet = &$this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('K3', self::getCurrentDate());

        $valueToCell = [
            'F' => 'taxes-federal-all',
            'G' => 'taxes-federal-year',
            'H' => 'taxes-regional-all',
            'I' => 'taxes-regional-year',
            'J' => 'taxes-local-all',
            'K' => 'taxes-local-year',
            'L' => 'taxes-offbudget-all',
            'M' => 'taxes-offbudget-year',
            'N' => 'taxes-nds-all',
            'O' => 'taxes-nds-year',
            'P' => 'taxes-breaks-all',
            'Q' => 'taxes-breaks-year',
            'R' => 'taxes-breaks-federal-all',
            'S' => 'taxes-breaks-federal-year',
            'T' => 'taxes-breaks-local-all',
            'U' => 'taxes-breaks-local-year',
            'V' => 'taxes-breaks-offbudget-all',
            'W' => 'taxes-breaks-offbudget-year',
            'X' => 'custom-duties-all',
            'Y' => 'custom-duties-year',
            'Z' => 'custom-duties-breaks-all',
            'AA' => 'custom-duties-breaks-year'
        ];

        $sumFields = ArrayHelper::getValue(ReportFieldsTable::getFormConfig(), ReportFieldsTable::FORM_TAXES . '.blocks.0.fields', []);
        $sumFields = array_column($sumFields, 'id');

        $rowNum = 10;
        foreach ($this->data as $company) {

            $sheet->setCellValue('A' . $rowNum, $rowNum - 9);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            $total = [0,0];
            foreach ($sumFields as $sumField) {
                $k = 0;
                if (false !== strpos($sumField, '-year')) {
                    $k = 1;
                }

                $total[$k] += (float) ArrayHelper::getValue($company, 'FIELDS.0.' . $sumField);
            }

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = self::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            $sheet->setCellValue('D' . $rowNum, $total[0]);
            $sheet->setCellValue('E' . $rowNum, $total[1]);

            $rowNum++;
        }
    }

    protected function processForm2()
    {
        $sheet = &$this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('G4', self::getCurrentDate());

        $valueToCell = [
            'D' => 'area-application',
            'E' => 'area-rent',
            'F' => 'area-property',
            'G' => 'object-name-plan',
            'H' => 'capital-object',
            'I' => 'construction-period'
        ];

        $rowNum = 9;

        foreach ($this->data as $company) {

            $rowNum++;

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - 9);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = self::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            if ($rowValue = self::getStages($company)) {
                $richText = new RichText();
                $richText->createText(implode(", \n", $rowValue));

                $cell = $sheet->getCell('J' . $rowNum);

                $cell->setValue($richText);
                $cell->getStyle()->getAlignment()->setWrapText(true);
            }
        }
    }

    protected function processForm3()
    {
        $sheet = &$this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('E4', self::getCurrentDate());

        $valueToCell = [
            'D' => 'office-application',
            'E' => 'office-rent'
        ];

        $rowNum = 8;

        foreach ($this->data as $company) {

            $rowNum++;

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - 8);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = self::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            if ($rowValue = self::getStages($company)) {
                $richText = new RichText();
                $richText->createText(implode(", \n", $rowValue));

                $cell = $sheet->getCell('F' . $rowNum);

                $cell->setValue($richText);
                $cell->getStyle()->getAlignment()->setWrapText(true);
            }
        }
    }

    protected function processForm4()
    {
        $sheet = &$this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('H3', self::getCurrentDate());

        $valueToCell = [
            'E' => 'export-volume-all',
            'F' => 'export-volume-year',
            'I' => 'high-tech-production',
            'L' => 'high-productive-jobs'
        ];

        $rowNum = 8;

        $groupValueToCell = [
            'groups' => [
                'G' => 'export-countries',
                'H' => 'export-code'
            ],
            'innovations' => [
                'K' => 'innovation'
            ]
        ];

        foreach ($this->data as $company) {

            $rowNum++;

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - 8);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('D' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                if ($valueName == 'high-tech-production') {
                    $val = $val == 'yes' ? 'да' : 'нет';
                } else {
                    $val = self::normalizeFloat($val);
                }

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            foreach (['groups', 'innovations'] as $type) {
                if (!$groupValues = self::getGroupValues($company, $type)) {
                    continue;
                }

                foreach ($groupValueToCell[$type] as $cellName => $valueName) {
                    $sheet->setCellValue(
                        $cellName . $rowNum,
                        implode(
                            ', ',
                            array_column($groupValues, $valueName)
                        )
                    );
                }
            }
        }
    }

    protected function processForm5()
    {
        $sheet = &$this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('B4', 'по состоянию на ' . self::getCurrentDate());

        $valueToCell = [
            'E' => 'intangible-assets',
            'F' => 'degrees-employees',
        ];

        $rowNum = 9;
        foreach ($this->data as $company) {

            $sheet->setCellValue('A' . $rowNum, $rowNum - 8);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('D' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = self::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            $rowNum++;
        }
    }

    protected function processForm6()
    {
        $sheet = &$this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('E4', self::getCurrentDate());

        $valueToCell = [
            'D' => 'result-type',
            'E' => 'result-description',
            'F' => 'result-date',
            'G' => 'result-commercialization'
        ];

        $rowNum = 7;
        $companyNum = 1;
        foreach ($this->data as $company) {

            $rowNum++;
            $rowValueNum = $rowNum;

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $companyNum++);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('D' . $rowNum, $company['NAME_SEZ']);

            $values = self::getGroupValues($company, 'results');

            if (!$values) {
                return;
            }

            foreach ($values as $rowValues) {
                if ($rowValueNum > 7) {
                    $this->copyRow($rowValueNum, $rowValueNum + 1);
                }
                foreach ($valueToCell as $cellName => $valueName) {
                    $sheet->setCellValue($cellName . $rowValueNum, ArrayHelper::getValue($rowValues, $valueName));
                    $sheet->getCell($cellName . $rowValueNum)->getStyle()->getAlignment()->setWrapText(true);
                }
                $rowValueNum++;
            }

            $rowNum = $rowValueNum;
        }
    }

    /**
     * @param array $company
     * @return array
     */
    protected static function getStages(array $company)
    {
        $res = [];

        if (empty($company['GROUPS'])) {
            return $res;
        }

        $stages = ReportFieldsTable::getStages();

        foreach ($company['GROUPS'] as $groupId => $groupType) {
            $fields = ArrayHelper::getValue($company, 'FIELDS.' . $groupId);
            if (!$fields) {
                continue;
            }

            $stageVal = ArrayHelper::getValue($fields, 'construction-stage');

            if (!$stageVal) {
                continue;
            }

            $stage = ArrayHelper::getValue($stages, $stageVal, []);
            $res[] = $stage['name'];

            if (!$stage['extra']) {
                continue;
            }

            $valNum = ArrayHelper::getValue($company, 'FIELDS.' . $groupId . '.construction-permission-num');
            $valDate = ArrayHelper::getValue($company, 'FIELDS.' . $groupId . '.construction-permission-date');
//            $valFile = \CFile::GetFileArray(
//                ArrayHelper::getValue(
//                    $company,
//                    'FIELDS.' . $groupId . '.' . \Kelnik\Report\Model\ReportFieldsTable::FIELD_CONSTRUCTION_FILE
//                )
//            );

            $val = '№' . $valNum;
            if ($valDate) {
                $val .= ' от ' . $valDate;
            }

//            if (!empty($valFile['SRC'])) {
//                $val = '<a href="' . getSiteBaseUrl() . $valFile['SRC'] . '">' . $val . '</a>';
//            }

            $res[] = $val;
        }

        return $res;
    }

    protected static function getGroupValues(array $company, $type = 'groups')
    {
        $res = [];

        if (empty($company['GROUPS'])) {
            return $res;
        }

        foreach ($company['GROUPS'] as $groupId => $groupType) {
            if ($groupType !== $type) {
                continue;
            }

            $res[] = ArrayHelper::getValue($company['FIELDS'], $groupId, []);
        }

        return $res;
    }

    protected function sendFile()
    {
        ob_clean();
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="export_' . date('Y-m-d_H-i') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    protected static function getCurrentDate()
    {
        return '«' . date('d') . '» ' .
                mb_strtolower(FormatDate('F')) . ' ' .
                date('Y') . ' года';
    }

    protected static function normalizeFloat($val)
    {
        return str_replace(',', '.', $val);
    }

    protected function copyRow($rowSrc, $rowDst)
    {
        $sheet = &$this->spreadsheet->getActiveSheet();

        $sheet->insertNewRowBefore($rowDst);
//        $sheet->getColumnDimension();
//        $sheet->duplicateStyle($sheet->getStyle($rowSrc), $rowDst);
    }
}
