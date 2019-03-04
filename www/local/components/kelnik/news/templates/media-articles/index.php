<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<div class="l-useful">
    <div class="l-useful-single__back"><a href="<?= $arParams['SEF_FOLDER']; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="8" height="13" viewBox="0 0 8 13"><defs><path id="prgga" d="M214.44 194.5l-.94 1.074 4.977 4.927-4.977 4.925.94 1.074 6.06-5.999z"/></defs><g><g transform="rotate(180 110.5 103.5)"><use fill="#30409a" xlink:href="#prgga"/></g></g></svg>
            Назад в медиа</a>
    </div>

    <div class="l-useful-single__title b-title"><h1><?= $APPLICATION->ShowTitle(false); ?></h1></div>
    <? $APPLICATION->IncludeComponent(
        'kelnik:news.list',
        'articles',
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
            'DATE_FORMAT' => $arParams['LIST_DATE_FORMAT'],
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
            'SEF_FOLDER' => $arParams['SEF_FOLDER'],
            'USE_ADVANCE_FILTER' => $arParams['USE_ADVANCE_FILTER']
        ),
        $component
    );?>
</div>
