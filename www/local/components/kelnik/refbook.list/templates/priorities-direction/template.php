<?php 
use Bitrix\Main\Localization\Loc;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} 
Loc::loadMessages(__FILE__);
?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>

<?php
$arResidentImages = [
    1 => [
        'IMG' => '/images/investors/pharm.svg',
        'NAME' => Loc::getMessage('NAME_PHARM')
    ],
    2 => [
        'IMG' => '/images/investors/device.svg',
        'NAME' => Loc::getMessage('NAME_MATERIALS')
    ],
    3 => [
        'IMG' => '/images/investors/it.svg',
        'NAME' => Loc::getMessage('NAME_IT')
    ],
    5 => [
        'IMG' => '/images/investors/microel.svg',
        'NAME' => Loc::getMessage('NAME_MICRO')
    ],
];
?>

<div class="b-priorities-direction__title">
    <?php echo Loc::getMessage('PRIORITIES'); ?>
</div>

<div class="b-priorities-direction__items">
    <?php foreach ($arResult['TYPES'] as $type): ?>
        <div class="b-priorities-direction__item">
            <div class="b-priorities-direction__wrap">
                <div class="b-priorities-direction__img">
                    <img width="120" src="<?=$arResidentImages[$type['ID']]['IMG']?>" height="120">
                </div>
                <div class="b-priorities-direction__inner-wrap">
                    <div class="b-priorities-direction__caption">
                        <?=$arResidentImages[$type['ID']]['NAME']?>
                    </div>
                    <div class="b-priorities-direction__residents b-priorities-direction__residents_is_adapt">
                        <?php if (LANG_DIR == '/ch/') { ?>
                           <?= $type['CNT']; ?> <?=Loc::getMessage('RESIDENTS')?>
                        <?php } else { ?>
                            <a href="<?= LANG_DIR; ?>residents/?type=<?= $type['ID']; ?>" class="b-link-line"><?= $type['CNT']; ?> <?=Loc::getMessage('RESIDENTS')?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="b-priorities-direction__residents">
                <?php if (LANG_DIR == '/ch/') { ?>
                    <?= $type['CNT']; ?> <?=Loc::getMessage('RESIDENTS')?>
                <?php } else { ?>
                    <a href="<?= LANG_DIR; ?>residents/?type=<?= $type['ID']; ?>" class="b-link-line"><?= $type['CNT']; ?> <?=Loc::getMessage('RESIDENTS')?></a>
                <?php } ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>