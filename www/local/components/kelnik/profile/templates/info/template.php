<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="j-profile-info">
    <div class="b-profile-block">
        <div class="b-profile-block__title">
            <h4>Администратор</h4>
        </div>
        <div class="b-profile-block__input-wrap j-profile-info-administrator">
            <div class="b-form-block">
                <input id="profile-name"
                       class="b-input-text"
                       type="text"
                       name="profile[FULL_NAME]"
                       maxlength="255"
                       autocomplete=""
                       placeholder="">
                <label for="profile-name" class="b-form-block__label">ФИО</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block">
                <input id="profile-email"
                       class="b-input-email"
                       type="email"
                       name="profile[EMAIL]">
                <label for="profile-email" class="b-form-block__label">Эл.почта</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block">
                <input id="profile-phone"
                       class="b-input-phone"
                       type="tel"
                       name="profile[WORK_PHONE]"
                       autocomplete="tel"
                       placeholder="+7(___) ___-__-__">
                <label for="profile-phone" class="b-form-block__label">Телефон</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block">
                <input id="profile-status"
                       class="b-input-text"
                       type="text"
                       name="profile[STATUS]"
                       maxlength=""
                       autocomplete=""
                       placeholder="" readonly>
                <label for="profile-status" class="b-form-block__label">Статус</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
        </div>
    </div>

    <div class="b-profile-block">
        <div class="b-profile-block__title">
            <h4>Компания</h4>
        </div>
        <div class="b-profile-block__input-wrap j-profile-info-company">
            <div class="b-form-block">
                <input id="company-resident"
                        class="b-input-text"
                        type="text"
                        name="profile[WORK_COMPANY]"
                        maxlength=""
                        autocomplete=""
                        placeholder="">
                <label for="company-resident" class="b-form-block__label">Резидент</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>

            <div class="b-form-block">
                <input id="company-inn"
                        class="b-input-text"
                        type="text"
                        name="profile[UF_INN]"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder="">
                <label for="company-inn" class="b-form-block__label">ИНН</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>

            <div class="b-form-block">
                <input id="company-legal-address"
                        class="b-input-text"
                        type="text"
                        name="profile[UF_ADD_LEGAL]"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder="">
                <label for="company-legal-address" class="b-form-block__label">Юр.адрес</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>

            <div class="b-form-block">
                <input id="company-postal-address"
                        class="b-input-text"
                        type="text"
                        name="profile[UF_ADDR_POST]"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder="">
                <label for="company-postal-address" class="b-form-block__label">Почтовый адрес</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>

            <div class="b-form-block">
                <input id="company-phone"
                        class="b-input-phone"
                        type="tel"
                        name="profile[UF_PHONE]"
                        autocomplete="tel"
                        placeholder="+7 (___) ___-__-__">
                <label for="company-phone" class="b-form-block__label">Телефон</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>

            <div class="b-form-block">
                <input id="company-fax"
                        class="b-input-phone"
                        type="tel"
                        name="profile[UF_FAX]"
                        autocomplete="tel"
                        placeholder="+7 (___) ___-__-__">
                <label for="company-fax" class="b-form-block__label">Факс</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block">
                <input id="company-email"
                        class="b-input-email"
                        type="email"
                        name="profile[UF_EMAIL]">
                <label for="company-email" class="b-form-block__label">Электронная почта</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block b-form-block_theme_grey">
                <input id="company-ceo"
                        class="b-input-text"
                        type="text"
                        name="profile[UF_OWNER_FIO]"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder="">
                <label for="company-director" class="b-form-block__label">ФИО генерального директора</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
        </div>
    </div>

    <div class="j-profile-info-contacts"></div>

    <div class="b-profile-add">
        <button class="button-add j-add-contact" type="button">Добавить еще</button>
    </div>
</div>
