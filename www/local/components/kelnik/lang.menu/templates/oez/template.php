<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if(!$arResult['LANGS']): return; endif; ?>
<div class="b-language">
    <span class="b-language__link">
        <?= $arResult['CURRENT_LANG']; ?>
    </span>
    <ul class="b-language__list">
        <?php foreach ($arResult['LANGS'] as $lng): ?>
        <li class="b-language__item<?php if($lng['SELECTED']): ?> is-active<?php endif; ?>">
            <a href="<?= $lng['URL']; ?>" class="b-language__item-link b-link-line"><?= $lng['NAME']; ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
