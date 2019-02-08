<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('Сообщения от ОЭЗ');
$APPLICATION->SetPageProperty('title', 'Сообщения от ОЭЗ | АООЭЗ');
if (!$USER->IsAuthorized()) {
    LocalRedirect('/cabinet/auth/');
}
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "submenu-cabinet",
    Array(
        "ALLOW_MULTI_SELECT" => "N",
        "DELAY" => "N",
        "MAX_LEVEL" => "1",
        "MENU_CACHE_GET_VARS" => array(""),
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "left",
        "USE_EXT" => "Y"
    )
);?>
<div class="l-profile-message-item">
    <div class="b-title b-profile-message-item-title">
        <h1><?php $APPLICATION->ShowTitle(false); ?></h1>
    </div>
    <?$APPLICATION->IncludeComponent(
        "kelnik:messages",
        "search",
        Array(
            'CACHE_TIME' => '3600',
            'CACHE_TYPE' => 'A',
            'IS_SEARCH' => 'Y'
        )
    );?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
