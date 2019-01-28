import InputTel from '../forms/telephone/telephone';
import successTemplate from './success.twig';
import Utils from '../../common/scripts/utils';

class Service {
    constructor() {
        this.form = 'j-service-form';
        this.submit = 'j-service-submit';

        this.login = 'j-auth-login';
        this.password = 'j-auth-password';

        this.title = 'j-service-title-select';
        this.fio = 'j-service-fio';
        this.email = 'j-service-email';
        this.phone = 'j-service-phone';
        this.text = 'j-service-text';

        this.errorInputClass = 'b-form-block-error';
        this.serviceInputClass = 'b-form-block__error-text';
        this.formBlockClass = 'b-form-block';

        this.isFieldCorrect = {
            title: true,
            fio  : false,
            email: false,
            phone: false,
            text : false
        };

        this.emptyErrorMessage = 'Поле не может быть пустым';
        this.incorrectEmailMessage = 'Некорректный email адрес';
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

    _bindEvents() {
        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();
            const that = this;
            const isFormFulfilled = this.checkForm();

            if (isFormFulfilled) {
                Utils.send(new FormData(that.$form), '/api/service/', {
                    success(response) {
                        const successStatus = 1;
                        const failStatus = 0;

                        if (response.request.status === successStatus) {
                            that.showSuccessMessage();
                        } else if (response.request.status === failStatus) {
                            const errorMessage = response.request.errors.join('</br>');

                            that.showErrorMessage(that.$inputResume, errorMessage);
                            that.errorRepeatPassword(errorMessage);
                        }
                    },
                    error(error) {
                        console.error(error);
                    }
                });
            }
        });

        this.$inputFIO.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'fio');
        });
        this.$inputEmail.addEventListener('change', (event) => {
            const isValidEmail = event.target.checkValidity();

            if (isValidEmail) {
                this.inputChangeHandler(event, 'email');
            } else {
                this.isFieldCorrect.email = false;
                this.showErrorMessage(event.target, this.incorrectEmailMessage);
            }
        });
        this.$inputPhone.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'phone');
        });
        this.$textarea.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'text');
        });
    }

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
        if (!this.isFieldCorrect.email) {
            this.showErrorMessage(this.$inputEmail, this.emptyErrorMessage);
        }
        if (!this.isFieldCorrect.phone) {
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

    showErrorMessage(element, service) {
        const parentFormBlock = element.closest(`.${this.formBlockClass}`);
        const serviceEl = parentFormBlock.querySelector(`.${this.serviceInputClass}`);

        Utils.clearHtml(serviceEl);
        Utils.insetContent(serviceEl, service);
        parentFormBlock.classList.add(this.errorInputClass);
    }

    removeErrorMessage(element) {
        element.closest(`.${this.formBlockClass}`).classList.remove(this.errorInputClass);
    }

    showSuccessMessage() {
        const $popupContent = document.querySelector('.b-popup__content');

        Utils.clearHtml($popupContent);
        Utils.insetContent($popupContent, successTemplate());

        $popupContent.querySelector('.j-service-popup__close').addEventListener('click', () => {
            this.popup.close();
        });
    }
}

export default Service;
