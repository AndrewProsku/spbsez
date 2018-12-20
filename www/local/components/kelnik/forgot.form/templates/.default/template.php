<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<form method="post" enctype="multipart/form-data" class="password-recovery-form j-form-password-recovery">

    <div class="b-form-block-wrap password-recovery-form-block-wrap">
        <div class="b-form-block j-input-email">
            <input id="email"
                   class="b-input-email"
                   type="email"
                   name="email"
                   required>
            <label for="email" class="b-form-block__label">
                Эл. почта
            </label>
            <span class="b-form-block__error-text">
                Поле не может быть пустым
            </span>
        </div>
    </div>
    <div class="password-recovery-form-block">
        <button type="submit" class="button password-recovery-form__button">
            Восстановить пароль
        </button>
    </div>

</form>