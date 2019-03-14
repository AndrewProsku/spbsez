<?php
namespace Kelnik\Api\Process;

use Kelnik\Helpers\ArrayHelper;
use Kelnik\Info\Component\InfoDocsList;

/**
 * Class ApiProcessInfoDocs
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessInfoDocs extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        $component = $this->initComponent(
            'kelnik:infodocs.list',
            [
                "AJAX_PARAM_NAME" => "compid",
                "AJAX_COMPONENT_ID" => ArrayHelper::getValue($request, 'compid', 'info-docs'),
                "SECTION" => (int) ArrayHelper::getValue($request, 'sect'),
                "YEAR" => (int) ArrayHelper::getValue($request, 'year'),
                "CACHE_GROUPS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "SHOW_FILTER" => "N",
                "USE_AJAX" => "N",
                "AJAX_TYPE" => "JSON",
                "AJAX_TEMPLATE_PAGE" => ""
            ],
            'docs'
        );

        $component->executeComponent();
        ob_clean();

        $this->data['elements'] = ArrayHelper::getValue($component->arResult, 'ELEMENTS', []);

        unset($component);

        if (!$this->data['elements']) {
            return false;
        }

        $showFields = [
            'ID', 'NAME', 'TYPE_NAME', 'FILE', 'DATE_SHOW_FORMAT', 'DATE_SHOW_FORMAT_HUMAN'
        ];

        foreach ($this->data['elements'] as &$v) {
            foreach ($v as $key => $val) {
                if (in_array($key, $showFields)) {
                    continue;
                }
                unset($v[$key]);
            }
        }
        unset($v);

        return true;
    }
}
