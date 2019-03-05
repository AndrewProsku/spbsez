<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
    'kelnik:news.detail',
    'article-oez',
    array(
        'SECTION_ID' => $arParams['SECTION_ID'],
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'SET_404' => $arParams['SET_404'],
        'DATE_FORMAT' => $arParams['DETAIL_DATE_FORMAT'],
        'SET_SEO_TAGS' => $arParams['SET_SEO_TAGS'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER']
    ),
    $component
);?>