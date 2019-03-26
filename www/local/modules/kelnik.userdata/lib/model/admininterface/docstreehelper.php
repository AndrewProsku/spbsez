<?php

namespace Kelnik\UserData\Model\AdminInterface;

use Bitrix\Main\Context;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Kelnik\AdminHelper\Helper\AdminBaseHelper;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\UserData\Model\DocsTable;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(
    __DIR__ . DIRECTORY_SEPARATOR
    . '..' . DIRECTORY_SEPARATOR . 'docstable.php'
);

class DocsTreeHelper extends AdminBaseHelper
{
    protected static $model = DocsTable::class;

    public function show()
    {
        global $APPLICATION;

        !Loader::includeModule('kelnik.report');

        if (!$this->hasWriteRights()) {
            $this->addErrors(Loc::getMessage('KELNIK_ADMIN_HELPER_ACCESS_FORBIDDEN'));
            $this->showMessages();

            return false;
        }

        $request = Context::getCurrent()->getRequest();

        if ($request->isAjaxRequest() && isset($_POST['id'])) {
            $APPLICATION->RestartBuffer();

            BitrixHelper::jsonResponse(
                $this->prepareChild(
                    self::getChild(
                        str_replace('c', '', $request->getPost('id'))
                    ),
                    !empty($request->getPost('id'))
                )
            );
        }

        Loc::loadMessages(__FILE__);

        \CJSCore::RegisterExt(
            'treeGrid',
            [
                'js' => getLocalPath('js/kelnik.report') . '/jquery.treeGrid.js',
                'use' => \CJSCore::USE_ADMIN
            ]
        );

        \CJSCore::Init(['jquery', 'treeGrid']);

        $APPLICATION->SetAdditionalCSS(getLocalPath('css/kelnik.report/fontawesome/css/all.css'));
        $APPLICATION->SetAdditionalCSS(getLocalPath('css/kelnik.report/grid.css'));

        $tableId = 'kelnik-docs';

        $params = [
            'url' => self::getUrl(),
            'treeField' => 'NAME',
            'columns' => []
        ];

        echo '<div id="' . $tableId . '-div" class="adm-list-table-layout">';
        echo '<table class="adm-list-table grid-table" id="'. $tableId .'" role="grid"><thead><tr class="adm-list-table-header">';

        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_USERDATA_ID') . '</div></td>';
        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_USERDATA_RESIDENT') . '</div></td>';
        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_USERDATA_USER_ID') . '</div></td>';
        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_USERDATA_DATE_CREATED') . '</div></td>';

        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">&nbsp;</div></td>';
        echo '</tr></thead></table>';
        echo '</div>';

        $params['columns'] = [
            [
                'field' => 'ID',
                'attributes' => [
                    'style' => 'width: 50px;'
                ]
            ],
            [
                'field' => 'NAME'
            ],
            [
                'field' => 'USER_NAME'
            ],
            [
                'field' => 'DATE'
            ],
            [
                'field' => 'menu',
                'attributes' => [
                    'class' => 'grid-actions tree'
                ]
            ]
        ];

        $params = json_encode($params);

        echo "\n<script>$('#{$tableId}').treeGrid({$params});</script>\n";
    }

    protected static function getChild($parentId): array
    {
        $parentId = (int) $parentId;

        try {
            if (!$parentId) {
                return Profile::getAdminCompanyList();
            }

            return DocsTable::getAssoc([
                'select' => [
                    'ID',
                    'NAME' => 'FILE.ORIGINAL_NAME',
                    'USER_NAME',
                    'FILE_NAME' => 'FILE.FILE_NAME',
                    'MODILE_ID' => 'FILE.MODULE_ID',
                    'SUBDIR' => 'FILE.SUBDIR',
                    'FILE_SIZE' => 'FILE.FILE_SIZE',
                    'DATE_CREATED'
                ],
                'filter' => [
                    '=COMPANY_ID' => $parentId
                ],
                'order' => [
                    'DATE_CREATED' => 'DESC'
                ]
            ], 'ID');
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function prepareChild(array $data, $isChild = false): array
    {
        if (!$data) {
            return $data;
        }

        $stat = [];

        if (!$isChild) {
            $stat = DocsTable::getAssoc([
                'select' => [
                    'COMPANY_ID',
                    new ExpressionField('CNT', 'COUNT(DISTINCT %s)', 'ID')
                ],
                'filter' => [
                    '=COMPANY_ID' => array_keys($data)
                ]
            ], 'COMPANY_ID', 'CNT');
        }

        foreach ($data as $k => $val) {
            if (!$isChild) {
                $data[$k] = [
                    'ID' => '',
                    'NAME' => '<a href="javascript:;">' . $val . '</a>',
                    'id' => 'c' . $k,
                    'parent_id' => 0,
                    'quantity' => ArrayHelper::getValue($stat, $k, 0)
                ];
                continue;
            }

            $fileLink = \CFile::GetFileSRC($val);

            $data[$k] = [
                'id' => $k,
                'parent_id' => 'c'. $val['COMPANY_ID'],
                'quantity' => -1,
                'ID' => $k,
                'NAME' => '<a target="_blank" href="' . $fileLink . '">' . $val['NAME'] . '</a>',
                'USER_NAME' => $val['USER_NAME'],
                'DATE' => $val['DATE_CREATED'] instanceof DateTime ? $val['DATE_CREATED']->toString() : '-',
                'menu' => '<a class="bx-core-popup-menu-item bx-core-popup-menu-item-default" href="' . DocsEditHelper::getUrl(['ID' => $val['ID']]) . '">' .
                            '<span class="bx-core-popup-menu-item-icon adm-menu-edit"></span>' .
                            '<span class="bx-core-popup-menu-item-text">' . Loc::getMessage('KELNIK_USERDATA_EDIT_ELEMENT') . '</span>' .
                            '</a>'
            ];
        }

        return $data;
    }
}
