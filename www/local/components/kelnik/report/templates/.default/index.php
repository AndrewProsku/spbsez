<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<div class="l-reports">
    <?php $APPLICATION->IncludeComponent(
        'kelnik:report.list',
        '.default',
        array(
            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
            'CACHE_TIME' => $arParams['CACHE_TIME'],
            'ACTION' => $arParams['ACTION'],
            'SET_404' => $arParams['SET_404'],
            'USE_AJAX' => $arParams['USE_AJAX'],
            'AJAX_TYPE' => $arParams['AJAX_TYPE'],
            'AJAX_HEAD_RELOAD' => $arParams['AJAX_HEAD_RELOAD'],
            'AJAX_TEMPLATE_PAGE' => $arParams['AJAX_TEMPLATE_PAGE'],
            'AJAX_COMPONENT_ID' => $arParams['AJAX_COMPONENT_ID'],
            'SEF_FOLDER' => $arParams['SEF_FOLDER']
        ),
        $component
    );?>
</div>
<?php $APPLICATION->IncludeFile('inc_account_logout.php'); ?>
