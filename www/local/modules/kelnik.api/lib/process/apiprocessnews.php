<?php
namespace Kelnik\Api\Process;

use Bitrix\Main\Context;
use Kelnik\Helpers\ArrayHelper;
use Kelnik\News\Component\NewsList;

/**
 * Class ApiProcessVacancy
 *
 * Профиль
 *
 * @package Kelnik\Api\Process
 */
class ApiProcessNews extends ApiProcessAbstract
{
    public function execute(array $request): bool
    {
        $contextRequest = Context::getCurrent()->getRequest();

        \CBitrixComponent::includeComponentClass('kelnik:news.list');

        $component = new NewsList();
        $component->initComponentTemplate('media-news');
        $component->arParams = $component->onPrepareComponentParams([
            "AJAX_PARAM_NAME" => "compid",
            "AJAX_COMPONENT_ID" => "news-list",
            "AJAX_TEMPLATE_PAGE" => "",
            "AJAX_TYPE" => "JSON",
            "CACHE_GROUPS" => "N",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "N",
            "ELEMENTS_COUNT" => "6",
            "SECTION_ID" => (int) $contextRequest->getPost('sect'),
            "SEF_FOLDER" => "/media/news/",
            "SEF_MODE" => "N",
            "SEF_URL_TEMPLATES" => Array("detail"=>"#ELEMENT_CODE#/","index"=>"","section"=>""),
            "SET_404" => "N",
            "SET_SEO_TAGS" => "N",
            "SORT_BY_1" => "DATE_SHOW",
            "SORT_ORDER_1" => "DESC",
            "SORT_BY_2" => "ID",
            "SORT_ORDER_2" => "ASC",
            "USE_AJAX" => "N",
            "USE_ADVANCE_FILTER" => "Y"
        ]);

        $component->executeComponent();

        $this->data['news'] = ArrayHelper::getValue($component->arResult, 'ELEMENTS', []);
        $this->data['showMore'] = ArrayHelper::getValue($component->arResult, 'MORE', false);

        unset($component);

        if ($this->data['news']) {
            $showFields = [
                'ID', 'NAME', 'IMAGE_PREVIEW_PATH',
                'DETAIL_PAGE_URL', 'DATE_SHOW_FORMAT',
                'TEXT_PREVIEW', 'TAGS'
            ];

            foreach ($this->data['news'] as &$v) {
                foreach ($v as $key => $val) {
                    if (in_array($key, $showFields)) {
                        continue;
                    }
                    unset($v[$key]);
                }
            }
            unset($v);
        }

        return true;
    }
}
