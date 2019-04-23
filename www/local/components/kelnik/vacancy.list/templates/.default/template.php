<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php if (empty($arResult['ELEMENTS'])): ?>
    <div class="b-vacancies-empty" style="display: block">
        К сожалению, в настоящий момент открытых вакансий нет
    </div>
    <?php return; ?>
<?php endif; ?>

<div class="b-vacancies-list">
    <?php foreach ($arResult['ELEMENTS'] as $row): ?>
        <div class="b-vacancy j-vacancy">
            <h2 class="b-vacancy__header"><?= $row['NAME']; ?></h2>
            <div class="b-vacancy__content-wrapper">
                <div class="b-vacancy__content">
                    <div class="b-vacancy__salary">
                        <?php if($row['PRICE_MIN'] && $row['PRICE_MAX']): ?>
                            <?= $row['PRICE_MIN']; ?> - <?= $row['PRICE_MAX']; ?> ₽
                        <?php elseif($row['PRICE_MIN']): ?>
                            от <?= $row['PRICE_MIN']; ?> ₽
                        <?php elseif($row['PRICE_MAX']): ?>
                            до <?= $row['PRICE_MAX']; ?> ₽
                        <?php elseif($row['PRICE_TEXT']): ?>
                            <?= $row['PRICE_TEXT']; ?>
                        <?php endif; ?>
                    </div>
                    <div class="b-vacancy__characteristics">
                        <?php if($row['EXPERIENCE']): ?>
                            <div class="b-vacancy__characteristic">
                                <div class="b-vacancy__characteristic-name">Опыт работы</div>
                                <div class="b-vacancy__characteristic-value"><?= $row['EXPERIENCE']; ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if($row['EMPLOYMENT']): ?>
                            <div class="b-vacancy__characteristic">
                                <div class="b-vacancy__characteristic-name">Занятость</div>
                                <div class="b-vacancy__characteristic-value"><?= $row['EMPLOYMENT']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if(!empty($row['DESCR'])): ?>
                    <div class="b-vacancy-description">
                        <?= $row['DESCR']; ?>
                    </div>
                    <?php endif; ?>
                    <?php
                        $blocks = [
                            'DUTIES' => 'Обязанности',
                            'REQUIREMENTS' => 'Требования',
                            'CONDITIONS' => 'Условия'
                        ];
                    ?>
                    <?php foreach ($blocks as $blockKey => $blockName): ?>
                        <?php if (empty($row[$blockKey])): continue; endif; ?>
                        <h3 class="b-vacancy__subtitle"><?= $blockName; ?></h3>
                        <div class="b-vacancy__description"><?= $row[$blockKey]; ?></div>
                    <?php endforeach; ?>
                    <button class="button b-vacancy__button j-vacancy-button" type="button" data-href="#popup"
                            data-id="<?= $row['ID']; ?>">Откликнуться на вакансию
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div id="#popup" class="b-vacancies-popup">
    <h3 class="b-vacancies-popup__header">Отклик на вакансию</h3>
    <form action="" method="post" enctype="multipart/form-data" class="b-vacancy-response-form j-vacancy-form">
        <div class="b-vacancy-response-form__inputs">
            <div class="b-form-block j-vacancy-title-select">
                <div class="b-select">
                    <div class="b-select__wrapper">
                        <select id="vacancy-title"
                                class="b-select__list j-select"
                                name="type"
                                data-placeholder="">
                            <?php foreach ($arResult['ELEMENTS'] as $row): ?>
                                <option value="<?= $row['ID']; ?>"><?= $row['NAME']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <label for="vacancy-title" class="b-form-block__label">
                    Должность
                </label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block j-vacancy-fio">
                <input id="vacancy-fio"
                        class="b-input-text"
                        type="text"
                        name="fio"
                        maxlength=""
                        autocomplete=""
                        value=""
                        placeholder="">
                <label for="admin-fio" class="b-form-block__label">ФИО</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block j-vacancy-email">
                <input id="vacancy-email"
                        class="b-input-email"
                        type="email"
                        name="email">
                <label for="email" class="b-form-block__label">
                    Эл. почта
                </label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-form-block j-vacancy-phone">
                <input id="vacancy-phone"
                        class="b-input-phone"
                        type="tel"
                        name="phone"
                        autocomplete="tel"
                        placeholder="+7 ___ ___-__-__">
                <label for="admin-phone" class="b-form-block__label">Телефон</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
            <div class="b-vacancy-response-form__note">*doc, docx, pdf, xls, xlsx</div>
            <div class="b-form-block b-input-file j-vacancy-resume">
                <input type="file" class="b-input-file__input" accept=".doc,.docx,.pdf,.xls,.xlsx"
                       id="vacancy-resume"
                       name="resume">
                <div class="b-input-file__text b-pseudo-input"> </div>
                <label for="vacancy-resume" class="b-input-file__add">Выбрать файл<i></i></label>
                <button class="b-input-file__delete" type="button">Удалить<i></i></button>
                <div class="b-form-block__label">Прикрепите резюме</div>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
        </div>
        <button class="button b-vacancy__button j-vacancy-submit">Откликнуться на вакансию</button>
    </form>
</div>
