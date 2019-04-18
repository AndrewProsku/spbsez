<?php

namespace Kelnik\Report\Model\AdminInterface;

use Bitrix\Main\Context;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Kelnik\AdminHelper\Helper\AdminBaseHelper;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\Helpers\BitrixHelper;
use Kelnik\Helpers\Database\Query;
use Kelnik\Report\Model\ReportsTable;
use Kelnik\Userdata\Profile\Profile;

Loc::loadMessages(
    __DIR__ . DIRECTORY_SEPARATOR
    . '..' . DIRECTORY_SEPARATOR . 'reportstable.php'
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

            preg_match('!c(?P<resident>\d+)(\-(?P<year>\d+)|)!si', $request->getPost('id'), $matches);
            $residentId = (int) ArrayHelper::getValue($matches, 'resident', 0);
            $year       = (int) ArrayHelper::getValue($matches, 'year', 0);

            BitrixHelper::jsonResponse(
                $this->prepareChild(
                    self::getChild($residentId, $year),
                    $residentId,
                    $year
                )
            );
        }

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
        echo '<td class="adm-list-table-cell"><div class="adm-list-table-cell-inner">' . Loc::getMessage('KELNIK_REPORT_TYPE') . '</div></td>';
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
                'field' => 'TYPE'
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

    protected static function getChild(int $residentId, int $year = 0): array
    {
        try {
            if (!$residentId) {
                return Profile::getAdminCompanyList();
            }

            $keyField = 'ID';
            $params = [
                'select' => [
                    'ID', 'COMPANY_ID', 'NAME',
                    'TYPE', 'YEAR',
                    'STATUS_NAME' => 'STATUS.NAME'
                ],
                'filter' => [
                    '=COMPANY_ID' => $residentId,
                    '=YEAR' => $year
                ],
                'order' => [
                    'TYPE' => 'DESC',
                    'DATE_CREATED' => 'DESC'
                ],
                'group' => []
            ];

            if (!$year) {
                Query::setGlobalChainsToGroup(false);
                $params['select'] = ['YEAR', 'COMPANY_ID'];
                $params['group']  = ['YEAR'];
                $params['order'] = [
                    'YEAR' => 'DESC'
                ];
                $keyField = 'YEAR';
                unset($params['filter']['=YEAR']);
            }

            $res = ReportsTable::getAssoc($params, $keyField);
            Query::setGlobalChainsToGroup(true);

        } catch (\Exception $e) {
            $res = [];
        }

        return $res;
    }

    protected function prepareChild(array $data, int $residentId = 0, int $year = 0): array
    {
        if (!$data) {
            return $data;
        }

        $stat = [];

        if (!$residentId) {
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

        foreach ($data as $k => &$v) {
            if (!$residentId) {
                $v = [
                    'ID' => '',
                    'NAME' => '<a href="javascript:;" class="kelnik-fake-sub">' . $v . '</a>',
                    'id' => 'c' . $k,
                    'parent_id' => 0,
                    'quantity' => ArrayHelper::getValue($stat, $k, 0)
                ];

                continue;
            }

            if ($residentId && !$year) {
                $v = [
                    'ID' => '',
                    'NAME' => '<a href="javascript:;" class="kelnik-fake-sub">' . $v['YEAR'] . '</a>',
                    'id' => 'c' . $v['COMPANY_ID'] . '-' . $v['YEAR'],
                    'parent_id' => 'c' . $v['COMPANY_ID'],
                    'quantity' => 1
                ];

                continue;
            }

            $v = [
                'id' => $v['ID'],
                'parent_id' => 'c' . $v['COMPANY_ID'] . '-' . $v['YEAR'],
                'quantity' => -1,
                'ID' => $v['ID'],
                'NAME' => '<a target="_blank" href="' . ReportsEditHelper::getUrl(['ID' => $v['ID']]) . '">' . $v['NAME'] . '</a>',
                'TYPE' => ReportsTable::getTypeName($v['TYPE']),
                'STATUS' => $v['STATUS_NAME']
            ];
        }

        return $data;
    }
}
