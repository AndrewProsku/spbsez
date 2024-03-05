<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Contacts");
$APPLICATION->SetPageProperty('title', 'Contacts');

$yMapJson = [
    "center" => [59.939092, 30.612133],
    "scrollwheel" => false,
    "fullScreenControl" => false,
    "customZoomControl" => true,
    "htmlMarkers" => [
        [
            "title" => "Novoorlovskaya",
            "layout" => "secondary",
            "coords" => [60.053400, 30.231714],
            "link" => "/en/infrastructure/novoorlovskaya/",
            "coordShape" => [[-60, -30],[60, -30],[60, 10],[-60, 10]]
        ],
        [
            "title" => "Noidorf",
            "layout" => "secondary",
            "coords" => [59.840573, 30.005940],
            "link" => "/en/infrastructure/noidorf/",
            "coordShape" => [[-60, -30],[60, -30],[60, 10],[-60, 10]]
        ],
        [
            "title" => "Parnas",
            "layout" => "secondary",
            "coords" => [60.084141, 30.369391],
            "link" => "/en/infrastructure/parnas/",
            "coordShape" => [[-60, -30],[60, -30],[60, 10],[-60, 10]]
        ],
        [
            "title" => "Shushary",
            "layout" => "secondary",
            "coords" => [59.809591, 30.445522],
            "link" => "/en/infrastructure/shushary/",
            "coordShape" => [[-60, -30],[60, -30],[60, 10],[-60, 10]]
        ],
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
                <div class="l-contacts__yandex-map b-yandex-map j-yandex-map" id="map_list" data-lang="<?= LANGUAGE_ID; ?>" data-json="<?= $yMapJson; ?>">
                    <div id="first" class="b-yandex-map__base"></div>
                </div>

                <!--<div class="l-contacts__map-text">
                    <div class="l-contacts__map-text-item">
                        <div class="l-contacts__map-text-title">
                            <p>Novoorlovskaya site</p>
                        </div>
                        <div class="l-contacts__map-text-desc">
                            <p>Doroga v Kamenku, 74, lit. A, St. Petersburg</p>
                        </div>
                    </div>
                    <div class="l-contacts__map-text-item">
                        <div class="l-contacts__map-text-title">
                            <p>Novoorlovskaya site</p>
                        </div>
                        <div class="l-contacts__map-text-desc">
                            <p>Doroga v Kamenku, 74, lit. A, St. Petersburg</p>
                        </div>
                    </div>
                    <div class="l-contacts__map-text-item">
                        <div class="l-contacts__map-text-title">
                            <p>Noydorf site</p>
                        </div>
                        <div class="l-contacts__map-text-desc">
                            <p>Svyazi Street, 34 A, Strelna, St.Petersburg</p>
                        </div>
                    </div>
                </div>-->

                <div class="l-contacts__opener" onclick="sezApp.openContacts(this)">Open contacts</div>
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
                                        "CODE" => "contacts_block_en_1"
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
                                        "CODE" => "contacts_block_en_2"
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
                                        "CODE" => "contacts_block_en_3"
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
                                        "CODE" => "contacts_block_en_4"
                                    )
                                );?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
