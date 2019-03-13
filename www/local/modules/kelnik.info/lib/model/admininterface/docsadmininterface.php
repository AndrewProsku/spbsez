<?php

namespace Kelnik\Info\Model\AdminInterface;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;
use Kelnik\AdminHelper\Helper\AdminInterface;
use Kelnik\AdminHelper\Widget\CheckboxWidget;
use Kelnik\AdminHelper\Widget\ComboBoxWidget;
use Kelnik\AdminHelper\Widget\DateTimeWidget;
use Kelnik\AdminHelper\Widget\FileWidget;
use Kelnik\AdminHelper\Widget\NumberWidget;
use Kelnik\AdminHelper\Widget\StringWidget;
use Kelnik\Info\Model\DocsTable;
use Kelnik\Info\Model\TypesTable;
use Kelnik\Refbook\Types;

Loc::loadMessages(__FILE__);

class DocsAdminInterface extends AdminInterface
{
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME'   => Loc::getMessage('KELNIK_FIELDS_MAIN'),
                'FIELDS' => [
                    'ID'       => [
                        'WIDGET'           => new NumberWidget(),
                        'READONLY'         => true,
                        'FILTER'           => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'ACTIVE'   => [
                        'WIDGET'   => new CheckboxWidget(),
                        'DEFAULT'  => DocsTable::YES
                    ],
                    'TYPE_ID' => [
                        'WIDGET' => new ComboBoxWidget(),
                        'REQUIRED' => true,
                        'VARIANTS' => TypesTable::getAdminAssocList()
                    ],
                    'NAME'     => [
                        'WIDGET'   => new StringWidget(),
                        'SIZE'     => 40,
                        'FILTER'   => '%',
                        'REQUIRED' => true
                    ],
                    'DATE_SHOW' => [
                        'WIDGET' => new DateTimeWidget()
                    ],
                    'SORT'     => [
                        'WIDGET'  => new NumberWidget(),
                        'DEFAULT' => 500,
                    ],
                    'FILE_ID' => [
                        'WIDGET' => new FileWidget(),
                        'HEADER' => false,
                        'FILTER' => false
                    ]
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function helpers()
    {
        return [
            DocsListHelper::class,
            DocsEditHelper::class
        ];
    }

    /**
     * @return array
     */
    public static function getSites()
    {
        try {
            $tmp = SiteTable::getList([
                'select' => ['LID', 'NAME'],
                'order'  => [
                    'SORT' => 'ASC'
                ]
            ])->FetchAll();
        } catch (\Exception $e) {
            $tmp = [];
        }

        if (!$tmp) {
            return [];
        }

        $res = [];

        foreach ($tmp as $v) {
            $res[$v['LID']] = '[' . $v['LID'] . '] ' . $v['NAME'];
        }

        return $res;
    }
}
