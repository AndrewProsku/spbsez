<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="b-add-request__content j-request-pass">
    <?php if(!empty($arResult['REQUEST_ID'])): ?>
        <h4>Заявка отправлена</h4>
        <p>Ваша заявка отправлена менеджеру ОЭЗ. Номер заявки <strong><?= $arResult['REQUEST_ID']; ?></strong></p>
    <?php else: ?>
        <form method="post" enctype="multipart/form-data" class="b-add-request__form">
            <!--
             Для вывода ошибки и текста подсказки добавить класс b-form-block-error - на блок с классом b-form-block
             Для правильно заполненного блока добавить класс b-form-block-success - на блок с классом b-form-block
             -->
            <div class="b-add-request__org">
                <h4>Организация</h4>

                <div class="b-form-block">
                    <div class="b-select">
                        <div class="b-select__wrapper">
                            <select id="territory"
                                class="b-select__list j-select"
                                name="TERRITORY_ID"
                                data-placeholder="">
                                <?php foreach ($arResult['TYPES'] as $type): ?>
                                <option value="<?= $type['ID']; ?>"<?php if(!empty($arResult['FORM']['TERRITORY'] == $type['ID'])): ?> selected<?php endif; ?>><?= $type['NAME']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <label for="appeal" class="b-form-block__label">
                        Территория для проезда
                    </label>
                </div>

                <div class="b-form-block">
                    <input id="organization"
                        class="b-input-text"
                        type="text"
                        name="NAME"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder=""
                        required>

                    <label for="theme-request" class="b-form-block__label">
                        Организация, подающая заявку
                    </label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block">
                    <input id="visitTime"
                        class="b-input-text"
                        type="text"
                        name="visitTime"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder=""
                        required                                          data-date="true">

                    <label for="theme-request" class="b-form-block__label">
                        Дата и время визита
                    </label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block">
                    <input
                        id="visitTarget"
                        class="b-input-text"
                        type="text"
                        name="visitTarget"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder=""
                        required                                         >

                    <label for="theme-request" class="b-form-block__label">
                        Цель визита
                    </label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block">
                    <input
                        id="executiveCompany"
                        class="b-input-text"
                        type="text"
                        name="executiveCompany"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder=""
                        required                                         >

                    <label for="theme-request" class="b-form-block__label">
                        Должностное лицо компании, подающее заявку
                    </label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block b-form-block_theme_grey">
                    <input
                        id="executiveVisit"
                        class="b-input-text"
                        type="text"
                        name="executiveVisit"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder=""
                        required                                         >

                    <label for="theme-request" class="b-form-block__label">
                        Должностное лицо компании, ответственное за визит
                    </label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block">
                    <input
                        id="phone"
                        class="b-input-phone"
                        type="tel"
                        name="phone"
                        autocomplete="tel"
                        placeholder="+7 ___ ___-__-__"
                    >

                    <label for="phone" class="b-form-block__label">Телефон</label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>
            </div>

            <div class="b-add-request__pass">
                <h4>Пропуск</h4>

                <div class="b-add-request__pass-container j-pass-container">

                    <div class="b-add-request__pass__item">

                        <div class="b-form-block__half-wrap b-form-block__half-wrap_is_top">
                            <div class="b-form-block">
                                <input
                                    id="fio"
                                    class="b-input-text"
                                    type="text"
                                    name="fio[]"
                                    maxlength=""
                                    autocomplete=""
                                    value=""
                                    placeholder=""
                                    required                                         >

                                <label for="theme-request" class="b-form-block__label">
                                    ФИО
                                </label>
                                <span class="b-form-block__error-text">Текст подсказки</span>
                            </div>

                            <div class="b-form-block">
                                <input
                                    id="organizationPass"
                                    class="b-input-text"
                                    type="text"
                                    name="organizationPass[]"
                                    maxlength=""
                                    autocomplete=""
                                    value=""
                                    placeholder=""
                                    required                                         >

                                <label for="theme-request" class="b-form-block__label">
                                    Организация
                                </label>
                                <span class="b-form-block__error-text">Текст подсказки</span>
                            </div>
                        </div>

                        <div class="b-form-block__half-wrap b-form-block__half-wrap_is_center">
                            <div class="b-form-block">
                                <input
                                    id="carModel"
                                    class="b-input-text"
                                    type="text"
                                    name="carModel[]"
                                    maxlength=""
                                    autocomplete=""
                                    value=""
                                    placeholder=""
                                    required                                         >

                                <label for="theme-request" class="b-form-block__label">
                                    Марка автомобиля
                                </label>
                                <span class="b-form-block__error-text">Текст подсказки</span>
                            </div>

                            <div class="b-form-block">
                                <input
                                    id="stateNumber"
                                    class="b-input-text"
                                    type="text"
                                    name="stateNumber[]"
                                    maxlength=""
                                    autocomplete=""
                                    value=""
                                    placeholder=""
                                    required                                         >

                                <label for="theme-request" class="b-form-block__label">
                                    Гос. номер
                                </label>
                                <span class="b-form-block__error-text">Текст подсказки</span>
                            </div>
                        </div>

                        <div class="b-form-block">
                            <input
                                id="accompanying"
                                class="b-input-text"
                                type="text"
                                name="accompanying[]"
                                maxlength=""
                                autocomplete=""
                                value=""
                                placeholder=""
                                required                                         >

                            <label for="theme-request" class="b-form-block__label">
                                Фамилия и инициалы сопровождающего лица
                            </label>
                            <span class="b-form-block__error-text">Текст подсказки</span>
                        </div>

                    </div>

                </div>

                <div class="b-profile-add">
                    <button class="button-add j-add-pass" type="button">Добавить еще пропуск</button>
                </div>
            </div>

            <button type="submit" class="button b-add-request__form-button">
                Подать заявку
            </button>
        </form>
    <?php endif; ?>
</div>
