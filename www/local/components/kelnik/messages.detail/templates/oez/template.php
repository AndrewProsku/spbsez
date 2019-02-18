<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="b-profile-message-item-back">
    <a href="<?= $arParams['SEF_FOLDER']; ?>" class="b-profile-message-item-back__button">
        Назад к сообщениям
    </a>
</div>

<div class="b-profile-message-item-block">
    <time datetime="<?= $arResult['ELEMENT']['DATE']; ?>" class="b-profile-message-item-block__date"><?= $arResult['ELEMENT']['DATE_FORMAT']; ?></time>
    <h2 class="b-profile-message-item-block__title"><?= $arResult['ELEMENT']['NAME']; ?></h2>
    <div class="b-profile-message-item-block__desc"><?= $arResult['ELEMENT']['TEXT']; ?></div>
    <?php if(!empty($arResult['ELEMENT']['FILES'])): ?>
        <div class="b-profile-message-item-block__files">
            <h3 class="b-profile-message-item-block__files-title">Прикрепленные файлы</h3>
            <ul class="b-profile-message-item-block__files-wrap">
                <?php foreach ($arResult['ELEMENT']['FILES'] as $fileData): ?>
                    <li class="b-profile-message-item-block__file-item b-profile-message-item-block__file-item_ext_<?= $fileData['EXT']; ?>">
                        <h4><?= $fileData['ORIGINAL_NAME']; ?></h4>
                        <a href="<?= $fileData['SRC']; ?>" class="b-link-line">Скачать</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
