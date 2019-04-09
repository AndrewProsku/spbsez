import InputFile from 'components/forms/file';
import InputTel from 'components/forms/telephone/telephone';
import Language from '../language';
import resultTemplate from './success.twig';
import Select from 'components/forms/select';
import Utils from '../../common/scripts/utils';

const Lang = new Language();

class Vacancy {
    constructor() {
        this.form = 'j-vacancy-form';
        this.submit = 'j-vacancy-submit';

        this.login = 'j-auth-login';
        this.password = 'j-auth-password';

        this.title = 'j-vacancy-title-select';
        this.fio = 'j-vacancy-fio';
        this.email = 'j-vacancy-email';
        this.phone = 'j-vacancy-phone';
        this.resume = 'j-vacancy-resume';

        this.errorInputClass = 'b-form-block-error';
        this.messageInputClass = 'b-form-block__error-text';
        this.formBlockClass = 'b-form-block';

        this.isFieldCorrect = {
            title : true,
            fio   : false,
            email : false,
            phone : false,
            resume: false
        };

        this.lang = document.querySelector('html').getAttribute('lang') || 'ru';
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

        // this.$title = this.$form.querySelector(`.${this.title}`);
        // this.$inputTitle = this.$title.querySelector('select');
        this.$fio = this.$form.querySelector(`.${this.fio}`);
        this.$inputFIO = this.$fio.querySelector('input');
        this.$email = this.$form.querySelector(`.${this.email}`);
        this.$inputEmail = this.$email.querySelector('input');
        this.$phone = this.$form.querySelector(`.${this.phone}`);
        this.$inputPhone = this.$phone.querySelector('input');
        this.$resume = this.$form.querySelector(`.${this.resume}`);
        this.$inputResume = this.$resume.querySelector('input');
    }

    _initInputs() {
        // Переинциализируем Select
        const initializedSelects = this.popup.popup.querySelectorAll('.chosen-container');

        if (initializedSelects.length) {
            initializedSelects.forEach((select) => {
                Utils.removeElement(select);
            });
        }

        // Выберем нужную вакансию для селекта вакансий
        const vacancyID = this.popup.target.dataset.id;

        this.popup.popup.querySelector(`.j-vacancy-title-select  select`).value = vacancyID;

        const select = new Select({
            element: '.b-popup_theme_vacancy .j-select',

            disableSearch: true
        });

        select.init();

        // Инициализируем поле ввода телефона
        const vacancyPhone = Array.from(this.popup.popup.querySelectorAll('input[type="tel"]'));

        if (vacancyPhone.length) {
            const inputTel = new InputTel();

            inputTel.init({input: vacancyPhone});
        }

        // Инициализируем поле для загруки файла резюме
        const resumeInput = new InputFile();

        resumeInput.init({
            target: this.popup.popup.querySelector('.b-input-file')
        });
    }

    _bindEvents() {
        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();
            const that = this;
            const isFormFulfilled = this.checkForm();

            if (!isFormFulfilled) {
                return;
            }

            const formData = new FormData(that.$form);

            formData.set('lang', document.documentElement.lang);

            Utils.send(formData, '/api/vacancy/', {
                success(response) {
                    const successStatus = 1;

                    if (response.request.status === successStatus) {
                        that.showResultMessage(response.data);

                        return true;
                    }

                    return false;
                },
                error(error) {
                    console.error(error);
                }
            });
        });

        this.$inputFIO.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'fio');
        });
        this.$inputEmail.addEventListener('change', (event) => {
            const isValidEmail = (/^[^\s@]+@[^\s@]+\.[^\s@]+$/u).test(event.target.value);

            if (isValidEmail) {
                this.inputChangeHandler(event, 'email');
            } else {
                this.isFieldCorrect.phone = false;
                this.showErrorMessage(event.target, this.incorrectEmailMessage);
            }
        });
        this.$inputPhone.addEventListener('change', (event) => {
            this._onPhoneChange(event);
        });
        this.$inputResume.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'resume');
        });
    }

    _onPhoneChange(event) {
        if (this._isPhoneTooShort(event.target.value)) {
            this.isFieldCorrect.phone = false;
            this.showErrorMessage(event.target, this.incorrectPhoneMessage);
        } else {
            this.isFieldCorrect.phone = true;
            this.inputChangeHandler(event, 'phone');
        }
    }

    _isPhoneTooShort(value) {
        const phoneDigits = value.replace(/[^0-9]/gu, '');

        return (phoneDigits.length > 0) && (phoneDigits.length < 11);
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
            const errorMessage = this._isPhoneTooShort(this.$inputPhone.value) ?
                this.incorrectPhoneMessage :
                this.emptyErrorMessage;

            this.showErrorMessage(this.$inputPhone, errorMessage);
        }
        if (!this.isFieldCorrect.resume) {
            this.showErrorMessage(this.$inputResume, this.emptyErrorMessage);
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

    showResultMessage(data) {
        const $popupContent = document.querySelector('.b-popup__content');

        Utils.clearHtml($popupContent);
        Utils.insetContent($popupContent, resultTemplate(data));

        $popupContent.querySelector('.j-vacancy-popup__close').addEventListener('click', () => {
            this.popup.close();
        });
    }
}

export default Vacancy;
