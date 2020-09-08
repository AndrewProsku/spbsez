<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__DIR__ . '/../../template.php');

$this->setFrameMode(true);

if (empty($arResult['BLOCK']['BODY'])) {
    if ($arResult['SHOW_NOTICE']) {
        ShowMessage(Loc::getMessage('KELNIK_TEXT_BLOCK_EMPTY'));
    }
    return;
} elseif ($arResult['BLOCK']['ACTIVE'] != 'Y') {
    if ($arResult['SHOW_NOTICE']) {
        ShowMessage(Loc::getMessage('KELNIK_TEXT_BLOCK_INACTIVE'));
    }
    return;
}
?>

<?= $arResult['BLOCK']['BODY']; ?>