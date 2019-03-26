<?php

namespace Kelnik\Report\Model\AdminInterface;

use Bitrix\Main\Context;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminBaseHelper;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(
    __DIR__ . DIRECTORY_SEPARATOR
    . '..' . DIRECTORY_SEPARATOR . 'reports.php'
);

class ReportsTreeHelper extends AdminBaseHelper
{
    protected static $model = ReportsTable::class;

    public function show()
    {
        global $APPLICATION;

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

        $tableId = 'kelnik-reports';

        $params = [
            'url' => self::getUrl(),
            'treeField' => 'NAME',
            'columns' => []
        ];

        echo '<div id="' . $tableId . '-div" class="adm-list-table-layout">';
        echo '<table class="adm-list-table grid-table" id="'. $tableId .'" role="grid"><thead><tr class="adm-list-table-header">';

        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_REPORT_ID') . '</div></td>';
        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_REPORT_NAME') . '</div></td>';
        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_REPORT_QUARTER') . '</div></td>';
        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_REPORT_STATUS') . '</div></td>';

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
                'field' => 'QUARTER'
            ],
            [
                'field' => 'STATUS'
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

            return ReportsTable::getAssoc([
                'select' => [
                    'ID', 'COMPANY_ID', 'NAME',
                    'QUARTER', 'YEAR',
                    'STATUS_NAME' => 'STATUS.NAME'
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

    protected function prepareChild(array $data, $isReports = false): array
    {
        if (!$data) {
            return $data;
        }

        $stat = [];

        if (!$isReports) {
            $stat = ReportsTable::getAssoc([
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
            if (!$isReports) {
                $data[$k] = [
                    'ID' => '',
                    'NAME' => '<a href="#">' . $val . '</a>',
                    'id' => 'c' . $k,
                    'parent_id' => 0,
                    'quantity' => ArrayHelper::getValue($stat, $k, 0)
                ];
                continue;
            }

            $data[$k] = [
                'id' => $k,
                'parent_id' => 'c'. $data['COMPANY_ID'],
                'quantity' => -1,
                'ID' => $k,
                'NAME' => '<a target="_blank" href="' . ReportsEditHelper::getUrl(['ID' => $k]) . '">' . $val['NAME'] . '</a>',
                'QUARTER' => ReportsTable::getTypeName($val['QUARTER']) . ' ' . $val['YEAR'],
                'STATUS' => $val['STATUS_NAME']
            ];
        }

        return $data;
    }
}
