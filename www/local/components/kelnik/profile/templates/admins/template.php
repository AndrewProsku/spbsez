<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="j-profile-administrators">
    <?php if(empty($arResult['USERS'])): ?>
        <div class="b-empty-page j-empty-page is-active"><p>Администраторов пока нет</p></div>
    <?php else: ?>
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
                                name="FULL_NAME"
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
                                name="EMAIL"
                                value="<?= $row['EMAIL']; ?>">
                        <label for="admin-email-<?= $row['ID']; ?>" class="b-form-block__label">Эл.почта </label>
                        <span class="b-form-block__error-text">Текст подсказки</span>
                    </div>

                    <div class="b-form-block">
                        <input id="admin-phone-<?= $row['ID']; ?>"
                                class="b-input-phone"
                                type="tel"
                                name="PERSONAL_PHONE"
                                autocomplete="tel"
                                placeholder="+7 ___ ___-__-__"
                                value="<?= $row['PERSONAL_PHONE']; ?>">
                        <label for="admin-phone-<?= $row['ID']; ?>" class="b-form-block__label">Телефон</label>
                        <span class="b-form-block__error-text">Текст подсказки</span>
                    </div>

                    <div class="b-form-block">
                        <input id="admin-status-<?= $row['ID']; ?>"
                               class="b-input-text"
                               type="text"
                               name="STATUS"
                               value="<?= $row['STATUS_NAME']; ?>" readonly disabled>
                        <label for="admin-status-<?= $row['ID']; ?>" class="b-form-block__label">Статус</label>
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
                                        <?php $rowId = 'admin' . $row['ID'] . 'report'; ?>
                                        <input id="<?= $rowId; ?>"
                                                class="b-checkbox-input"
                                                type="checkbox"
                                                value="1"
                                                name="report"
                                                <?php if($row[\Kelnik\Userdata\Profile\ProfileModel::CAN_REPORT]): ?> checked<?php endif; ?>>
                                        <label for="<?= $rowId; ?>" class="b-checkbox-label">
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
                                            <span class="b-checkbox-text">Подача отчета</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="b-select-accordion__list-item">
                                    <div class="b-form__checkbox">
                                        <?php $rowId = 'admin' . $row['ID'] . 'msg'; ?>
                                        <input id="<?= $rowId; ?>"
                                                class="b-checkbox-input"
                                                type="checkbox"
                                               value="1"
                                               name="msg"
                                               <?php if($row[\Kelnik\Userdata\Profile\ProfileModel::CAN_MSG]): ?> checked<?php endif; ?>>
                                        <label for="<?= $rowId; ?>" class="b-checkbox-label">
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
                                            <span class="b-checkbox-text">Сообщения от ОЭЗ</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="b-select-accordion__list-item">
                                    <div class="b-form__checkbox">
                                        <?php $rowId = 'admin' . $row['ID'] . 'request'; ?>
                                        <input id="<?= $rowId; ?>"
                                                class="b-checkbox-input"
                                                type="checkbox"
                                                value="1"
                                                name="request"
                                                <?php if($row[\Kelnik\Userdata\Profile\ProfileModel::CAN_REQUEST]): ?> checked<?php endif; ?>>
                                        <label for="<?= $rowId; ?>" class="b-checkbox-label">
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
                                            <span class="b-checkbox-text">Подача заявки</span>
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
    <?php endif; ?>
</div>

<div class="b-profile-add">
    <button class="button-add j-add-administrator" type="button">Добавить еще администратора</button>
</div>
