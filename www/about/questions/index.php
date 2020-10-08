<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Часто задаваемые вопросы");
?>
	<?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "submenu-about",
        Array(
            "ALLOW_MULTI_SELECT" => "N",
            "DELAY" => "N",
            "MAX_LEVEL" => "1",
            "MENU_CACHE_GET_VARS" => array(""),
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "ROOT_MENU_TYPE" => "left",
            "USE_EXT" => "N"
        )
    );?>
    <div class="l-faq">
        <div class="b-faq-title">
            <h1><? $APPLICATION->ShowTitle(false); ?></h1>
            <form action="" class="faq-search">
                <input type="text" class="faq-search__field" name="q" placeholder="Поиск по ключевым словам" value="<?=$_GET["q"]?>">
            </form>
        </div>        
        <?$APPLICATION->IncludeComponent(
			"kelnik:questions.list",
			"faq",
			Array(
                "SEARCH_NAME" => "q",
                "USE_TYPES" => "Y",
				"CACHE_TIME" => "360000",
	            "CACHE_TYPE" => "N"
			),
			false
		);?>
    </div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>