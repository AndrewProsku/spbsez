<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
    'kelnik:report.list',
    '.default',
    array(
        'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
        'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
        'SORT_BY_1' => $arParams['SORT_BY_1'],
        'SORT_ORDER_1' => $arParams['SORT_ORDER_1'],
        'SORT_BY_2' => $arParams['SORT_BY_2'],
        'SORT_ORDER_2' => $arParams['SORT_ORDER_2'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'ACTION' => $arParams['ACTION'],
        'SET_404' => $arParams['SET_404'],
        'SET_SEO_TAGS' => $arParams['SET_SEO_TAGS'],
        'PAGER_SAVE_SESSION' => $arParams['PAGER_SAVE_SESSION'],
        'ELEMENTS_COUNT' => $arParams['ELEMENTS_COUNT'],
        'USE_AJAX' => $arParams['USE_AJAX'],
        'AJAX_TYPE' => $arParams['AJAX_TYPE'],
        'AJAX_HEAD_RELOAD' => $arParams['AJAX_HEAD_RELOAD'],
        'AJAX_TEMPLATE_PAGE' => $arParams['AJAX_TEMPLATE_PAGE'],
        'AJAX_COMPONENT_ID' => $arParams['AJAX_COMPONENT_ID'],
        'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
        'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
        'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
        'PAGER_TITLE' => $arParams['PAGER_TITLE'],
        'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
        'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
        'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
        'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER']
    ),
    $component
);?>
