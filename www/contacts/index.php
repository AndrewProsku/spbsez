<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
$APPLICATION->SetPageProperty('title', 'Контакты');
$yMapJson = [
    "center" => [59.942099, 31.186182],
    "scrollwheel" => false,
    "fullScreenControl" => false,
    "customZoomControl" => true,
    "htmlMarkers" => [
        [
            "title" => "Новоорловская",
            "layout" => "secondary",
            "coords" => [60.053400, 30.231714],
            "link" => "/infrastructure/novoorlovskaya/"
        ],
        [
            "title" => "Нойдорф",
            "layout" => "secondary",
            "coords" => [59.840573, 30.005940],
            "link" => "/infrastructure/noidorf/",
            "coordShape" => [[-60, -30],[60, -30],[60, 10],[-60, 10]]
        ],
        [
            "title" => "Инновационный центр",
            "layout" => "secondary",
            "coords" => [60.053400, 30.231714],
            "link" => "/infrastructure/innocenter/"
        ]
    ],
    "markers" => []
];

$yMapJson = base64_encode(json_encode($yMapJson));
?>
    <div class="l-contacts">
        <div class="l-contacts__map-wrapper">
            <div class="l-contacts__title">
                <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
            </div>
            <div class="l-contacts__map">
                <div class="l-contacts__yandex-map b-yandex-map j-yandex-map" id="map_list" data-json="<?= $yMapJson; ?>">
                    <div id="first" class="b-yandex-map__base"></div>
                </div>
           
                <!--<div class="l-contacts__map-text">
                    <div class="l-contacts__map-text-item">
                        <div class="l-contacts__map-text-title">
                            <p>Участок «Новоорловская»</p>
                        </div>
                        <div class="l-contacts__map-text-desc">
                            <p>Санкт-Петербург, Дорога в Каменку, д. 74, литера А</p>
                        </div>
                    </div>
                    <div class="l-contacts__map-text-item">
                        <div class="l-contacts__map-text-title">
                            <p>Инновационный центр</p>
                        </div>
                        <div class="l-contacts__map-text-desc">
                            <p>Санкт-Петербург, Дорога в Каменку, д. 74, литера А</p>
                        </div>
                    </div>
                    <div class="l-contacts__map-text-item">
                        <div class="l-contacts__map-text-title">
                            <p>Участок «Нойдорф»</p>
                        </div>
                        <div class="l-contacts__map-text-desc">
                            <p>Санкт-Петербург, пос. Стрельна, ул. Связи, д. 34А</p>
                        </div>
                    </div>                
                </div>-->

                <div class="l-contacts__opener" onclick="sezApp.openContacts(this)">Показать контакты</div>
                <div class="l-contacts__map-text l-contacts__map-text-scroll">
                    <div class="l-contacts__closer" onclick="sezApp.closeContacts(this)"></div>                    
                    <div class="l-contacts__content" id="managment">
                        <div class="l-contacts__content-scroll" data-scrollbar>
                            <div class="l-contacts__block l-contacts__block-main">                                    
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
                            <div class="l-contacts__block">                                    
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
                            <div class="l-contacts__block">                                    
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
                </div>
            </div>
        </div>

        <?/*?><div class="l-contacts__content" id="managment">

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
        </div><?*/?>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
