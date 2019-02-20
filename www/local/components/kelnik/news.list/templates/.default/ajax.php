<?php foreach ($arResult['ELEMENTS'] as $element): ?>
    <div>
        <a href="<?=$element['DETAIL_PAGE_URL']?>"><?=$element['NAME']?></a>
    </div>
<?php endforeach; ?>