<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php
\Bitrix\Main\Localization\Loc::loadMessages(__DIR__ . '/../../template.php');
?>
<div id="#service" class="b-service-popup">
    <h3 class="b-service-popup__header"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_HEADER'); ?></h3>
    <form method="post" enctype="multipart/form-data" class="b-service-response-form j-service-form">
        <div class="b-service-response-form__inputs">
            <div class="b-form-block j-service-title-select">
                <div class="b-select">
                    <div class="b-select__wrapper">
                        <select id="service-title"
                                class="b-select__list j-select"
                                name="type"
                                data-placeholder="">
                            <?php foreach ($arResult['TYPES'] as $typeId => $typeName): ?>
                                <option value="<?= $typeId; ?>"><?= $typeName; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <label for="service-title" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_SUBJECT'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_SUBJECT'); ?></span>
            </div>
            <div class="b-form-block j-service-text b-input-textarea">
                <textarea id="text"
                          class="b-input-text"
                          name="text"
                          rows="3"
                          cols=""
                          placeholder=""></textarea>

                <label for="admin-fio" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_COMMENT'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_COMMENT'); ?></span>
            </div>
            <div class="b-form-block j-service-company">
                <input id="company"
                       class="b-input-text"
                       type="text"
                       name="company"
                       maxlength=""
                       autocomplete=""
                       value=""
                       placeholder="">
                <label for="admin-fio" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_COMPANY'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_COMPANY'); ?></span>
            </div>
            <div class="b-form-block j-service-fio">
                <input id="service-fio"
                       class="b-input-text"
                       type="text"
                       name="fio"
                       maxlength=""
                       autocomplete=""
                       value=""
                       placeholder="">
                <label for="admin-fio" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_NAME'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_NAME'); ?></span>
            </div>
            <div class="b-form-block j-service-position">
                <input id="position"
                       class="b-input-text"
                       type="text"
                       name="position"
                       maxlength=""
                       autocomplete=""
                       value=""
                       placeholder="">
                <label for="email" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_POSITION'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_POSITION'); ?></span>
            </div>
            <div class="b-form-block j-service-email">
                <input id="service-email"
                       class="b-input-email"
                       type="email"
                       name="email">
                <label for="email" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_EMAIL'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_EMAIL'); ?></span>
            </div>
            <div class="b-form-block j-service-phone">
                <input id="service-phone"
                       class="b-input-phone"
                       type="tel"
                       name="phone"
                       autocomplete="tel"
                       placeholder="<?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_PHONE_MASK'); ?>">
                <label for="admin-phone" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_PHONE'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_PHONE'); ?></span>
            </div>
        </div>

        <button class="button b-service__button j-service-submit"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REQ_TMPL_SUBMIT'); ?></button>
    </form>
</div>
