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

                <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['NAME'])): ?> b-form-block-error<?php endif; ?>">
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

                <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['DATE_START'])): ?> b-form-block-error<?php endif; ?>">
                    <input id="visitTime"
                        class="b-input-text"
                        type="text"
                        name="timeFrom"
                        maxlength=""
                        autocomplete=""
                        value="<?= $arResult['FORM']['DATE_START']; ?>"
                        placeholder=""
                        required data-date="true">
                    <label for="theme-request" class="b-form-block__label">
                        Дата и время визита
                    </label>
                    <?php if(!empty($arResult['ERRORS']['FIELDS']['DATE_START'])): ?>
                        <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['DATE_START']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['TARGET'])): ?> b-form-block-error<?php endif; ?>">
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

                <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['EXECUTIVE_COMPANY'])): ?> b-form-block-error<?php endif; ?>">
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

                <div class="b-form-block b-form-block_theme_grey<?php if(!empty($arResult['ERRORS']['FIELDS']['EXECUTIVE_VISIT'])): ?> b-form-block-error<?php endif; ?>">
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

                <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['PHONE'])): ?> b-form-block-error<?php endif; ?>">
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
