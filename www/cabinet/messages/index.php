<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('Сообщения от ОЭЗ');
$APPLICATION->SetPageProperty('title', 'Сообщения от ОЭЗ | АООЭЗ');
if (!$USER->IsAuthorized()) {
    LocalRedirect(LANG_DIR . 'cabinet/auth/');
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
<div class="l-profile-messages">
    <div class="b-title b-profile-message-title">
        <h1><?php $APPLICATION->ShowTitle(false); ?></h1>
    </div>
    <?$APPLICATION->IncludeComponent(
	"kelnik:messages", 
	"oez", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "oez",
		"USE_SEARCH" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => LANG_DIR . "cabinet/messages/",
		"LIST_DATE_FORMAT" => "H:i d.m.Y",
		"SET_404" => "Y",
		"DETAIL_DATE_FORMAT" => "d.m.Y, H:i",
		"SEF_URL_TEMPLATES" => array(
			"list" => "",
			"search" => "search/",
			"detail" => "#ELEMENT_TYPE##ELEMENT_ID#/",
		)
	),
	false
);?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
