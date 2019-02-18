<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php if(!$arResult['YEARS']): ?>
    <p>Нет сообщений</p>
    <?php return; ?>
<?php endif; ?>

<div class="b-message-filter">
    <div class="b-message-filter__mobile-date j-messages-select">
        <div class="b-select">
            <div class="b-select__wrapper">
                <select id="message-date"
                        class="b-select__list j-select"
                        name="year"
                        data-placeholder="">
                    <?php foreach ($arResult['YEARS'] as $year): ?>
                        <option value="<?= $year; ?>"<?php if($year == $arParams['YEAR']): ?> selected<?php endif; ?>><?= $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="b-message-filter__date j-messages-tabs">
        <ul class="b-tabs b-tabs-ajax">
            <?php foreach ($arResult['YEARS'] as $year): ?>
                <li class="b-tabs__item<?php if($year == $arParams['YEAR']): ?> is-active<?php endif; ?> j-tabs__item"
                    data-link="/api/messages/"
                    data-year="<?= $year; ?>"><?= $year; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="b-message-filter__search">
        <form action="/cabinet/messages/search/"
              enctype="application/x-www-form-urlencoded"
              class="b-message-filter__search-form">
            <input id="message-search"
                    class="b-input-search"
                    type="search"
                    name="q"
                    placeholder="Поиск сообщений">
            <button type="submit" class="b-message-filter__search-form-button">
                <img src="/images/forms/search-icon.png" alt="Найти">
            </button>
        </form>
    </div>
</div>
<div class="b-message-block-wrap j-messages">
    <?php foreach ($arResult['MESSAGES'] as $month): ?>
        <div class="b-message-block-title"><?= $month['NAME']; ?></div>
        <div class="b-message-block">
            <?php foreach ($month['ELEMENTS'] as $msg): ?>
            <div class="b-message-block__item<?php if($msg['IS_NEW'] === \Kelnik\Messages\Model\MessagesTable::YES): ?> is-new<?php endif; ?>">
                <div class="b-message-block__item-title">
                    <a href="<?= $msg['LINK']; ?>" class="b-link-line"><?= $msg['NAME']; ?></a>
                </div>
                <div class="b-message-block__item-desc">
                    <span class="b-message-block__item-time"><?= $msg['TIME']; ?></span>
                    <time datetime="<?= $msg['DATE']; ?>" class="b-message-block__item-date"><?= $msg['DATE_FORMAT']; ?></time>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php if($arResult['SHOW_MORE']): ?>
<div class="b-message-more">
    <button type="button" class="b-message-more__button button-add j-more" data-send="/api/messages/">
        Загрузить еще сообщения
    </button>
</div>
<?php endif; ?>