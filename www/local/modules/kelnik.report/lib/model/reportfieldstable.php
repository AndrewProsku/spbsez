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

            (new StringField('PARENT_FIELD_NAME'))
                ->configureSize(100),
            (new StringField('FIELD_NAME'))
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

    public static function getForms()
    {
        return [
            self::FORM_COMMON => [
                'blocks' => [
                    [
                        'type' => 'foreign-investors',
                        'fields' => [
                            [
                                'id' => 'foreign-investors',
                                'suffix' => 'yes',
                                'trueValue' => 'yes',
                                'type' => 'boolean'
                            ],
                            [
                                'id' => 'foreign-investors',
                                'suffix' => 'no',
                                'trueValue' => 'no',
                                'type' => 'boolean'
                            ],
                            'investors-countries'
                        ]
                    ],
                    [
                        'fields' => [
                            'jobs-plan-all',
                            'jobs-plan-year',
                            'jobs-actual-all',
                            'jobs-actual-year'
                        ]
                    ],
                    [
                        'fields' => [
                            'invests-plan-all',
                            'invests-plan-year',
                            'capital-invests-plan-all',
                            'capital-invests-plan-year'
                        ]
                    ],
                    [
                        'fields' => [
                            'invests-all',
                            'invests-year',
                            'capital-invests-all',
                            'capital-invests-year'
                        ]
                    ],
                    [
                        'fields' => [
                            'revenue-all',
                            'revenue-year'
                        ]
                    ],
                    [
                        'fields' => [
                            'produce-all',
                            'produce-year'
                        ]
                    ],
                    [
                        'fields' => ['salary']
                    ]
                ]
            ],
            self::FORM_TAXES => [
                'blocks' => [
                    [
                        'type' => 'taxes',
                        'fields' => [
                            'taxes-federal-all',
                            'taxes-federal-year',
                            'taxes-regional-all',
                            'taxes-regional-year',
                            'taxes-local-all',
                            'taxes-local-year',
                            'taxes-offbudget-all',
                            'taxes-offbudget-year',
                            'taxes-nds-all',
                            'taxes-nds-year'
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
                        'stages' => [
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
                'blocks' => [
                    [
                        'fields' => ['office-application']
                    ],
                    [
                        'fields' => ['office-rent']
                    ],
                    [
                        'type' => 'construction-stage',
                        'stages' => [
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
                'blocks' => [
                    [
                        'fields' => [
                            'export-volume-all',
                            'export-volume-year'
                        ]
                    ],
                    [
                        'type' => 'export-countries',
                        'groups' => [
                            'export-countries',
                            'export-code'
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
                        'innovations' => ['innovation']
                    ],
                    [
                        'fields' => ['high-productive-jobs']
                    ]
                ]
            ],
            self::FORM_ADDITIONAL_INDICATORS => [
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
                'type' => 'results',
                'fields' => [
                    'result-type',
                    'result-description',
                    'result-date',
                    'result-commercialization'
                ]
            ]
        ];
    }
}
