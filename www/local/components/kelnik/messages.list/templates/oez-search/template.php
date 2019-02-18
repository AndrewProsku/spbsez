<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="b-message-filter">
    <div class="b-message-filter__search">
        <form method="GET" enctype="multipart/form-data" class="b-message-filter__search-form">
            <input id="message-search"
                    class="b-input-search"
                    type="search"
                    name="q"
                    placeholder="Поиск сообщений"
                    value="<?= $arResult['QUERY']; ?>">
            <button type="submit" class="b-message-filter__search-form-button">
                <img src="/images/forms/search-icon.png" alt="Найти">
            </button>
        </form>
    </div>
</div>

<div class="b-message-block-wrap">
    <div class="b-message-block-title">Найдено <?= $arResult['CNT'] . ' ' . $arResult['CNT_WORD']; ?>:</div>
    <?php if($arResult['MESSAGES']): ?>
        <div class="b-message-block">
            <?php foreach ($arResult['MESSAGES'] as $msg): ?>
                <div class="b-message-block__item">
                    <div class="b-message-block__item-title<?php if($msg['IS_NEW'] === \Kelnik\Messages\Model\MessagesTable::YES): ?> is-new<?php endif; ?>">
                        <a href="<?= $msg['LINK']; ?>" class="b-link-line"><?= $msg['NAME']; ?></a>
                    </div>
                    <div class="b-message-block__item-desc">
                        <span class="b-message-block__item-time"><?= $msg['TIME']; ?></span>
                        <time datetime="<?= $msg['DATE']; ?>" class="b-message-block__item-date"><?= $msg['DATE_FORMAT']; ?></time>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
