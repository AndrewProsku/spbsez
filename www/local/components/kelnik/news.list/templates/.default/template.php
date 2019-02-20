<?php if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true) die(); ?>
<?php CJSCore::Init(['jquery']); ?>

<div id="block_<?= $arParams['AJAX_COMPONENT_ID']; ?>" class="news-list">
    <?php foreach ($arResult['ELEMENTS'] as $element): ?>
    <div>
        <a href="<?=$element['DETAIL_PAGE_URL']?>"><?=$element['NAME']?></a>
    </div>
    <?php endforeach; ?>
</div>

<?php if($arResult['MORE']): ?>
    <div>
        <a href="javascript:;"
           class="news-btn"
           data-id="<?= $arParams['AJAX_COMPONENT_ID']; ?>"
           data-page='<?= $arResult['PAGE']+1; ?>'
           data-ajax-param="<?= $arResult['AJAX_REQUEST_PARAMS']; ?>"
        >Показать еще <?= $arResult['MORE'] , ' ' , $arResult['MORE_TEXT']; ?></a>
    </div>
<?php endif; ?>
