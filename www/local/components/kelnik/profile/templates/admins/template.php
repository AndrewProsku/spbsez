<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="j-profile-administrators">
    <?php foreach ($arResult['USERS'] as $row): ?>
        <div class="b-profile-block j-profile-block" data-id="<?= $row['ID']; ?>">
            <button type="button" class="b-profile-block__delete j-delete-administrator" data-id="<?= $row['ID']; ?>"></button>
            <div class="b-profile-block__title b-profile-admin-title">
                <h4><?= $row['FULL_NAME']; ?></h4>
            </div>
            <div class="b-profile-block__input-wrap">
                <div class="b-form-block">
                    <input id="admin-fio-<?= $row['ID']; ?>"
                            class="b-input-text"
                            type="text"
                            name="admin[<?= $row['ID']; ?>][FULL_NAME]"
                            maxlength=""
                            autocomplete=""
                            value="<?= $row['FULL_NAME']; ?>"
                            placeholder="">
                    <label for="admin-fio-<?= $row['ID']; ?>" class="b-form-block__label">ФИО</label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block">
                    <input id="admin-email-<?= $row['ID']; ?>"
                            class="b-input-email"
                            type="email"
                            name="admin[<?= $row['ID']; ?>][EMAIL]"
                            value="<?= $row['EMAIL']; ?>">
                    <label for="admin-email-<?= $row['ID']; ?>" class="b-form-block__label">Эл.почта </label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block">
                    <input id="admin-phone-<?= $row['ID']; ?>"
                            class="b-input-phone"
                            type="tel"
                            name="admin[<?= $row['ID']; ?>][PHONE]"
                            autocomplete="tel"
                            placeholder="+7 ___ ___-__-__"
                            value="<?= $row['PERSONAL_PHONE']; ?>">
                    <label for="admin-phone-<?= $row['ID']; ?>" class="b-form-block__label">Телефон</label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block">
                    <div class="b-select">
                        <div class="b-select__wrapper">
                            <select id="admin-status-<?= $row['ID']; ?>"
                                    class="b-select__list j-select"
                                    name="admin[<?= $row['ID']; ?>][STATUS]"
                                    data-placeholder="">

                                <option value="status1"
                                >
                                    Администратор
                                </option>
                                <option value="status2"
                                >
                                    Супер-Администратор
                                </option>
                                <option value="status3"
                                >
                                    Просто Администратор
                                </option>
                                <option value="status4"
                                        selected>
                                    Не просто Администратор
                                </option>
                            </select>
                        </div>
                    </div>

                    <label for="admin1-status" class="b-form-block__label">Статус</label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>

                <div class="b-form-block b-form-block_type_accordion j-select-accordion">
                    <div class="b-select-accordion">
                        <input id="5"
                                class="b-input-text"
                                type="text"
                                name=""
                                maxlength=""
                                autocomplete=""
                                value=""
                                placeholder=""
                                disabled>

                        <div class="b-select-accordion__list">
                            <div class="b-select-accordion__list-title">
                                <p>Доступ к разделам сайта</p>
                            </div>
                            <div class="b-select-accordion__list-item">
                                <div class="b-form__checkbox">
                                    <input
                                            id="admin5access1"
                                            class="b-checkbox-input"
                                            type="checkbox"
                                            value=""
                                            name="access1"
                                            checked>
                                    <label for="admin5access1" class="b-checkbox-label">
                                        <span class="b-checkbox-box"><svg width="16" height="11" xmlns="http://www.w3.org/2000/svg"
                                                  viewBox="0 0 16 11">
                                            <defs>
                                                <path d="M281,4467.5l5,5l8.5,-8.5" id="Path-0"/>
                                            </defs>
                                                <g transform="matrix(1,0,0,1,-280,-4463)">
                                                    <g>
                                                        <use xlink:href="#Path-0" class="b-checkbox-mark" fill-opacity="0" fill="#ffffff" stroke-linejoin="round"
                                                             stroke-linecap="butt"
                                                             stroke-opacity="1" stroke="#30409a" stroke-miterlimit="50" stroke-width="3"/>
                                                    </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <p class="b-checkbox-text">Подача отчета</p>
                                    </label>
                                </div>
                            </div>
                            <div class="b-select-accordion__list-item">
                                <div class="b-form__checkbox">
                                    <input
                                            id="admin5access2"
                                            class="b-checkbox-input"
                                            type="checkbox"
                                            value=""
                                            name="access2"
                                    >
                                    <label for="admin5access2" class="b-checkbox-label">
                                        <span class="b-checkbox-box"><svg width="16" height="11" xmlns="http://www.w3.org/2000/svg"
                                                  viewBox="0 0 16 11">
                                            <defs>
                                                <path d="M281,4467.5l5,5l8.5,-8.5" id="Path-0"/>
                                            </defs>
                                                <g transform="matrix(1,0,0,1,-280,-4463)">
                                                    <g>
                                                        <use xlink:href="#Path-0" class="b-checkbox-mark" fill-opacity="0" fill="#ffffff" stroke-linejoin="round"
                                                             stroke-linecap="butt"
                                                             stroke-opacity="1" stroke="#30409a" stroke-miterlimit="50" stroke-width="3"/>
                                                    </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <p class="b-checkbox-text">Сообщения от ОЭЗ</p>
                                    </label>
                                </div>
                            </div>
                            <div class="b-select-accordion__list-item">
                                <div class="b-form__checkbox">
                                    <input
                                            id="admin5access3"
                                            class="b-checkbox-input"
                                            type="checkbox"
                                            value=""
                                            name="access3"
                                            checked>
                                    <label for="admin5access3" class="b-checkbox-label">
                                        <span class="b-checkbox-box"><svg width="16" height="11" xmlns="http://www.w3.org/2000/svg"
                                                  viewBox="0 0 16 11">
                                                <defs>
                                                    <path d="M281,4467.5l5,5l8.5,-8.5" id="Path-0"/>
                                                </defs>
                                                <g transform="matrix(1,0,0,1,-280,-4463)">
                                                    <g>
                                                        <use xlink:href="#Path-0" class="b-checkbox-mark" fill-opacity="0" fill="#ffffff" stroke-linejoin="round"
                                                             stroke-linecap="butt"
                                                             stroke-opacity="1" stroke="#30409a" stroke-miterlimit="50" stroke-width="3"/>
                                                    </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <p class="b-checkbox-text">Подача заявки</p>
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="button b-select-accordion__button">OK</button>
                        </div>
                    </div>

                    <label for="admin1-access" class="b-form-block__label">Доступ</label>
                    <span class="b-form-block__error-text">Текст подсказки</span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="b-profile-add">
    <button class="button-add j-add-administrator" type="button">Добавить еще администратора</button>
</div>
