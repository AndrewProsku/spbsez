<?php

try {
    \Bitrix\Main\Application::getInstance()->getTaggedCache()->registerTag('kelnik:infrastructureList');
    \Bitrix\Main\Loader::includeModule('kelnik.infrastructure');
    \Bitrix\Main\Localization\Loc::loadMessages(
        \Bitrix\Main\Application::getDocumentRoot() .
        getLocalPath('components/kelnik/infrastructure.list/class.php')
    );

    $elements = \Kelnik\Infrastructure\Model\PlatformTable::getActiveList(true);

    if (!$elements) {
        return;
    }

    $prefix = \Bitrix\Main\Localization\Loc::getMessage('KELNIK_INFRA_COMP_PLATFORM') . ' ';
    foreach ($elements as $v) {
        $v = \Kelnik\Infrastructure\Model\PlatformTable::replaceFieldsByLang($v, LANGUAGE_ID);
        if($v['NAME'] == 'Инновационный центр'){
            $name = $v['NAME'];
        }else{
            $name = $prefix . '«' . $v['NAME'] . '»';
        }
        $aMenuLinks[] = [
            $name,
            LANG_DIR . 'infrastructure/' . $v['ALIAS'] . '/',
            [],
            [],
            ''
        ];
    }

} catch (Exception $e) {
    return;
}
