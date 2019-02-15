<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<div id="#service" class="b-service-popup">
    <h3 class="b-service-popup__header">Оставить заявку</h3>
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
                <label for="service-title" class="b-form-block__label">
                    Цель обращения
                </label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block j-service-text b-input-textarea">
                <textarea id="text"
                          class="b-input-text"
                          name="text"
                          rows="3"
                          cols=""
                          placeholder=""></textarea>

                <label for="admin-fio" class="b-form-block__label">Комментарий</label>
                <span class="b-form-block__error-text">Комментарий</span>
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
                <label for="admin-fio" class="b-form-block__label">Компания</label>
                <span class="b-form-block__error-text">Компания</span>
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
                <label for="admin-fio" class="b-form-block__label">ФИО</label>
                <span class="b-form-block__error-text">Имя</span>
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
                <label for="email" class="b-form-block__label">Должность</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block j-service-email">
                <input id="service-email"
                       class="b-input-email"
                       type="email"
                       name="email">
                <label for="email" class="b-form-block__label">Эл. почта</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block j-service-phone">
                <input id="service-phone"
                       class="b-input-phone"
                       type="tel"
                       name="phone"
                       autocomplete="tel"
                       placeholder="+7 ___ ___-__-__">
                <label for="admin-phone" class="b-form-block__label">Телефон</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
        </div>

        <button class="button b-service__button j-service-submit">Оставить заявку</button>
    </form>
</div>
