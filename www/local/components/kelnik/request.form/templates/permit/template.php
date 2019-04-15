<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="b-add-request__content j-request-pass">
    <?php if(!empty($arResult['REQUEST_ID'])): ?>
        <h4>Заявка отправлена</h4>
        <p>Ваша заявка отправлена менеджеру ОЭЗ. Номер заявки <strong><?= $arResult['REQUEST_ID']; ?></strong></p>
    <?php else: ?>
        <form method="post" enctype="multipart/form-data" class="b-add-request__form">
            <div class="b-add-request__org">
                <h4>Организация</h4>

                <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['TYPE_ID'])): ?> b-form-block-error<?php endif; ?>">
                    <div class="b-select">
                        <div class="b-select__wrapper">
                            <select id="territory"
                                class="b-select__list j-select"
                                name="area"
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
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['TYPE_ID'])): ?>
                        <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['TYPE_ID']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="b-form-block b-form-block_is_long-label<?php if(!empty($arResult['ERRORS']['FIELDS']['NAME'])): ?> b-form-block-error<?php endif; ?>">
                    <input id="organization"
                        class="b-input-text"
                        type="text"
                        name="name"
                        maxlength=""
                        autocomplete=""
                        value="<?= $arResult['FORM']['NAME']; ?>"
                        placeholder=""
                        required >
                    <label for="theme-request" class="b-form-block__label">
                        Организация, подающая заявку
                    </label>
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['NAME'])): ?>
                        <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['NAME']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="b-form-block b-form-block_is_date<?php if(!empty($arResult['ERRORS']['FIELDS']['DATE_START']) || !empty($arResult['ERRORS']['FIELDS']['DATE_FINISH'])): ?> b-form-block-error<?php endif; ?>">
                    <div class="b-form-block__date">
                        <span class="b-form-block__def">С</span>
                        <input
                            id="visitTimeFrom"
                            class="b-input-text j-input-value-from j-input-calendar"
                            type="text"
                            name="timeFrom"
                            maxlength=""
                            autocomplete=""
                            value=""
                            placeholder=""
                            required readonly data-date="true" data-date-class='j-date-from'
                        >
                        <label for="theme-request" class="b-form-block__label">
                            Дата и время визита «C»
                        </label>
                    </div>

                    <div class="b-form-block__date">
                        <span class="b-form-block__def">До</span>
                        <input
                            id="visitTimeTo"
                            class="b-input-text j-input-value-to j-input-calendar"
                            type="text"
                            name="timeTo"
                            maxlength=""
                            autocomplete=""
                            value=""
                            placeholder=""
                            required readonly data-date="true" data-date-class='j-date-to'
                        >
                        <label for="theme-request" class="b-form-block__label">
                            Дата и время визита «До»
                        </label>
                    </div>

                    <div class="b-form-block-calendar j-form-block-calendars">
                        <div class="b-input-date j-input-date j-date-from"
                             data-from-year=""
                             data-from-month=""
                             data-show-months="4"
                             data-value-selector="j-input-value-from"
                             data-name="dateFrom"
                             data-sync-from="dateTo"
                             data-sync-to="">

                            <div class="b-input-date__title">С</div>

                            <ul class="b-input-date__head">
                                <li class="b-input-date__head-item">Пн</li>
                                <li class="b-input-date__head-item">Вт</li>
                                <li class="b-input-date__head-item">Ср</li>
                                <li class="b-input-date__head-item">Чт</li>
                                <li class="b-input-date__head-item">Пт</li>
                                <li class="b-input-date__head-item b-input-date__head-item_is_disabled">Сб</li>
                                <li class="b-input-date__head-item b-input-date__head-item_is_disabled">Вс</li>
                            </ul>

                            <div class="b-input-date__content j-input-date-content"></div>

                            <div class="b-input-date__addition">
                                <div class="b-input-date__time">
                                    <input type="text" class="j-input-date-time">
                                </div>
                            </div>
                        </div>

                        <div class="b-input-date j-input-date j-date-to"
                             data-from-year=""
                             data-from-month=""
                             data-show-months="4"
                             data-value-selector="j-input-value-to"
                             data-name="dateTo"
                             data-sync-from=""
                             data-sync-to="dateFrom">

                            <div class="b-input-date__title">До</div>

                            <ul class="b-input-date__head">
                                <li class="b-input-date__head-item">Пн</li>
                                <li class="b-input-date__head-item">Вт</li>
                                <li class="b-input-date__head-item">Ср</li>
                                <li class="b-input-date__head-item">Чт</li>
                                <li class="b-input-date__head-item">Пт</li>
                                <li class="b-input-date__head-item b-input-date__head-item_is_disabled">Сб</li>
                                <li class="b-input-date__head-item b-input-date__head-item_is_disabled">Вс</li>
                            </ul>

                            <div class="b-input-date__content j-input-date-content"></div>

                            <div class="b-input-date__addition">
                                <div class="b-input-date__time">
                                    <input type="text" class="j-input-date-time">
                                </div>
                            </div>
                        </div>
                    </div>

                    <label class="b-form-block__label">
                        Дата и время визита
                    </label>
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['DATE_START']) || !empty($arResult['ERRORS']['FIELDS']['DATE_FINISH'])): ?>
                        <span class="b-form-block__error-text"><?= implode(', ', [$arResult['ERRORS']['FIELDS']['DATE_START'], $arResult['ERRORS']['FIELDS']['DATE_FINISH']]); ?></span>
                    <?php endif; ?>
                </div>

                <div class="b-form-block b-form-block_is_long-label<?php if(!empty($arResult['ERRORS']['FIELDS']['TARGET'])): ?> b-form-block-error<?php endif; ?>">
                    <input id="visitTarget"
                        class="b-input-text"
                        type="text"
                        name="visitTarget"
                        maxlength=""
                        autocomplete=""
                        value="<?= $arResult['FORM']['TARGET']; ?>"
                        placeholder=""
                        required >
                    <label for="theme-request" class="b-form-block__label">
                        Цель визита
                    </label>
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['TARGET'])): ?>
                        <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['TARGET']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="b-form-block b-form-block_is_long-label<?php if(!empty($arResult['ERRORS']['FIELDS']['EXECUTIVE_COMPANY'])): ?> b-form-block-error<?php endif; ?>">
                    <input id="executiveCompany"
                        class="b-input-text"
                        type="text"
                        name="executiveCompany"
                        maxlength=""
                        autocomplete=""
                        value="<?= $arResult['FORM']['EXECUTIVE_COMPANY']; ?>"
                        placeholder=""
                        required >
                    <label for="theme-request" class="b-form-block__label">
                        Должностное лицо компании, подающее заявку
                    </label>
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['EXECUTIVE_COMPANY'])): ?>
                        <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['EXECUTIVE_COMPANY']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="b-form-block b-form-block_theme_grey b-form-block_is_long-label<?php if(!empty($arResult['ERRORS']['FIELDS']['EXECUTIVE_VISIT'])): ?> b-form-block-error<?php endif; ?>">
                    <input id="executiveVisit"
                        class="b-input-text"
                        type="text"
                        name="executiveVisit"
                        maxlength=""
                        autocomplete=""
                        value="<?= $arResult['FORM']['EXECUTIVE_VISIT']; ?>"
                        placeholder=""
                        required >
                    <label for="theme-request" class="b-form-block__label">
                        Должностное лицо компании, ответственное за визит
                    </label>
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['EXECUTIVE_VISIT'])): ?>
                        <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['EXECUTIVE_VISIT']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="b-form-block b-form-block_is_long-label<?php if(!empty($arResult['ERRORS']['FIELDS']['PHONE'])): ?> b-form-block-error<?php endif; ?>">
                    <input id="phone"
                        class="b-input-phone"
                        type="tel"
                        name="phone"
                        value="<?= $arResult['FORM']['PHONE']; ?>"
                        autocomplete="tel"
                        placeholder="+7 ___ ___-__-__">
                    <label for="phone" class="b-form-block__label">Телефон</label>
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['PHONE'])): ?>
                        <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['PHONE']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="b-add-request__pass">
                <h4>Пропуск</h4>
                <div class="b-add-request__pass-container j-pass-container">
                    <?php foreach ($arResult['FORM']['_PASS_'] as $formPass): ?>
                        <div class="b-add-request__pass__item">
                            <div class="b-form-block__half-wrap b-form-block__half-wrap_is_top">
                                <div class="b-form-block">
                                    <input id="fio"
                                        class="b-input-text"
                                        type="text"
                                        name="pass[fio][]"
                                        maxlength=""
                                        autocomplete=""
                                        value="<?= \Kelnik\Helpers\ArrayHelper::getValue($formPass, 'FIO'); ?>"
                                        placeholder=""
                                        required >
                                    <label for="theme-request" class="b-form-block__label">
                                        ФИО
                                    </label>
                                    <span class="b-form-block__error-text">Текст подсказки</span>
                                </div>
                                <div class="b-form-block">
                                    <input id="organizationPass"
                                        class="b-input-text"
                                        type="text"
                                        name="pass[organizationPass][]"
                                        maxlength=""
                                        autocomplete=""
                                        value="<?= \Kelnik\Helpers\ArrayHelper::getValue($formPass, 'ORG_NAME'); ?>"
                                        placeholder=""
                                        required >
                                    <label for="theme-request" class="b-form-block__label">
                                        Организация
                                    </label>
                                    <span class="b-form-block__error-text">Текст подсказки</span>
                                </div>
                            </div>
                            <div class="b-form-block__half-wrap b-form-block__half-wrap_is_center">
                                <div class="b-form-block">
                                    <input id="carModel"
                                        class="b-input-text"
                                        type="text"
                                        name="pass[carModel][]"
                                        maxlength=""
                                        autocomplete=""
                                        value="<?= \Kelnik\Helpers\ArrayHelper::getValue($formPass, 'CAR_VENDOR'); ?>"
                                        placeholder=""
                                        required >
                                    <label for="theme-request" class="b-form-block__label">
                                        Марка автомобиля
                                    </label>
                                    <span class="b-form-block__error-text">Текст подсказки</span>
                                </div>
                                <div class="b-form-block">
                                    <input id="stateNumber"
                                        class="b-input-text"
                                        type="text"
                                        name="pass[stateNumber][]"
                                        maxlength=""
                                        autocomplete=""
                                        value="<?= \Kelnik\Helpers\ArrayHelper::getValue($formPass, 'CAR_NUMBER'); ?>"
                                        placeholder=""
                                        required >
                                    <label for="theme-request" class="b-form-block__label">
                                        Гос. номер
                                    </label>
                                    <span class="b-form-block__error-text">Текст подсказки</span>
                                </div>
                            </div>
                            <div class="b-form-block">
                                <input id="accompanying"
                                    class="b-input-text"
                                    type="text"
                                    name="pass[accompanying][]"
                                    maxlength=""
                                    autocomplete=""
                                    value="<?= \Kelnik\Helpers\ArrayHelper::getValue($formPass, 'PERSON'); ?>"
                                    placeholder=""
                                    required >
                                <label for="theme-request" class="b-form-block__label">
                                    Фамилия и инициалы сопровождающего лица
                                </label>
                                <span class="b-form-block__error-text">Текст подсказки</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
