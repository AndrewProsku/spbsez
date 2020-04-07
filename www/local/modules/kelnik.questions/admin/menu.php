<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kelnik\Questions\Model\AdminInterface\QuestionsListHelper;
use Kelnik\Questions\Model\AdminInterface\QuestionsEditHelper;

if (!Loader::includeModule('kelnik.admin_helper')
    || !Loader::includeModule('kelnik.questions')
) {
    return;
}

Loc::loadMessages(__FILE__);

if ($GLOBALS['APPLICATION']->GetUserRight('kelnik.questions') < 'R') {
    return [];
}

return [
    'parent_menu' => 'global_menu_content',
    'sort' => 180,
    'icon' => 'iblock_menu_icon_types',
    'page_icon' => 'iblock_menu_icon_types',
    'text' => Loc::getMessage('KELNIK_QUESTIONS_MENU'),
    "items_id"    => 'kelnik_questions',
    'url' => QuestionsListHelper::getUrl(),
    'more_url' => [
        QuestionsEditHelper::getUrl(),
    ]
];
