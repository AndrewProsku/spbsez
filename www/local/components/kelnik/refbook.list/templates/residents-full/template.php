<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>

<?php if (!empty($arResult['TYPES'])): ?>
<div class="b-expanded-menu  j-expanded-menu">
    <div class="b-expanded-menu__header">Отрасли <span class="b-expanded-menu__item-counter"></span></div>
    <ul class="b-expanded-menu__list">
        <?php foreach ($arResult['TYPES'] as $type): ?>
            <li class="b-expanded-menu__item" data-category-id="<?= $type['ID']; ?>">
                <span class="b-expanded-menu__item-text"><?= $type['NAME']; ?></span> 
                <span class="b-expanded-menu__counter"><?= $type['CNT']; ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="b-filters j-filters">
    <?php foreach ($arResult['TYPES'] as $type): ?>
        <div id="types-residents"></div>
    <div class="b-filters__item" data-category-id="<?= $type['ID']; ?>"><?= $type['NAME']; ?><span class="b-filters__counter"><?= $type['CNT']; ?></span></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="b-residents-content">
    <?php foreach ($arResult['ELEMENTS'] as $element): ?>
        <article class="b-resident b-resident_category_<?= $element['TYPE_ID']; ?>">
            <div class="b-resident__inner">
                <div id="residents-description"></div>
                <?php if(!empty($element['IMAGE_ID_PATH'])): ?>
                    <img src="<?= $element['IMAGE_ID_PATH']; ?>" alt="<?= htmlentities($element['NAME'], ENT_QUOTES, 'UTF-8'); ?>" class="b-resident__logo">
                <?php endif; ?>
                <h2 class="b-resident__title"><?= $element['NAME']; ?></h2>
                <div class="b-resident__category"><?= $element['TYPE_NAME']; ?></div>
                <div class="b-resident__description"><?= $element['TEXT']; ?></div>
                <div class="b-resident__links">
                    <?php if($element['SITE']): ?>
                        <div class="b-resident__site-link"><a href="http://<?= $element['SITE']; ?>" target="_blank" class="b-link-line" rel="nofollow"><?= $element['SITE']; ?></a></div>
                    <?php endif; ?>
                    <?php if($element['PLACE']): ?>
                        <div class="b-resident__page-link"><span class="b-link-line"><?= $element['PLACE_NAME']; ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>
