<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if (empty($arResult['ELEMENTS'])): return; endif; ?>
<? //echo "<pre>"; print_r($arResult); echo "</pre>"; ?>
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
        <?
        $data_id = "#resident_" . $element["ID"];
        if (!$element["IMAGES"]) {
            $data_id = "#resident_" . $element["ID"] . "_no-photo";
        }
        ?>
        <article class="b-resident b-resident_category_<?= $element['TYPE_ID']; ?>">
            <input type="hidden" value="<?=$element["ID"]?>" class="resident-id">
            <div class="b-resident__inner">
                <div id="residents-description"></div>
          
                <?php if(!empty($element['IMAGE_ID_PATH'])): ?>
                    <img src="<?= $element['IMAGE_ID_PATH']; ?>" alt="<?= htmlentities($element['NAME'], ENT_QUOTES, 'UTF-8'); ?>" class="b-resident__logo j-message-button _pointer resident-popup" data-href="<?=$data_id?>">
                <?php endif; ?>
                <h2 class="b-resident__title j-message-button _pointer resident-popup" data-href="#resident_<?=$element["ID"]?>"><?= $element['NAME']; ?></h2>
                <div class="b-resident__category j-message-button _pointer resident-popup" data-href="#resident_<?=$element["ID"]?>"><?= $element['TYPE_NAME']; ?></div>
                <div class="b-resident__description j-message-button _pointer resident-popup" data-href="#resident_<?=$element["ID"]?>"><?= $element['TEXT']; ?></div>
             
                <div class="b-resident__links">
                    <?php if($element['SITE']): ?>
                        <div class="b-resident__site-link">
                            <a href="http://<?= $element['SITE']; ?>" target="_blank" class="b-link-line" rel="nofollow"><?= $element['SITE']; ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if($element['PLACE']): ?>
                        <div class="b-resident__page-link">
                            <a href="<?= $element['PLACE_LINK']; ?>" class="b-link-line"><?= $element['PLACE_NAME']; ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php foreach ($arResult['ELEMENTS'] as $element): ?>
    <?
    $data_id = "#resident_" . $element["ID"];
    if (!$element["IMAGES"]) {
        $data_id = "#resident_" . $element["ID"] . "_no-photo";
    }
    ?>
    <div id="<?=$data_id?>" class="b-message-popup resident-data">
        <div class="resident-popup-open">
            <div class="resident-popup-open__pics">         
                <div class="resident-popup-open__logo <? if(!$element["IMAGES"]){echo "resident-popup-open__logo_no-photo";} ?>">
                    <img src="<?=$element["IMAGE_ID_PATH"]?>" alt="<?=$element["NAME"]?> - лого">
                </div>
                <div class="resident-popup-open__image">
                    <?foreach($element["IMAGES"] as $k=>$image){
                        ?><img src="<?=$image?>" alt="<?=$element["NAME"]?> - рисунок <?=$k+1?>" class="<?=$k==0 ? 'visible-image' : ''?>"><?
                    }?>
                    <?if(count($element["IMAGES"]) > 1){?>
                        <div class="resident-popup-open__image-next" onclick="sezApp.nextResidentPhoto(this)"></div>
                    <?}?>
                </div>
            </div>
            <div class="resident-popup-open__content">
                <div class="resident-popup-open__title"><?=$element["NAME"]?></div>
                <?if($element["STATUS_DATE"]){?>
                    <div class="resident-popup-open__date"><?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_DATE_TITLE');?> <?=FormatDate('Y', MakeTimeStamp($element["STATUS_DATE"]))?></div>
                <?}?>
                <div style="clear:both"></div>
                <div class="resident-popup-open__text"><?=$element["TEXT"]?></div>
                <div class="resident-popup-open__info">
                    <div class="resident-popup-open__contacts">
                        <div class="resident-popup-open__contacts-title"><?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_RESIDENT_CONTACTS');?></div>
                        <div class="resident-popup-open__contacts-phone"><?=$element["PHONE"]?></div>
                        <div class="resident-popup-open__contacts-phone">
                            <a href="http://<?= $element['SITE']; ?>" target="_blank"><?= $element['SITE']; ?></a>
                        </div>
                    </div>
                    <div class="resident-popup-open__address">
                        <div class="resident-popup-open__contacts-title"><?=\Bitrix\Main\Localization\Loc::getMessage('KELNIK_RESIDENT_ADDRESS');?></div>
                        <div class="resident-popup-open__contacts-phone">
                            <?=$element["ADDRESS"]?>
                        </div>
                        <?php if($element['PLACE']): ?>
                            <div class="resident-popup-open__contacts-phone">
                                Площадка «<?= $element['PLACE_NAME']; ?>»
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?if($element["PROJECT_STAGE"]){?>
                    <div class="resident-popup-open__date"><?=$element["PROJECT_STAGE"]?></div>
                <?}?>
            </div>
        </div>
    </div>
<?php endforeach; ?>