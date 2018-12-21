<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<form action="" enctype="multipart/form-data" class="b-login-form j-form-authorization">
    <div class="b-form-block-wrap">
        <div class="b-form-block j-auth-login">
            <input id="email"
                    class="b-input-email"
                    type="email"
                    name="email">

            <label for="email" class="b-form-block__label">
                Эл. почта
            </label>
            <span class="b-form-block__error-text">
                    Это поле обязательно для заполнения
                </span>
        </div>
        <div class="b-form-block j-auth-password">
            <input id="password"
                    class="b-input-password"
                    type="password"
                    name="password">

            <label for="password" class="b-form-block__label">
                Пароль
            </label>
            <span class="b-form-block__error-text">
                    Это поле обязательно для заполнения
                </span>
        </div>
    </div>
    <div class="b-remember-passsword">
        <a href="/cabinet/forgot/" class="b-link-line">
            Я не помню пароль
        </a>
    </div>
    <div class="b-personal-information j-person-data">
        <div class="b-form__checkbox">
            <input id="personal-information"
                    class="b-checkbox-input"
                    type="checkbox"
                    value=""
                    name="personal-information"
                    required>
            <label for="personal-information" class="b-checkbox-label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
                    <path class="b-checkbox-box"
                          d="M8.25,5.62h19a3,3,0,0,1,3,3V27.625a3,3,0,0,1-3,3h-19a3,3,0,0,1-3-3V8.62A3,3,0,0,1,8.25,5.62Z"/>
                    <path class="b-checkbox-mark"
                          d="M9,17.568l1.809-2.229,5.064,4.829,7.958-8.543L26,13.854,15.872,24.625Z"/>
                </svg>
                <p class="b-checkbox-text">Даю согласие на обработку <a href='#' class='b-link-line'>персональных
                        данных</a></p>
            </label>
        </div>
    </div>

    <div class="b-login-form__button-block">
        <button type="submit" class="button b-login-form__button j-auth-submit">
            Войдите в личный кабинет
        </button>
    </div>
</form>
