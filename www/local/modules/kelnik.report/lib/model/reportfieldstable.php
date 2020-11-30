<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\Database\DataManager;

Loc::loadMessages(__FILE__);

class ReportFieldsTable extends DataManager
{
    public const FORM_COMMON = 0;
    public const FORM_TAXES = 1;
    public const FORM_BUILDING = 2;
    public const FORM_RENT = 3;
    public const FORM_INDICATORS = 4;
    public const FORM_ADDITIONAL_INDICATORS = 5;
    public const FORM_RESULT = 6;

    public const FIELD_CONSTRUCTION_FILE = 'construction-permission-file';
    public const FIELD_CONSTRUCTION_DATE = 'construction-permission-date';

    public const FLOAT_SCALE = 6;

    public static $defaultValues = [
        /*'foreign-investors'    => [
            'VALUE'    => 'no',
            'FORM_NUM' => self::FORM_COMMON
        ],
        'high-tech-production' => [
            'VALUE'    => 'no',
            'FORM_NUM' => self::FORM_INDICATORS
        ]*/
    ];

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'kelnik_report_fields';
    }

    public static function getCollectionClass()
    {
        return Fields::class;
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configureAutocomplete(true)
                ->configurePrimary(true)
                ->configureTitle(Loc::getMessage('KELNIK_REPORT_ID')),

            (new IntegerField('REPORT_ID'))
                ->configureRequired(true),

            (new IntegerField('GROUP_ID'))
                ->configureDefaultValue(0),
            (new IntegerField('FORM_NUM'))
                ->configureDefaultValue(0),
            (new BooleanField('IS_PRE_FILLED'))
                ->configureValues(self::NO, self::YES)
                ->configureDefaultValue(self::NO),
            (new StringField('NAME'))
                ->configureSize(100),
            (new StringField('VALUE'))
                ->configureSize(255),
            (new StringField('COMMENT'))
                ->configureSize(255),

            (new Reference(
                'REPORT',
                ReportsTable::class,
                Join::on('this.REPORT_ID', 'ref.ID')
            ))->configureJoinType('INNER'),

            (new Reference(
                'GROUP',
                ReportFieldsGroupTable::class,
                Join::on('this.GROUP_ID', 'ref.ID')
            ))->configureJoinType('LEFT')
        ];
    }

    /**
     * Добавление полей с значениями по-умолчанию
     *
     * @param int $reportId
     */
    public static function addDefaultFields(int $reportId)
    {
        if (!$reportId) {
            return;
        }

        $values = [];
        $sqlHelper = Application::getConnection()->getSqlHelper();

        foreach (self::$defaultValues as $field => $params) {
            $values[] = '(' .
                            $sqlHelper->convertToDbInteger($reportId) . ', ' .
                            $sqlHelper->convertToDbString($field) . ', ' .
                            $sqlHelper->convertToDbInteger($params['FORM_NUM']) . ', ' .
                            $sqlHelper->convertToDbString($params['VALUE']) .
                        ')';
        }

        try {
            Application::getConnection()->query(
                'INSERT INTO `' . self::getTableName() . '` (`REPORT_ID`, `NAME`, `FORM_NUM`, `VALUE`) '.
                'VALUES ' . implode(', ', $values)
            );
        } catch (\Exception $e) {
        }
    }

    /**
     * Добавление полей для групп
     *
     * @param int $reportId
     * @param int $formNum
     * @param $groupType
     * @param int $groupId
     */
    public static function addFieldsByGroup(int $reportId, int $formNum, $groupType, int $groupId)
    {
        $blocks = ArrayHelper::getValue(ReportFieldsTable::getFormConfig(), $formNum . '.blocks', []);

        if (!$blocks || !$reportId) {
            return;
        }

        $values = [];

        $sqlHelper = Application::getConnection()->getSqlHelper();

        foreach ($blocks as $block) {
            if (empty($block['multiple']['name']) || $block['multiple']['name'] !== $groupType) {
                continue;
            }

            foreach ($block['multiple']['fields'] as $field) {
                $values[] = '(' .
                    $sqlHelper->convertToDbInteger($reportId). ', ' .
                    $sqlHelper->convertToDbInteger($groupId) . ', ' .
                    $sqlHelper->convertToDbInteger($formNum) . ', ' .
                    $sqlHelper->convertToDbString($field['id']) .
                    ')';
            }
        }

        if (!$values) {
            return;
        }

        try {
            Application::getConnection()->query(
                'INSERT INTO `' . self::getTableName() . '` (`REPORT_ID`, `GROUP_ID`, `FORM_NUM`, `NAME`) '.
                'VALUES ' . implode(', ', $values)
            );
        } catch (\Exception $e) {
        }
    }

    public static function getFormConfig()
    {
        return [
            self::FORM_COMMON => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_1'),
                'blocks' => [
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-1'),
                        'type' => 'foreign-investors',
                        'fields' => [
                            [
                                'id' => 'foreign-investors',
                                'suffix' => 'yes',
                                'trueValue' => 'yes',
                                'type' => 'boolean',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-1-1')
                            ],
                            [
                                'id' => 'foreign-investors',
                                'suffix' => 'no',
                                'trueValue' => 'no',
                                'type' => 'boolean',
                                'excludeAdmin' => true
                            ],
                            [
                                'id' => 'investors-countries',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-1-2'),
                                'type' => 'text'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2'),
                        'fields' => [
                            [
                                'id' => 'jobs-plan-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-1'),
                                'type' => 'int'
                            ],
                            [
                                'id' => 'jobs-plan-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-2'),
                                'type' => 'int'
                            ],
                            [
                                'id' => 'jobs-actual-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-3'),
                                'type' => 'int'
                            ],
                            [
                                'id' => 'jobs-actual-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-4'),
                                'type' => 'int'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3'),
                        'fields' => [
                            [
                                'id' => 'invests-plan-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'invests-plan-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-2'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'capital-invests-plan-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-3'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'capital-invests-plan-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-4'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4'),
                        'fields' => [
                            [
                                'id' => 'invests-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'invests-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-2'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'capital-invests-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-3'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'capital-invests-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-4'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5'),
                        'fields' => [
                            [
                                'id' => 'revenue-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'revenue-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5-3'),
                                'type' => 'float'
                            ],                            
                            [
                                'id' => 'extra-title-for-revenue',
                                'extra-title' => true,
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5-5'),
                            ],                            
                            [
                                'id' => 'revenue-year-extra',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5-2'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'revenue-all-extra',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5-4'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-6'),

                        'fields' => [
                            [
                                'id' => 'produce-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-6-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'produce-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-6-2'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-7'),
                        'fields' => [
                            [
                                'id' => 'salary',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-7'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                ]
            ],
            self::FORM_TAXES => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_2'),
                'blocks' => [
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1'),
                        'type' => 'taxes',
                        'fields' => [
                            [
                                'id' => 'taxes-federal-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-federal-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-2'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-regional-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-3'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-regional-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-4'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-local-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-5'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-local-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-6'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-offbudget-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-7'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-offbudget-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-8'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-nds-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-9'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-nds-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-10'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2'),
                        'type' => 'taxes',
                        'fields' => [
//                            [
//                                'id' => 'taxes-breaks-all',
//                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-1'),
//                                'type' => 'float'
//                            ],
//                            [
//                                'id' => 'taxes-breaks-year',
//                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-2'),
//                                'type' => 'float'
//                            ],
                            [
                                'id' => 'taxes-breaks-federal-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-3'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-breaks-federal-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-4'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-breaks-local-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-5'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-breaks-local-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-6'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-breaks-offbudget-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-7'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'taxes-breaks-offbudget-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-8'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-3'),
                        'fields' => [
                            [
                                'id' => 'custom-duties-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-3-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'custom-duties-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-3-2'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-4'),
                        'fields' => [
                            [
                                'id' => 'custom-duties-breaks-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-4-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'custom-duties-breaks-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-4-2'),
                                'type' => 'float'
                            ]
                        ]
                    ]
                ]
            ],
            self::FORM_BUILDING => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_3'),
                'blocks' => [
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-1'),
                        'fields' => [
                            [
                                'id' => 'area-application',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-1-1'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-2'),
                        'fields' => [
                            [
                                'id' => 'area-rent',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-2-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'area-property',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-2-2'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-3'),

                        'fields' => [
                            [
                                'id' => 'object-name-plan',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-3-1'),
                                'type' => 'text'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-4'),

                        'fields' => [
                            [
                                'id' => 'capital-object',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-4-1'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-5'),

                        'fields' => [
                            [
                                'id' => 'construction-period',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-5-1'),
                                'type' => 'text'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-6'),
                        'type' => 'construction-stage',
                        'multiple' => [
                            'name' => 'stages',
                            'id' => 'stageID',
                            'fields' => [
                                [
                                    'id' => 'construction-stage',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-6-1'),
                                    'type' => 'text'
                                ],
                                [
                                    'id' => 'construction-permission-num',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-6-2'),
                                    'type' => 'text'
                                ],
                                [
                                    'id' => 'construction-permission-file',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-6-3'),
                                    'type' => 'int'
                                ],
                                [
                                    'id' => 'construction-permission-date',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_3-6-4'),
                                    'type' => 'date'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            self::FORM_RENT => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_4'),
                'blocks' => [
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-1'),
                        'fields' => [
                            [
                                'id' => 'office-application',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-1-1'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-2'),
                        'fields' => [
                            [
                                'id' => 'office-rent',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-1-1'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-3'),
                        'type' => 'construction-stage',
                        'multiple' => [
                            'name' => 'stages',
                            'id' => 'stageID',
                            'fields' => [
                                [
                                    'id' => 'construction-stage',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-3-1'),
                                    'type' => 'text'
                                ],
                                [
                                    'id' => 'construction-permission-num',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-3-2'),
                                    'type' => 'text'
                                ],
                                [
                                    'id' => 'construction-permission-file',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-3-3'),
                                    'type' => 'int'
                                ],
                                [
                                    'id' => 'construction-permission-date',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_4-3-4'),
                                    'type' => 'date'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            self::FORM_INDICATORS => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_5'),
                'blocks' => [
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-1'),
                        'fields' => [
                            [
                                'id' => 'export-volume-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-1-1'),
                                'type' => 'float'
                            ],
                            [
                                'id' => 'export-volume-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-1-2'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-2'),
                        'type' => 'export-countries',
                        'multiple' => [
                            'name' => 'groups',
                            'id' => 'ID',
                            'fields' => [
                                [
                                    'id' => 'export-countries',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-2-1'),
                                    'type' => 'text'
                                ],
                                [
                                    'id' => 'export-code',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-2-2'),
                                    'type' => 'text'
                                ]
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-3'),
                        'fields' => [
                            [
                                'id' => 'high-tech-production',
                                'suffix' => 'yes',
                                'trueValue' => 'yes',
                                'type' => 'boolean',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-3-1')
                            ],
                            [
                                'id' => 'high-tech-production',
                                'suffix' => 'no',
                                'trueValue' => 'no',
                                'type' => 'boolean',
                                'excludeAdmin' => true
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-4'),
                        'type' => 'innovations',
                        'multiple' => [
                            'name' => 'innovations',
                            'id' => 'ID',
                            'fields' => [
                                [
                                    'id' => 'innovation',
                                    'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-4-1'),
                                    'type' => 'text'
                                ]
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-5'),
                        'fields' => [
                            [
                                'id' => 'high-productive-jobs',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_5-5-1'),
                                'type' => 'int'
                            ]
                        ]
                    ]
                ]
            ],
            self::FORM_ADDITIONAL_INDICATORS => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_6'),
                'blocks' => [
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_6-1'),
                        'fields' => [
                            [
                                'id' => 'intangible-assets',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_6-1-1'),
                                'type' => 'float'
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_6-2'),
                        'fields' => [
                            [
                                'id' => 'degrees-employees',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_6-2-1'),
                                'type' => 'int'
                            ]
                        ]
                    ]
                ]
            ],
            self::FORM_RESULT => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_7'),
                'type' => 'results',
                'blocks' => [
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_7-1'),
                        'type' => 'results',
                        'fields' => [
                            [
                                'id' => 'result-type',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_7-1-1'),
                                'type' => 'longText'
                            ],
                            [
                                'id' => 'result-description',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_7-1-2'),
                                'type' => 'longText'
                            ],
                            [
                                'id' => 'result-date',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_7-1-3'),
                                'type' => 'longText'
                            ],
                            [
                                'id' => 'result-commercialization',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_7-1-4'),
                                'type' => 'longText'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function getFormFieldType($fieldName, $formNum)
    {
        $blocks = ArrayHelper::getValue(self::getFormConfig(), $formNum . '.blocks', []);

        foreach ($blocks as $block) {

            $fields = !empty($block['multiple']['fields'])
                        ? $block['multiple']['fields']
                        : ArrayHelper::getValue($block, 'fields', []);

            if (!$fields) {
                continue;
            }

            foreach ($fields as $field) {
                if ($field['id'] === $fieldName) {
                    return ArrayHelper::getValue($field, 'type', 'text');
                }
            }
        }

        return false;
    }

    public static function getFieldTypes()
    {
        return [
            'float' => function ($val) {
                return self::normalizeFloat($val);
            },
            'int' => function ($val) {
                return (int) self::normalizeDigital($val);
            },
            'text' => function ($val) {
                return substr(
                    htmlspecialchars(strip_tags((string)$val), ENT_QUOTES, 'UTF-8'),
                    0,
                    255
                );
            },
            'longText' => function ($val) {
                return substr(
                    htmlspecialchars(strip_tags((string)$val), ENT_QUOTES, 'UTF-8'),
                    0,
                    1000
                );
            },
            'boolean' => function ($val) {
                return ((string)$val) === 'yes' ? 'yes' : 'no';
            },
            'date' => function ($val) {
                return preg_replace('![^0-9\.]!si', '', (string)$val);
            }
        ];
    }

    /**
     * Стадии строительства
     * `extra` - требует дополнительных полей
     *
     * @return array
     */
    public static function getStages()
    {
        return [
            'stage1' => [
                'name'  => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE1'),
                'extra' => false
            ],
            'stage2' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE2'),
                'extra' => false
            ],
            'stage3' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE3'),
                'extra' => false
            ],
            'stage4' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE4'),
                'extra' => false
            ],
            'stage5' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE5'),
                'extra' => false
            ],
            'stage6' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE6'),
                'extra' => false
            ],
            'stage7' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE7'),
                'extra' => false
            ]
        ];
    }

    public static function normalizeDigital($val)
    {
        return str_replace([' ', ','], ['', '.'], $val);
    }

    public static function normalizeFloat($val)
    {
        $val = self::normalizeDigital($val);

        return bcadd($val, 0, self::FLOAT_SCALE);
    }
}
