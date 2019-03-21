<?php

$MESS['KELNIK_INFRASTRUCTURE_SORT'] = 'Сортировка';
$MESS['KELNIK_INFRASTRUCTURE_ACTIVE'] = 'Активен';
$MESS['KELNIK_INFRASTRUCTURE_NAME'] = 'Название';
$MESS['KELNIK_INFRASTRUCTURE_NAME_EN'] = 'Название (англ.)';

$MESS['KELNIK_INFRASTRUCTURE_TEXT'] = 'Описание';
$MESS['KELNIK_INFRASTRUCTURE_TEXT_EN'] = 'Описание (англ.)';

$MESS['KELNIK_INFRASTRUCTURE_VIDEO'] = 'Видео';
$MESS['KELNIK_INFRASTRUCTURE_IMAGE'] = 'Изображение';
$MESS['KELNIK_INFRASTRUCTURE_MAP_COORDS_LAT'] = 'Координаты карты (lat)';
$MESS['KELNIK_INFRASTRUCTURE_MAP_COORDS_LNG'] = 'Координаты карты (lng)';
$MESS['KELNIK_INFRASTRUCTURE_MAP_COORDS_CENTER_LAT'] = 'Координаты центра карты (lat)';
$MESS['KELNIK_INFRASTRUCTURE_MAP_COORDS_CENTER_LNG'] = 'Координаты центра карты (lng)';
$MESS['KELNIK_INFRASTRUCTURE_PLANOPLAN'] = 'Код PlanoPlan';

$tabMess = [
    'TEXT' => 'Описание',
    'TEXT_FEATURES' => 'Показатели',
    'TEXT_TRAITS' => 'Особенности',
    'TEXT_AREA' => 'Аренда',
    'TEXT_MAP' => 'Карта',
    'TEXT_INFRA' => 'Инфраструктура',
    'TEXT_CUSTOMS' => 'Таможня',
    'TEXT_GALLERY' => 'Галерея',
    'TEXT_ADVANTAGES1' => 'Преимущества 1',
    'TEXT_ADVANTAGES2' => 'Преимущества 2',
    'TEXT_ADVANTAGES3' => 'Преимущества 3',
    'IMAGES' => 'Галерея'
];

foreach (\Kelnik\Infrastructure\Model\PlatformTable::getFields() as $field) {
    $MESS['KELNIK_INFRASTRUCTURE_TAB_' . $field] = $tabMess[$field];

    foreach (\Kelnik\Infrastructure\Model\PlatformTable::getLangs() as $fieldLang) {
        $MESS['KELNIK_INFRASTRUCTURE_' . $field . '_' . $fieldLang] = $tabMess[$field] . ($fieldLang === 'EN' ? ' (англ.)' : '');
    }
}
