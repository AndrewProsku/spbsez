<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
    <?php if(empty($arResult['QUESTIONS'])): return; endif; ?>
    <div class="b-search__result j-globalSearch-result is-result">
        <ul class="b-search__result-wrapp">Популярные запросы
            <?php foreach ($arResult['QUESTIONS'] as $element): ?>
                <?if(!$element['URL']) continue;?>
                <li class="b-search__result-item">
                    <a href="<?=$element['URL']?>" class="b-search__result-text j-search-link"><?=$element['NAME']?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
