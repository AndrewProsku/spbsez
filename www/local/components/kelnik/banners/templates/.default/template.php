<?php
if ($arResult['ID'] && $_COOKIE['oez_banner_'.$arResult['ID']] != 'close') { ?>
    <div class="banner-wrapper" data-id="<?=$arResult['ID']?>">
        <div class="banner" style="background-image: url(<?=$arResult['IMAGE_PATH']?>);">
            <div class="banner__overlay" <?php if($arResult['OVERLAY']) { ?>style="background-color: #<?=$arResult['OVERLAY']?>;"<?php } ?>></div>
            <div class="banner__title">
                <?=$arResult['NAME']?>
            </div>
            <div class="banner__text">
               <?=$arResult['SUBTITLE']?>
            </div>
            <a class="banner__button" href="<?=$arResult['LINK']?>" target="blank">Подробнее</a> 
            <a class="banner__closer" href=""></a>
        </div>
    </div> 
<?php } ?>

