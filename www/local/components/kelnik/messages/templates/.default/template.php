<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>


<div class="b-message-filter">
    <div class="b-message-filter__mobile-date j-messages-select">
        <div class="b-select">
            <div class="b-select__wrapper">
                <select
                        id="message-date"
                        class="b-select__list j-select"
                        name="message-date"
                        data-placeholder=""
                >

                    <option value="2018"
                    >
                        2018
                    </option>
                    <option value="2017"
                    >
                        2017
                    </option>
                    <option value="2016"
                    >
                        2016
                    </option>
                    <option value="2015"
                    >
                        2015
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="b-message-filter__date j-messages-tabs">
        <ul class="b-tabs b-tabs-ajax">
            <li class="b-tabs__item is-active j-tabs__item"
                data-link="/tests/messages.json"
                data-year="2018">
                2018
            </li>
            <li class="b-tabs__item  j-tabs__item"
                data-link="/tests/messages-2017.json"
                data-year="2017">
                2017
            </li>
            <li class="b-tabs__item  j-tabs__item"
                data-link="/tests/messages.json"
                data-year="2016">
                2016
            </li>
            <li class="b-tabs__item  j-tabs__item"
                data-link="/tests/messages.json"
                data-year="2015">
                2015
            </li>
        </ul>
    </div>
    <div class="b-message-filter__search">
        <form action="" method="POST" enctype="multipart/form-data" class="b-message-filter__search-form">
            <input
                    id="message-search"
                    class="b-input-search"
                    type="search"
                    name=""
                    placeholder="Поиск сообщений"
            >
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
