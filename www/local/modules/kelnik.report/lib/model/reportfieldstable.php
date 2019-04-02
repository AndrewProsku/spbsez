<?php

namespace Kelnik\Report\Model;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
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
            (new StringField('NAME'))
                ->configureSize(100),
            (new StringField('VALUE'))
                ->configureSize(255),

            (new Reference(
                'REPORT',
                ReportsTable::class,
                Join::on('this.REPORT_ID', 'ref.ID')
            ))->configureJoinType('INNER')
        ];
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
                                'exclude' => true
                            ],
                            [
                                'id' => 'investors-countries',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-1-2')
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2'),
                        'fields' => [
                            [
                                'id' => 'jobs-plan-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-1')
                            ],
                            [
                                'id' => 'jobs-plan-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-2')
                            ],
                            [
                                'id' => 'jobs-actual-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-3')
                            ],
                            [
                                'id' => 'jobs-actual-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-2-4')
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3'),
                        'fields' => [
                            [
                                'id' => 'invests-plan-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-1')
                            ],
                            [
                                'id' => 'invests-plan-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-2')
                            ],
                            [
                                'id' => 'capital-invests-plan-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-3')
                            ],
                            [
                                'id' => 'capital-invests-plan-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-3-4')
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4'),
                        'fields' => [
                            [
                                'id' => 'invests-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-1')
                            ],
                            [
                                'id' => 'invests-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-2')
                            ],
                            [
                                'id' => 'capital-invests-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-3')
                            ],
                            [
                                'id' => 'capital-invests-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-4-4')
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5'),
                        'fields' => [
                            [
                                'id' => 'revenue-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5-1')
                            ],
                            [
                                'id' => 'revenue-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-5-2')
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-6'),

                        'fields' => [
                            [
                                'id' => 'produce-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-6-1')
                            ],
                            [
                                'id' => 'produce-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-6-2')
                            ]
                        ]
                    ],
                    [
                        'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_1-7'),
                        'fields' => [
                            ['id' => 'salary']
                        ]
                    ]
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
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-1')
                            ],
                            [
                                'id' => 'taxes-federal-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-1-2')
                            ],
                            [
                                'id' => 'taxes-regional-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-1')
                            ],
                            [
                                'id' => 'taxes-regional-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-2-2')
                            ],
                            [
                                'id' => 'taxes-local-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-3-1')
                            ],
                            [
                                'id' => 'taxes-local-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-3-2')
                            ],
                            [
                                'id' => 'taxes-offbudget-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-4-1')
                            ],
                            [
                                'id' => 'taxes-offbudget-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-4-2')
                            ],
                            [
                                'id' => 'taxes-nds-all',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-5-1')
                            ],
                            [
                                'id' => 'taxes-nds-year',
                                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_2-5-2')
                            ]
                        ]
                    ],
                    [
                        'fields' => [
                            'taxes-breaks-all',
                            'taxes-breaks-year',
                            'taxes-breaks-federal-all',
                            'taxes-breaks-federal-year',
                            'taxes-breaks-local-all',
                            'taxes-breaks-local-year',
                            'taxes-breaks-offbudget-all',
                            'taxes-breaks-offbudget-year'
                        ]
                    ],
                    [
                        'fields' => [
                            'custom-duties-all',
                            'custom-duties-year'
                        ]
                    ],
                    [
                        'fields' => [
                            'custom-duties-breaks-all',
                            'custom-duties-breaks-year'
                        ]
                    ]
                ]
            ],
            self::FORM_BUILDING => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_3'),
                'blocks' => [
                    [
                        'fields' => ['area-application']
                    ],
                    [
                        'fields' => [
                            'area-rent',
                            'area-property'
                        ]
                    ],
                    [
                        'fields' => ['object-name-plan']
                    ],
                    [
                        'fields' => ['capital-object']
                    ],
                    [
                        'fields' => ['construction-period']
                    ],
                    [
                        'type' => 'construction-stage',
                        'multiple' => [
                            'name' => 'stages',
                            'id' => 'stageID',
                            'fields' => [
                                'construction-stage',
                                'construction-permission-num',
                                'construction-permission-file',
                                'construction-permission-date'
                            ]
                        ]
                    ]
                ]
            ],
            self::FORM_RENT => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_4'),
                'blocks' => [
                    [
                        'fields' => ['office-application']
                    ],
                    [
                        'fields' => ['office-rent']
                    ],
                    [
                        'type' => 'construction-stage',
                        'multiple' => [
                            'name' => 'stages',
                            'id' => 'stageID',
                            'fields' => [
                                'construction-stage',
                                'construction-permission-num',
                                'construction-permission-file',
                                'construction-permission-date'
                            ]
                        ]
                    ]
                ]
            ],
            self::FORM_INDICATORS => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_5'),
                'blocks' => [
                    [
                        'fields' => [
                            'export-volume-all',
                            'export-volume-year'
                        ]
                    ],
                    [
                        'type' => 'export-countries',
                        'multiple' => [
                            'name' => 'groups',
                            'id' => 'ID',
                            'fields' => [
                                'export-countries',
                                'export-code'
                            ]
                        ]
                    ],
                    [
                        'fields' => [
                            [
                                'id' => 'high-tech-production',
                                'suffix' => 'yes',
                                'trueValue' => 'yes',
                                'type' => 'boolean'
                            ],
                            [
                                'id' => 'high-tech-production',
                                'suffix' => 'no',
                                'trueValue' => 'no',
                                'type' => 'boolean'
                            ]
                        ]
                    ],
                    [
                        'type' => 'innovations',
                        'multiple' => [
                            'name' => 'innovations',
                            'id' => 'ID',
                            'fields' => ['innovation']
                        ]
                    ],
                    [
                        'fields' => ['high-productive-jobs']
                    ]
                ]
            ],
            self::FORM_ADDITIONAL_INDICATORS => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_6'),
                'blocks' => [
                    [
                        'fields' => ['intangible-assets']
                    ],
                    [
                        'fields' => ['degrees-employees']
                    ]
                ]
            ],
            self::FORM_RESULT => [
                'title' => Loc::getMessage('KELNIK_REPORT_FIELDS_FORM_7'),
                'type' => 'results',
                'blocks' => [
                    [
                        'type' => 'results',
                        'fields' => [
                            'result-type',
                            'result-description',
                            'result-date',
                            'result-commercialization'
                        ]
                    ]
                ]
            ]
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
                'extra' => true
            ],
            'stage5' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE5'),
                'extra' => false
            ],
            'stage6' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE6'),
                'extra' => true
            ],
            'stage7' => [
                'name' => Loc::getMessage('KELNIK_REPORT_FIELDS_STAGE7'),
                'extra' => false
            ]
        ];
    }
}
