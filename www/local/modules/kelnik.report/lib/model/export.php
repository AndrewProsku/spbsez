<?php


namespace Kelnik\Report\Model;


use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserTable;
use Kelnik\Helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as CellFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

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
     * @var array
     */
    protected $statuses = [];

    /**
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    protected $spreadsheet;

    /**
     * @var boolean
     */
    protected $isSingle;

    /**
     * @var string
     */
    protected $tpl;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(int $year, int $type, array $companies = [], array $statuses = [], $tpl = 'tables.xlsx', $isSingle = false)
    {
        $this->year = $year;
        $this->type = $type;
        $this->companies = $companies;
        $this->statuses = $statuses;

        $this->companies = array_map(function ($el) {
            return (int)$el;
        }, $this->companies);

        $this->statuses = array_map(function ($el) {
            return (int)$el;
        }, $this->statuses);

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
                    $tpl
                ]
            )
        ));

        $this->isSingle = $isSingle;
    }

    public function getFile()
    {
        $this->importData();
        $this->sendFile();
    }

    /**
     * Выгрузка данных из БД в Excel
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function importData()
    {
        $this->spreadsheet->getDefaultStyle()->getProtection()->setHidden(Protection::PROTECTION_UNPROTECTED);
        $this->spreadsheet->getDefaultStyle()->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

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

            gc_collect_cycles();
        }

        $this->spreadsheet->setActiveSheetIndex(ReportFieldsTable::FORM_COMMON);
    }

    /**
     * Выборка данных из БД
     *
     * @param $formNum - номер формы (от 0 до 6)
     * @see ReportFieldsTable::getFormConfig
     */
    protected function loadData($formNum)
    {
        $this->data = [];

        try {
        	$filter = [
                '=COMPANY_ID' => $this->companies,
                '=TYPE' => $this->type,
                '=YEAR' => $this->year,
                '=FIELDS.FORM_NUM' => $formNum
            ];

            if (count($this->statuses) > 0) {
            	$filter['=STATUS_ID'] = $this->statuses;
            }

            $res = ReportsTable::getAssoc([
                'select' => [
                    'COMPANY_ID', 'YEAR', 'TYPE', 'NAME', 'NAME_SEZ', 'STATUS_ID',
                    'REPORT_FIELD_' => 'FIELDS',
                    'REPORT_FIELD_GROUP_' => 'FIELDS.GROUP'
                ],
                'runtime' => [
                    (new ExpressionField(
                        'COMPANY_ORIG_NAME',
                        '(SELECT `WORK_COMPANY` FROM `' . UserTable::getTableName() . '` WHERE `ID` = %s)',
                        'COMPANY_ID'
                    ))
                ],
                'filter' => $filter,
                'order' => [
                    'COMPANY_ORIG_NAME' => 'ASC'
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
                    'NAME' => htmlspecialchars_decode($v['NAME']),
                    'NAME_SEZ' => htmlspecialchars_decode($v['NAME_SEZ']),
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
        unset($res);
    }

    protected function processForm0()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        if ($this->isSingle) {
            $types = ReportsTable::getTypes();
            $sheet->setCellValue('L3', $types[$this->type] . ' ' . $this->year . ' года');
        } else {
            $sheet->setCellValue('L3', self::getCurrentDate());
        }

        $valueToCell = [
            'D' => 'foreign-investors',
            'E' => 'investors-countries',
            'F' => 'jobs-plan-all',
            'G' => 'jobs-plan-year',
            'H' => 'jobs-actual-all',
            'I' => 'jobs-actual-year',
            'J' => 'invests-plan-all',
            'K' => 'invests-plan-year',
            'L' => 'capital-invests-plan-all',
            'M' => 'capital-invests-plan-year',
            'N' => 'invests-all',
            'O' => 'invests-year',
            'P' => 'capital-invests-all',
            'Q' => 'capital-invests-year',
            'R' => 'revenue-all',
            'S' => 'revenue-year',
            'T' => 'revenue-year-extra',
            'U' => 'revenue-all-extra',
            'V' => 'produce-all',
            'W' => 'produce-year',
            'X' => 'salary',
           // 'Y' => 'salary-new',

        ];

        $rowNum = $start = 10;
        foreach ($this->data as $company) {
            if ($rowNum > $start) {
                $this->copyRow($rowNum - 1, $rowNum);
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - ($start - 1));
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $colName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                if ($valueName === 'foreign-investors') {
                    $val = $val == 'yes' ? 'да' : 'нет';
                } elseif ($valueName === 'investors-countries' && !$val) {
                    $val = 'Россия';
                } else {
                    $val = ReportFieldsTable::normalizeFloat($val);
                }

                $sheet->setCellValue($colName . $rowNum, $val);

                //принудительно ставим формат ячеек, если из шаблона не подтягивается
                if (in_array($colName, ['J', 'K', 'M', 'X'])) {
                    $sheet->getStyle($colName . $rowNum)->getNumberFormat()->setFormatCode(CellFormat::FORMAT_NUMBER_00);
                }
            }

            $rowNum++;
        }

        $valueToCell = array_slice($valueToCell, 2, count($valueToCell));
        $valueToCell = array_keys($valueToCell);

        $this->setCellSum($valueToCell, $rowNum, $start, $rowNum - 1);

        //принудительно ставим формат ячеек, если из шаблона не подтягивается
        foreach ($valueToCell as $cellName) {
            if (in_array($cellName, ['X'])) {
                $sheet->getStyle($cellName . $rowNum)->getNumberFormat()->setFormatCode(CellFormat::FORMAT_NUMBER_00);
            }
            if (in_array($cellName, ['F', 'G', 'H', 'I'])) {
                $sheet->getStyle($cellName . $rowNum)->getNumberFormat()->setFormatCode(CellFormat::FORMAT_NUMBER);
            }
        }
    }

    protected function processForm1()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        if ($this->isSingle) {
            $types = ReportsTable::getTypes();
            $sheet->setCellValue('K3', $types[$this->type] . ' ' . $this->year . ' года');
        } else {
            $sheet->setCellValue('K3', self::getCurrentDate());
        }

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

        $sumFieldsBrakes = ArrayHelper::getValue(ReportFieldsTable::getFormConfig(), ReportFieldsTable::FORM_TAXES . '.blocks.1.fields', []);
        $sumFieldsBrakes = array_column($sumFieldsBrakes, 'id');

        $rowNum = $start = 10;
        foreach ($this->data as $company) {

            if ($rowNum > $start) {
                $this->copyRow($rowNum - 1, $rowNum);
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - ($start - 1));
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            $total = [0,0];
            foreach ($sumFields as $sumField) {
                $k = 0;
                if (false !== strpos($sumField, '-year')) {
                    $k = 1;
                }

                $total[$k] = bcadd($total[$k], ArrayHelper::getValue($company, 'FIELDS.0.' . $sumField), ReportFieldsTable::FLOAT_SCALE);
            }

            $totalBreaks = [0,0];
            foreach ($sumFieldsBrakes as $sumField) {
                $k = 0;
                if (false !== strpos($sumField, '-year')) {
                    $k = 1;
                }

                $totalBreaks[$k] = bcadd($totalBreaks[$k], ArrayHelper::getValue($company, 'FIELDS.0.' . $sumField), ReportFieldsTable::FLOAT_SCALE);
            }

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = ReportFieldsTable::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            $sheet->setCellValue('D' . $rowNum, $total[0]);
            $sheet->setCellValue('E' . $rowNum, $total[1]);
            $sheet->setCellValue('P' . $rowNum, $totalBreaks[0]);
            $sheet->setCellValue('Q' . $rowNum, $totalBreaks[1]);

            $rowNum++;
        }

        $valueToCell = array_slice($valueToCell, 2, count($valueToCell));
        $valueToCell = array_keys($valueToCell);
        $valueToCell = array_merge($valueToCell, ['D', 'E']);

        $this->setCellSum($valueToCell, $rowNum, $start, $rowNum - 1);
    }

    protected function processForm2()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        if ($this->isSingle) {
            $types = ReportsTable::getTypes();
            $sheet->setCellValue('G4', $types[$this->type] . ' ' . $this->year . ' года');
        } else {
            $sheet->setCellValue('G4', self::getCurrentDate());
        }

        $valueToCell = [
            'D' => 'area-application',
            'E' => 'area-rent',
            'F' => 'area-property',
            'G' => 'object-name-plan',
            'H' => 'capital-object',
            'I' => 'construction-period'
        ];

//        $sheet->getStyle('A7:J9')->applyFromArray([
//            'protection' => [
//                'locked' => Protection::PROTECTION_UNPROTECTED,
//                'hidden' => Protection::PROTECTION_UNPROTECTED
//            ]
//        ]);

        $rowNum = $start = 9;

        foreach ($this->data as $company) {

            if ($rowNum > $start) {
                $this->copyRow($rowNum - 1, $rowNum);
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - ($start - 1));
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = ReportFieldsTable::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            if ($rowValue = self::getStages($company)) {
//                $richText = new RichText();
//                $richText->createText(implode(", \n", $rowValue));

                $cell = $sheet->getCell('J' . $rowNum);

                $cell->setValue(implode(", \n", $rowValue));
                $cell->getStyle()->getAlignment()->setWrapText(true);
            }

            $rowNum++;
        }
    }

    protected function processForm3()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        if ($this->isSingle) {
            $types = ReportsTable::getTypes();
            $sheet->setCellValue('E4', $types[$this->type] . ' ' . $this->year . ' года');
        } else {
            $sheet->setCellValue('E4', self::getCurrentDate());
        }

        $valueToCell = [
            'D' => 'office-application',
            'E' => 'office-rent'
        ];

        $rowNum = $start = 9;

        foreach ($this->data as $company) {
            if ($rowNum > $start) {
                $this->copyRow($rowNum - 1, $rowNum);
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - ($start - 1));
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = ReportFieldsTable::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            if ($rowValue = self::getStages($company)) {
                $richText = new RichText();
                $richText->createText(implode(", \n", $rowValue));

                $cell = $sheet->getCell('F' . $rowNum);

                $cell->setValue($richText);
                $cell->getStyle()->getAlignment()->setWrapText(true);
            }

            $rowNum++;
        }
    }

    protected function processForm4()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        if ($this->isSingle) {
            $types = ReportsTable::getTypes();
            $sheet->setCellValue('G3', $types[$this->type] . ' ' . $this->year . ' года');
        } else {
            $sheet->setCellValue('G3', self::getCurrentDate());
        }

        $valueToCell = [
            'D' => 'export-volume-all',
            'E' => 'export-volume-year',
            'H' => 'high-tech-production',
            'J' => 'high-productive-jobs'
        ];

        $rowNum = 8;
        $start = 9;

        $groupValueToCell = [
            'groups' => [
                'F' => 'export-countries',
                'G' => 'export-code'
            ],
            'innovations' => [
                'I' => 'innovation'
            ]
        ];

        foreach ($this->data as $company) {

            $rowNum++;

            if ($rowNum > $start) {
                $this->copyRow($rowNum - 1, $rowNum);
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - ($start - 1));
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                if ($valueName == 'high-tech-production') {
                    $val = $val == 'yes' ? 'да' : 'нет';
                } else {
                    $val = ReportFieldsTable::normalizeFloat($val);
                }

                $sheet->setCellValue($cellName . $rowNum, $val);

                //принудительно ставим формат ячеек, если из шаблона не подтягивается
                if (in_array($cellName, ['D', 'E'])) {
                    $sheet->getStyle($cellName . $rowNum)->getNumberFormat()->setFormatCode(CellFormat::FORMAT_NUMBER_00);
                }
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
        $sheet = $this->spreadsheet->getActiveSheet();

        if ($this->isSingle) {
            $types = ReportsTable::getTypes();
            $sheet->setCellValue('B4', 'за период ' . $types[$this->type] . ' ' . $this->year . ' года');
        } else {
            $sheet->setCellValue('B4', 'по состоянию на ' . self::getCurrentDate());
        }

        $valueToCell = [
            'D' => 'intangible-assets',
            'E' => 'degrees-employees',
        ];

        $rowNum = $start = 9;
        foreach ($this->data as $company) {

            if ($rowNum > $start) {
                $this->copyRow($rowNum - 1, $rowNum);
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - ($start - 1));
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                $val = ReportFieldsTable::normalizeFloat($val);

                $sheet->setCellValue($cellName . $rowNum, $val);
            }

            $rowNum++;
        }
    }

    protected function processForm6()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        if ($this->isSingle) {
            $types = ReportsTable::getTypes();
            $sheet->setCellValue('E4', $types[$this->type] . ' ' . $this->year . ' года');
        } else {
            $sheet->setCellValue('E4', self::getCurrentDate());
        }

        $valueToCell = [
            'D' => 'result-type',
            'E' => 'result-description',
            'F' => 'result-date',
            'G' => 'result-commercialization'
        ];

        $rowNum = 7;
        $companyNum = 1;
        $mergeCells = [];

        $r = [];

        foreach ($this->data as $company) {
            $rowNum++;
            $rowValueNum = $rowNum;

            if ($rowValueNum > 8) {
                $this->copyRow($rowValueNum - 1, $rowValueNum);
            }

            $r[0][] = $rowNum;

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $companyNum++);
            $sheet->setCellValue('B' . $rowNum, $company['NAME']);
            $sheet->setCellValue('C' . $rowNum, $company['NAME_SEZ']);

            $mergeCells[$companyNum][0] = $rowNum;

            $values = self::getGroupValues($company, 'results');

            if (!$values) {
                continue;
            }

            $needNewRow = false;
            foreach ($values as $rowValues) {
                if ($needNewRow) {
                    $this->copyRow($rowValueNum - 1, $rowValueNum);
                }
                foreach ($valueToCell as $cellName => $valueName) {
                    $sheet->setCellValue($cellName . $rowValueNum, ArrayHelper::getValue($rowValues, $valueName));
                    $sheet->getCell($cellName . $rowValueNum)->getStyle()->getAlignment()->setWrapText(true);
                }
                $needNewRow = true;
                $rowValueNum++;

                $r[1][] = $rowValueNum;
            }

            $rowNum = $rowValueNum - 1;

            $mergeCells[$companyNum][1] = $rowNum;
        }

        /*foreach ($mergeCells as $company) {
            foreach (['A', 'B', 'C'] as $col) {
                $sheet->mergeCells($col . $company[0] . ':' . $col . $company[1]);
            }
        }*/
    }

    protected function processForm7()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        $types = ReportsTable::getTypes();
        $sheet->setCellValue('A4', $types[$this->type] . ' ' . $this->year . ' года');

        $valueToCell = [
            'B' => 'project-category',
            'C' => 'project-name',
            'D' => 'project-description',
            'E' => 'project-inn',
            'F' => 'project-region',
            'G' => 'project-okved',
            'H' => 'project-group-okved',
            'I' => 'project-code-okved',
            'J' => 'project-area',
            'K' => 'project-minister',
            'L' => 'project-department',
            'M' => 'project-mer',
            'N' => 'project-maintain',
            'O' => 'project-effect',
            'P' => 'project-measure',
            'Q' => 'project-year-invest-start',
            'R' => 'project-year-invest-end',
            'S' => 'project-year-start',
            'T' => 'project-year-power',
            'U' => 'project-years-realization',
            'V' => 'project-people',
            'W' => 'project-finance-all',
            'X' => 'project-finance-federal',
            'Y' => 'project-finance-regional',
            'Z' => 'project-finance-local',
            'AA' => 'project-funds-all',
            'AB' => 'project-funds-private',
            'AC' => 'project-funds-federal',
            'AD' => 'project-funds-nonbudget',
            'AE' => 'project-raised-all',
            'AF' => 'project-raised-bank',
            'AG' => 'project-raised-loan',
            'AH' => 'project-raised-other'
        ];

        $floatCells = ['P', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH'];
        $stringCells = ['E', 'Q', 'R', 'S', 'T'];

        $okvedSections = \Kelnik\Report\Model\ReportFieldsTable::getOkvedSections();
        $okvedGroups = \Kelnik\Report\Model\ReportFieldsTable::getOkvedGroups();
        $okvedCodes = \Kelnik\Report\Model\ReportFieldsTable::getOkvedCodes();
        $projectAreas = \Kelnik\Report\Model\ReportFieldsTable::getProjectAreas();

        $rowNum = $start = 8;

        foreach ($this->data as $company) {

            if ($rowNum > $start) {
                $this->copyRow($rowNum - 1, $rowNum);
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(-1);
            $sheet->setCellValue('A' . $rowNum, $rowNum - ($start - 1));

            foreach ($valueToCell as $cellName => $valueName) {
                $val = trim(ArrayHelper::getValue($company, 'FIELDS.0.' . $valueName));
                if (in_array($cellName, $floatCells)) {
                    $val = ReportFieldsTable::normalizeFloat($val);
                }
                if ($valueToCell[$cellName] == 'project-okved') {
                    $val = \Kelnik\Helpers\ArrayHelper::getValue($okvedSections, $val);
                }
                if ($valueToCell[$cellName] == 'project-group-okved') {
                    $val = \Kelnik\Helpers\ArrayHelper::getValue($okvedGroups, $val);
                }
                if ($valueToCell[$cellName] == 'project-code-okved') {
                    $val = \Kelnik\Helpers\ArrayHelper::getValue($okvedCodes, $val);
                }
                if ($valueToCell[$cellName] == 'project-area') {
                    $val = \Kelnik\Helpers\ArrayHelper::getValue($projectAreas, $val);
                }

                if (in_array($cellName, $stringCells)) {
                    $sheet->setCellValueExplicit(
                        $cellName . $rowNum,
                        $val,
                        DataType::TYPE_STRING
                    );
                } else {
                    $sheet->setCellValue($cellName . $rowNum, $val);
                }

                //принудительно ставим формат ячеек, если из шаблона не подтягивается
                if (in_array($cellName, $floatCells)) {
                    $sheet->getStyle($cellName . $rowNum)->getNumberFormat()->setFormatCode(CellFormat::FORMAT_NUMBER_00);
                }
            }

            /*if ($rowValue = self::getStages($company)) {
                $cell = $sheet->getCell('J' . $rowNum);

                $cell->setValue(implode(", \n", $rowValue));
                $cell->getStyle()->getAlignment()->setWrapText(true);
            }*/

            $rowNum++;
        }

    }

    /**
     * Устанавливает формулу суммы в ячейку
     *
     * @param array $cols
     * @param int $rowSet
     * @param int $rowStart
     * @param int $rowEnd
     */
    protected function setCellSum(array $cols, int $rowSet, int $rowStart, int $rowEnd)
    {
        foreach ($cols as $col) {
            $this->spreadsheet->getActiveSheet()->setCellValue($col . $rowSet, '=SUM(' . $col . $rowStart . ':' . $col . $rowEnd . ')');
        }
    }

    /**
     * Получения групп полей по типу stages
     *
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

            $val = '№ ' . $valNum;
            if ($valDate) {
                $val .= ' от ' . $valDate;
            }

//            if (!empty($valFile['SRC'])) {
//                $val = '<a href="' . getSiteBaseUrl() . $valFile['SRC'] . '">' . $val . '</a>';
//            }

            $res[] = $val;
        }

        unset($company);

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

        unset($company);

        return $res;
    }

    protected function sendFile()
    {
        $dir = REPORTS_DIR;
        $files = glob($dir . "*");
        $c = count($files);
        if (count($files) > 0) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        ob_clean();
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');

        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Disposition: attachment; filename="export_' . date('Y-m-d_H-i') . '.xlsx"');
        //header('Cache-Control: max-age=0');

        $writer->save($dir . 'export_' . date('Y-m-d_H-i') . '.xlsx');
        header('Location: /upload/reports/export_' . date('Y-m-d_H-i') . '.xlsx');

        exit;
    }

    protected static function getCurrentDate()
    {
        return '«' . date('d') . '» ' .
                mb_strtolower(FormatDate('F')) . ' ' .
                date('Y') . ' года';
    }

    /**
     * Добавление новой строки через копирование
     *
     * @param int $rowNumSrc
     * @param int $rowNumDst
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function copyRow(int $rowNumSrc, int $rowNumDst)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->insertNewRowBefore($rowNumDst);
        $cols = $sheet->getColumnDimensions();
        foreach ($cols as $colNum => $coll) {
            $sheet->duplicateStyle(
                $sheet->getCell($colNum . $rowNumSrc)->getStyle()->getSharedComponent(),
                $colNum . $rowNumDst
            );
        }
        unset($sheet, $cell);
    }
}
