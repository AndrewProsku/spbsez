<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Политика в отношении обработки персональных данных");
$APPLICATION->SetPageProperty('title', 'Политика конфиденциальности');
?><div class="l-personal-data">
	<div class="l-personal-data__title b-title">
		<h1><? $APPLICATION->ShowTitle(false); ?></h1>
	</div>
	<div class="l-personal-data__content">
		 <?$APPLICATION->IncludeComponent(
	"kelnik:textblocks",
	"",
	Array(
		"CACHE_TIME" => "360000",
		"CACHE_TYPE" => "A",
		"CODE" => "pp_block_1"
	)
);?>
	</div>
</div>
<br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>