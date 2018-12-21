<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="b-add-request__content">
    <?php if(!empty($arResult['REQUEST_ID'])): ?>
        <h4>Заявка отправлена</h4>
        <p>Ваша заявка отправлена менеджеру ОЭЗ. Номер заявки <strong><?= $arResult['REQUEST_ID']; ?></strong></p>
        <p>Ответ по заявке прийдет на эл. почту <strong><?= $arResult['USER_EMAIL']; ?></strong></p>
    <?php else: ?>
        <h4>Заявка</h4>
        <form method="post" enctype="multipart/form-data" class="b-add-request__form">
            <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['NAME'])): ?> b-form-block-error<?php endif; ?>">
                <input id="theme-request"
                        class="b-input-text"
                        type="text"
                        name="name"
                        maxlength=""
                        autocomplete=""
                        value="<?= $arResult['FORM']['NAME']; ?>"
                        placeholder=""
                        required>

                <label for="theme-request" class="b-form-block__label">Тема заявки</label>
                <?php if(!empty($arResult['ERRORS']['FIELDS']['NAME'])): ?>
                <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['NAME']; ?></span>
                <?php endif; ?>
            </div>
            <div class="b-form-block<?php if(!empty($arResult['ERRORS']['FIELDS']['THEME'])): ?> b-form-block-error<?php endif; ?>">
                <div class="b-select">
                    <div class="b-select__wrapper">
                        <select id="appeal"
                                class="b-select__list j-select"
                                name="theme"
                                data-placeholder="">
                            <?php foreach ($arResult['TYPES'] as $type): ?>
                                <option value="<?= $type['ID']; ?>"<?php if(!empty($arResult['FORM']['TYPE'] == $type['ID'])): ?> selected<?php endif; ?>><?= $type['NAME']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <label for="appeal" class="b-form-block__label">
                    Причина обращения
                </label>
                <?php if(!empty($arResult['ERRORS']['FIELDS']['THEME'])): ?>
                    <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['THEME']; ?></span>
                <?php endif; ?>
            </div>
            <div class="b-form-block b-form-block-textarea<?php if(!empty($arResult['ERRORS']['FIELDS']['MESSAGE'])): ?> b-form-block-error<?php endif; ?>">
                <textarea name="message" id="request-message" class="b-form-block__message" required><?= $arResult['FORM']['MESSAGE']; ?></textarea>
                <label for="request-message" class="b-form-block__label">
                    Сообщение
                </label>
                <?php if(!empty($arResult['ERRORS']['FIELDS']['MESSAGE'])): ?>
                    <span class="b-form-block__error-text"><?= $arResult['ERRORS']['FIELDS']['MESSAGE']; ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" class="button b-add-request__form-button">
                Подать заявку
            </button>
        </form>
    <?php endif; ?>
</div>
