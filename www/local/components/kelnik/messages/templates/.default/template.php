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
                    data-link="/tests/messages.json"
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
    <div class="b-message-block-title">Декабрь</div>

    <div class="b-message-block">
        <!-- Для отображения нового сообщения добавить класс is-new -->
        <div class="b-message-block__item is-new">
            <div class="b-message-block__item-title">
                <a href="#" class="b-link-line">Объем инвестиций и капитальных вложений в период деятельности заявителя в ОЭЗ в течен</a>
            </div>
            <div class="b-message-block__item-desc">
                <span class="b-message-block__item-time">11:30</span>
                <time datetime="2018-12-26" class="b-message-block__item-date">26.12.2018</time>
            </div>
        </div>
        <div class="b-message-block__item is-new">
            <div class="b-message-block__item-title">
                <a href="#" class="b-link-line">Объем инвестиций и капитальных вложений в период деятельности заявителя в ОЭЗ в течен</a>
            </div>
            <div class="b-message-block__item-desc">
                <span class="b-message-block__item-time">11:30</span>
                <time datetime="2018-12-26" class="b-message-block__item-date">26.12.2018</time>
            </div>
        </div>
        <div class="b-message-block__item ">
            <div class="b-message-block__item-title">
                <a href="#" class="b-link-line">Объем инвестиций и капитальных вложений в период деятельности заявителя в ОЭЗ в течен</a>
            </div>
            <div class="b-message-block__item-desc">
                <span class="b-message-block__item-time">11:30</span>
                <time datetime="2018-12-26" class="b-message-block__item-date">26.12.2018</time>
            </div>
        </div>
    </div>
    <div class="b-message-block-title">Ноябрь</div>

    <div class="b-message-block">
        <!-- Для отображения нового сообщения добавить класс is-new -->
        <div class="b-message-block__item ">
            <div class="b-message-block__item-title">
                <a href="#" class="b-link-line">Заявка А-11-34534. Заявка в обработке</a>
            </div>
            <div class="b-message-block__item-desc">
                <span class="b-message-block__item-time">09:27</span>
                <time datetime="2018-11-26" class="b-message-block__item-date">26.11.2018</time>
            </div>
        </div>
        <div class="b-message-block__item ">
            <div class="b-message-block__item-title">
                <a href="#" class="b-link-line">Заявка А-11-35635. Заявка в обработке</a>
            </div>
            <div class="b-message-block__item-desc">
                <span class="b-message-block__item-time">13:45</span>
                <time datetime="2018-11-20" class="b-message-block__item-date">20.11.2018</time>
            </div>
        </div>
        <div class="b-message-block__item ">
            <div class="b-message-block__item-title">
                <a href="#" class="b-link-line">Заявка А-11-13676. Заявка в обработке</a>
            </div>
            <div class="b-message-block__item-desc">
                <span class="b-message-block__item-time">17:12</span>
                <time datetime="2018-11-20" class="b-message-block__item-date">20.11.2018</time>
            </div>
        </div>
    </div>
</div>

<div class="b-message-more">
    <button type="button" class="b-message-more__button button-add j-more" data-send="/tests/messages.json">
        Загрузить еще сообщения
    </button>
</div>
