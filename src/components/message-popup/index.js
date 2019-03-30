import InputTel from '../forms/telephone/telephone';
import Language from '../language';
import successTemplate from './message-success.twig';
import Utils from '../../common/scripts/utils';

const Lang = new Language();

class Message {
    constructor() {
        this.form = 'j-message-form';
        this.submit = 'j-message-submit';

        this.login = 'j-auth-login';
        this.password = 'j-auth-password';

        this.title = 'j-message-title-select';
        this.fio = 'j-message-fio';
        this.email = 'j-message-email';
        this.phone = 'j-message-phone';
        this.text = 'j-message-text';

        this.errorInputClass = 'b-form-block-error';
        this.messageInputClass = 'b-form-block__error-text';
        this.formBlockClass = 'b-form-block';

        this.isFieldCorrect = {
            title: true,
            fio  : false,
            email: false,
            phone: false,
            text : false
        };

        this.emptyErrorMessage = Lang.get('validation.required');
        this.incorrectEmailMessage = Lang.get('validation.email');
        this.incorrectPhoneMessage = Lang.get('validation.phone');
    }

    init(options) {
        this.popup = options.popup;

        this._initInputs();
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.$form = document.querySelector(`.${this.form}`);
        this.$fio = this.$form.querySelector(`.${this.fio}`);
        this.$inputFIO = this.$fio.querySelector('input');
        this.$email = this.$form.querySelector(`.${this.email}`);
        this.$inputEmail = this.$email.querySelector('input');
        this.$phone = this.$form.querySelector(`.${this.phone}`);
        this.$inputPhone = this.$phone.querySelector('input');
        this.$textarea = this.$form.querySelector(`.${this.text}`);
        this.$inputTextarea = this.$form.querySelector(`textarea`);
    }

    _initInputs() {
        // Инициализируем поле ввода телефона
        const vacancyPhone = Array.from(this.popup.popup.querySelectorAll('input[type="tel"]'));

        if (vacancyPhone.length) {
            const inputTel = new InputTel();

            inputTel.init({input: vacancyPhone});
        }
    }

    /* eslint-disable max-lines-per-function */

    _bindEvents() {
        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();
            const that = this;
            const isFormFulfilled = this.checkForm();

            /* eslint-disable consistent-return */
            if (!isFormFulfilled) {
                return;
            }

            const formData = new FormData(that.$form);

            formData.set('lang', document.documentElement.lang);

            Utils.send(formData, '/api/message/', {
                success(response) {
                    const successStatus = 1;

                    if (response.request.status === successStatus) {
                        that.showSuccessMessage(response.data);

                        return true;
                    }

                    const errorMessage = response.request.errors.join('</br>');

                    that.showErrorMessage(that.$inputResume, errorMessage);
                    that.errorRepeatPassword(errorMessage);

                    return false;
                },
                error(error) {
                    console.error(error);
                }
            });
            /* eslint-enable consistent-return */
        });

        this.$inputFIO.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'fio');
        });

        this.$inputEmail.addEventListener('change', (event) => {
            const isValidEmail = event.target.checkValidity();
            const emailStr = '^[-._a-zA-Za-яA-я0-9]{2,}@(?:[a-zA-Za-яА-Я0-9][-a-z-A-Z-a-я-А-Я0-9]+\\.)+[a-za-я]{2,6}$';
            const regEmail = new RegExp(emailStr, 'u');

            if (isValidEmail && regEmail.test(this.$inputEmail.value)) {
                this.inputChangeHandler(event, 'email');
            } else if (isValidEmail === false || regEmail.test(this.$inputEmail.value) === false) {
                this.showErrorMessage(event.target, this.incorrectEmailMessage);
            }
        });

        this.$inputPhone.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'phone');
            const regPhone = new RegExp('\\+7\\s\\d{3}\\s\\d{3}-\\d{2}-\\d{2}', 'u');

            if (!regPhone.test(this.$inputPhone.value)) {
                this.showErrorMessage(event.target, this.incorrectPhoneMessage);
            }
        });

        this.$textarea.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'text');
        });
    }

    /* eslint-enable max-lines-per-function */

    inputChangeHandler(event, inputName) {
        if (event.target.value.length) {
            this.isFieldCorrect[inputName] = true;
            this.removeErrorMessage(event.target);
        } else {
            this.isFieldCorrect[inputName] = false;
            this.showErrorMessage(event.target, this.emptyErrorMessage);
        }
    }

    checkForm() {
        let result = true;

        if (!this.isFieldCorrect.fio) {
            this.showErrorMessage(this.$inputFIO, this.emptyErrorMessage);
        }
        if (!this.$inputEmail.value.length) {
            this.showErrorMessage(this.$inputEmail, this.emptyErrorMessage);
        }
        if (!this.$inputPhone.value.length) {
            this.showErrorMessage(this.$inputPhone, this.emptyErrorMessage);
        }
        if (!this.isFieldCorrect.text) {
            this.showErrorMessage(this.$inputTextarea, this.emptyErrorMessage);
        }

        for (const field in this.isFieldCorrect) {
            if ({}.hasOwnProperty.call(this.isFieldCorrect, field) &&
                this.isFieldCorrect[field] === false) {
                result = false;
                break;
            }
        }

        return result;
    }

    showErrorMessage(element, message) {
        const parentFormBlock = element.closest(`.${this.formBlockClass}`);
        const messageEl = parentFormBlock.querySelector(`.${this.messageInputClass}`);

        Utils.clearHtml(messageEl);
        Utils.insetContent(messageEl, message);
        parentFormBlock.classList.add(this.errorInputClass);
    }

    removeErrorMessage(element) {
        element.closest(`.${this.formBlockClass}`).classList.remove(this.errorInputClass);
    }

    showSuccessMessage(data) {
        const $popupContent = document.querySelector('.b-popup__content');

        Utils.clearHtml($popupContent);
        Utils.insetContent($popupContent, successTemplate(data));

        $popupContent.querySelector('.j-message-popup__close').addEventListener('click', () => {
            this.popup.close();
        });
    }
}

export default Message;
