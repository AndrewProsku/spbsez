<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div id="#calcres" class="b-message-popup">
    <h3 class="b-message-popup__header"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_CALC_TITLE'); ?></h3>
    <form method="post" enctype="multipart/form-data" class="b-vacancy-response-form calcres-form" onsubmit="sezApp.sendCalcForm(this); return false;">
        <input id="calcdata" type="hidden" name="calcdata">
        <div class="b-vacancy-response-form__inputs">
            <div class="b-form-block j-message-email">
                <input id="message-email-calc" class="b-input-email" type="email" name="email">
                <label for="message-email-calc" class="b-form-block__label"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_EMAIL'); ?></label>
                <span class="b-form-block__error-text"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_EMAIL'); ?></span>
            </div>           
        </div>
        <button class="button b-vacancy__button"><?= \Bitrix\Main\Localization\Loc::getMessage('KELNIK_TMPL_FORM_SEND'); ?></button>
    </form>
</div>
