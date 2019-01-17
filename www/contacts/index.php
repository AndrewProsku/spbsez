<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
$APPLICATION->SetPageProperty('title', 'Контакты | АООЭЗ');
?>
    <div class="l-contacts">
        <div class="l-contacts__map-wrapper">
            <div class="l-contacts__title">
                <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
            </div>
            <div class="l-contacts__map">
                <div class="l-contacts__yandex-map b-yandex-map j-yandex-map" data-ajax="/tests/yandex-map.json">
                    <div id="first" class="b-yandex-map__base"></div>
                </div>
            </div>
            <div class="l-contacts__map-text">
                <div class="l-contacts__map-text-item">
                    <div class="l-contacts__map-text-title">
                        <p>Участок «Новоорловская»</p>
                    </div>
                    <div class="l-contacts__map-text-desc">
                        <p>Санкт-Петербург, Дорога в Каменку, д. 74</p>
                    </div>
                    <a href="#" class="l-contacts__map-text-link b-link-line j-contacts-location"
                       data-coordinates="[60.053400, 30.231714]">
                        Расположение
                    </a>
                </div>

                <div class="l-contacts__map-text-item">
                    <div class="l-contacts__map-text-title">
                        <p>Участок «Нойдорф»</p>
                    </div>
                    <div class="l-contacts__map-text-desc">
                        <p>Санкт-Петербург, пос. Стрельна, ул. Связи, д. 34А</p>
                    </div>
                    <a href="#" class="l-contacts__map-text-link b-link-line j-contacts-location"
                       data-coordinates="[59.840573, 30.005940]">
                        Расположение
                    </a>
                </div>
            </div>
        </div>

        <div class="l-contacts__content">

            <div class="l-contacts__block">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "contacts_block_1"
                    )
                );?>
            </div>
            <div class="l-contacts__block">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "contacts_block_2"
                    )
                );?>
            </div>
            <div class="l-contacts__block l-contacts__block_width_half-left">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "contacts_block_3"
                    )
                );?>
            </div>

            <div class="l-contacts__block l-contacts__block_width_half-right">
                <?$APPLICATION->IncludeComponent(
                    "kelnik:textblocks",
                    "",
                    Array(
                        "CACHE_TIME" => "360000",
                        "CACHE_TYPE" => "A",
                        "CODE" => "contacts_block_4"
                    )
                );?>
            </div>
        </div>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
