import InputTel from '../forms/telephone/telephone';
import Select from 'components/forms/select';
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
        this.company = 'j-service-company';
        this.position = 'j-service-position';

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
        this.incorrectPhoneMessage = 'Номер телефона введен не полностью';
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
        this.$inputTextarea = this.$textarea.querySelector(`textarea`);
        this.$company = this.$form.querySelector(`.${this.company}`);
        this.$inputCompany = this.$company.querySelector(`input`);
        this.$position = this.$form.querySelector(`.${this.position}`);
        this.$inputPosition = this.$position.querySelector(`input`);
    }

    _initInputs() {
        // Инициализируем поле ввода телефона
        const vacancyPhone = Array.from(this.popup.popup.querySelectorAll('input[type="tel"]'));

        if (vacancyPhone.length) {
            const inputTel = new InputTel();

            inputTel.init({input: vacancyPhone});
        }

        const vacancyID = this.popup.target.dataset.id;

        this.popup.popup.querySelector(`select`).value = vacancyID;

        const select = new Select({
            element: '.b-popup_theme_service .j-select',

            disableSearch: true
        });

        select.init();
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
                            // const errorMessage = response.request.errors.join('</br>');

                            // that.showErrorMessage(that.$inputResume, errorMessage);
                            // that.errorRepeatPassword(errorMessage);
                        }
                    },
                    error() {
                        // console.error(error);
                    }
                });
            }
        });

        this._checkName();
        this._checkEmail();
        this._checkPhone();
        this._checkTextarea();
        this._checkCompany();
        this._checkPosition();
    }

    _checkName() {
        this.$inputFIO.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'fio');
        });
    }

    _checkEmail() {
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
    }

    _checkPhone() {
        this.$inputPhone.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'phone');
            const regPhone = new RegExp('\\+7\\s\\d{3}\\s\\d{3}-\\d{2}-\\d{2}', 'u');

            if (!regPhone.test(this.$inputPhone.value)) {
                this.showErrorMessage(event.target, this.incorrectPhoneMessage);
            }
        });
    }

    _checkTextarea() {
        this.$textarea.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'text');
        });
    }

    _checkCompany() {
        this.$inputCompany.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'company');
        });
    }

    _checkPosition() {
        this.$inputPosition.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'position');
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
        if (!this.$inputEmail.value.length) {
            this.showErrorMessage(this.$inputEmail, this.emptyErrorMessage);
        }
        if (!this.isFieldCorrect.phone) {
            this.showErrorMessage(this.$inputPhone, this.emptyErrorMessage);
        }
        if (!this.isFieldCorrect.text) {
            this.showErrorMessage(this.$inputTextarea, this.emptyErrorMessage);
        }
        if (!this.isFieldCorrect.company) {
            this.showErrorMessage(this.$inputCompany, this.emptyErrorMessage);
        }
        if (!this.isFieldCorrect.position) {
            this.showErrorMessage(this.$inputPosition, this.emptyErrorMessage);
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
