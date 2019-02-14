<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div id="#message" class="b-message-popup">
    <h3 class="b-message-popup__header">Написать сообщение</h3>
    <form method="post" enctype="application/x-www-form-urlencoded" class="b-vacancy-response-form j-message-form">
        <div class="b-vacancy-response-form__inputs">
            <div class="b-form-block j-message-text b-input-textarea">
                    <textarea id="text"
                        class="b-input-text"
                        name="text"
                        rows="3"
                        cols=""
                        placeholder=""></textarea>
                <label for="admin-fio" class="b-form-block__label">Комментарий</label>
                <span class="b-form-block__error-text">Комментарий</span>
            </div>
            <div class="b-form-block j-message-fio">
                <input id="message-fio"
                    class="b-input-text"
                    type="text"
                    name="fio"
                    maxlength=""
                    autocomplete=""
                    value=""
                    placeholder="">
                <label for="message-fio" class="b-form-block__label">ФИО</label>
                <span class="b-form-block__error-text">Имя</span>
            </div>
            <div class="b-form-block j-message-email">
                <input id="message-email"
                    class="b-input-email"
                    type="email"
                    name="email">
                <label for="message-email" class="b-form-block__label">
                    Эл. почта
                </label>
                <span class="b-form-block__error-text">
                            Текст подсказки
                        </span>
            </div>
            <div class="b-form-block j-message-phone">
                <input id="message-phone"
                    class="b-input-phone"
                    type="tel"
                    name="phone"
                    autocomplete="tel"
                    placeholder="+7 ___ ___-__-__">
                <label for="message-phone" class="b-form-block__label">Телефон</label>
                <span class="b-form-block__error-text">Текст подсказки</span>
            </div>
        </div>
        <button class="button b-vacancy__button j-message-submit">Написать сообщение</button>
    </form>
</div>
