import $ from 'jquery';
import Language from '../language';
import Utils from '../../common/scripts/utils';

const Lang = new Language();

class Authorization {
    constructor() {
        this.form = 'j-form-authorization';
        this.submit = 'j-auth-submit';
        this.login = 'j-auth-login';
        this.password = 'j-auth-password';
        this.approve = 'j-person-data';
        this.formBlockClass = 'b-form-block';
        this.errorInputClass = 'b-form-block-error';
        this.messageInputs = 'b-form-block__error-text';
        this.isLogin = false;

        this.emptyErrorMessage = Lang.get('validation.required');
        this.incorrectEmailMessage = Lang.get('validation.email');
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.$form = document.querySelector(`.${this.form}`);
        this.$login = this.$form.querySelector(`.${this.login}`);
        this.$password = this.$form.querySelector(`.${this.password}`);
        this.$approve = this.$form.querySelector(`.${this.approve}`);
        this.$messagePassword = this.$password.querySelector(`.${this.messageInputs}`);
        this.$inputLogin = this.$login.querySelector('input');
        this.$inputPassword = this.$password.querySelector('input');
        this.$inputApprove = this.$approve.querySelector('input');

        this.defaultErrorMessage = this.$messagePassword.innerText;
    }

    /**
     * Биндит события
     * @private
     */
    _bindEvents() {
        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();
            const isFormFulfilled = this.checkForm();

            if (isFormFulfilled) {
                this.toLogin(`${$(event.target).serialize()}&lang=${document.documentElement.lang}`);
            }
        });

        this.$inputLogin.addEventListener('change', (event) => {
            const isValidEmail = (/^[^\s@]+@[^\s@]+\.[^\s@]+$/u).test(event.target.value);

            if (isValidEmail) {
                this.loginChangeHandler(event.target);
            } else {
                this.isLogin = false;
                this.showErrorMessage(event.target, this.incorrectEmailMessage);
            }
        });

        this.$inputPassword.addEventListener('change', () => {
            if (this.$inputPassword.value.length) {
                this.removeErrorPassword();
            } else {
                this.errorPassword();
            }
        });

        this.$inputApprove.addEventListener('change', () => {
            if (this.$inputApprove.checked) {
                this.$approve.classList.remove('is-error');
            }
        });
    }

    loginChangeHandler(target) {
        if (target.value.length) {
            this.isLogin = true;
            this.removeErrorLogin();
        } else {
            this.isLogin = false;
            this.showErrorMessage(target, this.emptyErrorMessage);
        }
    }

    checkForm() {
        if (!this.isLogin) {
            if (!this.$inputLogin.value.length) {
                this.showErrorMessage(this.$inputLogin, this.emptyErrorMessage);
            } else if (!this.$inputLogin.checkValidity()) {
                this.showErrorMessage(this.$inputLogin, this.incorrectEmailMessage);
            }

            return false;
        } else if (!this.$inputPassword.value.length) {
            this.errorPassword();

            return false;
        } else if (!this.$inputApprove.checked) {
            this.errorApprove();

            return false;
        }

        this.removeErrorPassword();
        this.$approve.classList.remove('is-error');

        return true;
    }

    toLogin(dataToSend) {
        const that = this;

        Utils.send(dataToSend, '/api/login', {
            success(response) {
                const successStatus = 1;
                const failStatus = 0;

                if (response.request.status === successStatus) {
                    window.location.href = response.data.backUrl || '/';
                } else if (response.request.status === failStatus) {
                    const errorMessage = response.request.errors.join('</br>');

                    that.errorPassword(errorMessage);
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    removeErrorLogin() {
        this.$login.classList.remove(this.errorInputClass);
    }

    errorApprove() {
        this.$approve.classList.add('is-error');
    }

    showErrorMessage(element, message) {
        const parentFormBlock = element.closest(`.${this.formBlockClass}`);
        const messageEl = parentFormBlock.querySelector(`.${this.messageInputs}`);

        Utils.clearHtml(messageEl);
        Utils.insetContent(messageEl, message);
        parentFormBlock.classList.add(this.errorInputClass);
    }

    removeErrorPassword() {
        this.$password.classList.remove(this.errorInputClass);
    }

    errorPassword(message) {
        Utils.clearHtml(this.$messagePassword);
        if (message) {
            Utils.insetContent(this.$messagePassword, message);
        } else {
            Utils.insetContent(this.$messagePassword, this.defaultErrorMessage);
        }
        this.$password.classList.add(this.errorInputClass);
    }
}

export default Authorization;
