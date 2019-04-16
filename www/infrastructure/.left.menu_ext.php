<?php

try {
    \Bitrix\Main\Application::getInstance()->getTaggedCache()->registerTag('kelnik:infrastructureList');
    \Bitrix\Main\Loader::includeModule('kelnik.infrastructure');
    \Bitrix\Main\Localization\Loc::loadMessages(
        \Bitrix\Main\Application::getDocumentRoot() .
        getLocalPath('components/kelnik/infrastructure.list/class.php')
    );

    $elements = \Kelnik\Infrastructure\Model\PlatformTable::getActiveList();

    if (!$elements) {
        return;
    }

    foreach ($elements as $v) {
        $v = \Kelnik\Infrastructure\Model\PlatformTable::replaceFieldsByLang($v, LANGUAGE_ID);
        $aMenuLinks[] = [
            \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM') . ' «' . $v['NAME'] . '»',
            LANG_DIR . 'infrastructure/' . $v['ALIAS'] . '/',
            [],
            [],
            ''
        ];
    }

} catch (Exception $e) {
    return;
}
