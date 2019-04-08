<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php if (empty($arResult)): return; endif; ?>
<nav class="b-mobile-menu__nav">
    <div class="b-mobile-menu__nav-wrap">
        <?php foreach($arResult as $arItem): ?>
            <div class="b-mobile-menu__nav-block">
                <div class="b-mobile-menu__nav-header<?php if(!empty($arItem['CHILD'])): ?> b-mobile-menu__nav-header_action_accordion j-mobile-menu-accordion<?php endif; ?>">
                    <a href="<?= $arItem['LINK'] ?>"
                       class="b-mobile-menu__nav-header-link b-link-line-two<?php if(!empty($arItem['SELECTED'])): ?> is-active<?php endif; ?>">
                        <?= $arItem['TEXT']; ?>
                    </a>
                </div>
                <?php if(!empty($arItem['CHILD'])): ?>
                    <ul class="b-mobile-menu__nav-body">
                    <?php foreach($arItem['CHILD'] as $child): ?>
                        <li class="b-mobile-menu__nav-item">
                            <a href="<?= $child['LINK']; ?>"
                               class="b-mobile-menu__nav-link<?php if(!empty($child['SELECTED'])): ?> is-active<?php endif; ?>">
                                <?= $child['TEXT']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</nav>