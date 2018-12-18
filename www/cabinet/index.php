<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetPageProperty('title', 'Личный кабинет | РОСОЭЗ');
if (!$USER->IsAuthorized()) {
    LocalRedirect('/cabinet/auth/');
}
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>