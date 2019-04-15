<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div id="#message" class="b-message-popup">
    <h3 class="b-message-popup__header"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_WRITE_MESSAGES'); ?></h3>
    <form method="post" enctype="multipart/form-data" class="b-vacancy-response-form j-message-form">
        <div class="b-vacancy-response-form__inputs">
            <div class="b-form-block j-message-text b-input-textarea">
                <textarea id="text" class="b-input-text" name="text" rows="3" cols="" placeholder=""></textarea>
                <label for="admin-fio" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_COMMENT'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_COMMENT'); ?></span>
            </div>
            <div class="b-form-block j-message-fio">
                <input id="message-fio" class="b-input-text" type="text" name="fio" maxlength="" autocomplete="" value="" placeholder="">
                <label for="message-fio" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_NAME'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_NAME'); ?></span>
            </div>
            <div class="b-form-block j-message-email">
                <input id="message-email" class="b-input-email" type="email" name="email">
                <label for="message-email" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_EMAIL'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_EMAIL'); ?></span>
            </div>
            <div class="b-form-block j-message-phone">
                <input id="message-phone" class="b-input-phone" type="tel" name="phone" autocomplete="tel" placeholder="<?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_PHONE_MASK'); ?>">
                <label for="message-phone" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_PHONE'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_PHONE'); ?></span>
            </div>
        </div>
        <button class="button b-vacancy__button j-message-submit"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_SEND'); ?></button>
    </form>
</div>
