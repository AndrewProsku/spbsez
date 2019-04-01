<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Contacts");
$APPLICATION->SetPageProperty('title', 'Contacts | SEZ');

$yMapJson = [
    "center" => [59.942099, 30.186182],
    "scrollwheel" => false,
    "fullScreenControl" => false,
    "customZoomControl" => true,
    "htmlMarkers" => [
      [
        "title" => "Novoorlovskaya",
        "layout" => "secondary",
        "coords" => [60.053400, 30.231714]
      ],
      [
        "title" => "Noydorf",
        "layout" => "secondary",
        "coords" => [59.840573, 30.005940]
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
                <div class="l-contacts__yandex-map b-yandex-map j-yandex-map" data-lang="<?= LANGUAGE_ID; ?>" data-json="<?= $yMapJson; ?>">
                    <div id="first" class="b-yandex-map__base"></div>
                </div>
            </div>
            <div class="l-contacts__map-text">
                <div class="l-contacts__map-text-item">
                    <div class="l-contacts__map-text-title">
                        <p>Novoorlovskaya site</p>
                    </div>
                    <div class="l-contacts__map-text-desc">
                        <p>Doroga v Kamenku, St.Petersburg</p>
                    </div>
                    <a href="#" class="l-contacts__map-text-link b-link-line j-contacts-location"
                       data-coordinates="[60.053400, 30.231714]">
                        Location
                    </a>
                </div>

                <div class="l-contacts__map-text-item">
                    <div class="l-contacts__map-text-title">
                        <p>Noydorf site</p>
                    </div>
                    <div class="l-contacts__map-text-desc">
                        <p>Svyazi Street, 34 A, Strelna, St.Petersburg</p>
                    </div>
                    <a href="#" class="l-contacts__map-text-link b-link-line j-contacts-location"
                       data-coordinates="[59.840573, 30.005940]">
                        Location
                    </a>
                </div>
            </div>
        </div>

        <div class="l-contacts__content" id="managment">

            <div class="l-contacts__block">
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
            <div class="l-contacts__block l-contacts__block_width_half-left">
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

            <div class="l-contacts__block l-contacts__block_width_half-right">
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
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
