<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?php if($arResult['CHANGE_PASSWORD']): ?>
    <form method="post" enctype="multipart/form-data" class="password-recovery-form j-form-new-password">
        <input type="hidden" name="USER_CHECKWORD" value="<?= $arResult['PARAMS']['USER_CHECKWORD']; ?>">
        <input type="hidden" name="USER_LOGIN" value="<?= $arResult['PARAMS']['USER_LOGIN']; ?>">
        <div class="b-form-block-wrap password-recovery-form-block-wrap">
            <div class="b-form-block-wrap">
                <label class="b-form-block j-input-new-password">
                    <input id="password"
                            class="b-input-password"
                            type="password"
                            name="password"
                           required>
                    <span class="b-form-block__label">Новый пароль</span>
                    <span class="b-form-block__error-text">Поле не может быть пустым</span>
                </label>
                <label class="b-form-block j-input-repeat-password">
                    <input id="new-password"
                            class="b-input-password"
                            type="password"
                            name="new-password"
                           required>
                    <span class="b-form-block__label">Повторите пароль</span>
                    <span class="b-form-block__error-text">Поле не может быть пустым</span>
                </label>
            </div>
        </div>
        <div class="password-recovery-form-block">
            <button type="submit" class="button password-recovery-form__button">
                Сохранить
            </button>
        </div>
    </form>
<?php else: ?>
    <div class="password-recovery-desc">
        Укажите электронную почту, на которую зарегистрирован ваш аккаунт — мы вышлем ссылку на восстановление
        пароля.
    </div>
    <form method="post" enctype="multipart/form-data" class="password-recovery-form j-form-password-recovery">
        <div class="b-form-block-wrap password-recovery-form-block-wrap">
            <label class="b-form-block j-input-email">
                <input id="email"
                       class="b-input-email"
                       type="email"
                       name="email"
                       required>
                <span class="b-form-block__label">Эл. почта</span>
                <span class="b-form-block__error-text">Поле не может быть пустым</span>
            </label>
        </div>
        <div class="password-recovery-form-block">
            <button type="submit" class="button password-recovery-form__button">
                Восстановить пароль
            </button>
        </div>
    </form>
<?php endif; ?>
