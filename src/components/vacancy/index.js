import InputFile from 'components/forms/file';
import InputTel from 'components/forms/telephone/telephone';
import Select from 'components/forms/select';
import successTemplate from './success.twig';
import Utils from '../../common/scripts/utils';

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

        this.$title = this.$form.querySelector(`.${this.title}`);
        this.$inputTitle = this.$title.querySelector('select');
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

            if (isFormFulfilled) {
                Utils.send(new FormData(that.$form), '/api/vacancy/', {
                    success(response) {
                        const successStatus = 1;
                        const failStatus = 0;

                        if (response.request.status === successStatus) {
                            that.showSuccessMessage();
                        } else if (response.request.status === failStatus) {
                            const errorMessage = response.request.errors.join('</br>');

                            // Нужно подумать как разделть ошибки по типу полей к которым они относятся
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
        this.$inputResume.addEventListener('change', (event) => {
            this.inputChangeHandler(event, 'resume');
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

    showSuccessMessage() {
        const $popupContent = document.querySelector('.b-popup__content');

        Utils.clearHtml($popupContent);
        Utils.insetContent($popupContent, successTemplate());

        $popupContent.querySelector('.j-vacancy-popup__close').addEventListener('click', () => {
            this.popup.close();
        });
    }
}

export default Vacancy;
