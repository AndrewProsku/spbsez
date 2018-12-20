import $ from 'jquery';
import Utils from '../../common/scripts/utils';

class Authorization {
    constructor() {
        this.form = 'j-form-authorization';
        this.submit = 'j-auth-submit';
        this.login = 'j-auth-login';
        this.password = 'j-auth-password';
        this.errorInputClass = 'b-form-block-error';
        this.messageInputs = 'b-form-block__error-text';
        this.isLogin = false;
        this.isPassword = false;
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.$form = document.querySelector(`.${this.form}`);
        this.$login = this.$form.querySelector(`.${this.login}`);
        this.$password = this.$form.querySelector(`.${this.password}`);
        this.$messagePassword = this.$password.querySelector(`.${this.messageInputs}`);
        this.$inputLogin = this.$login.querySelector('input');
        this.$inputPassword = this.$password.querySelector('input');

        this.defaultErrorMessage = this.$messagePassword.innerText;
    }

    _bindEvents() {
        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();
            const isFormFulfilled = this.checkForm();

            if (isFormFulfilled) {
                const dataToSend = $(event.target).serialize();

                this.toLogin(dataToSend);
            }
        });

        this.$inputLogin.addEventListener('change', () => {
            if (this.$inputLogin.value.length) {
                this.isLogin = true;
                this.removeErrorLogin();
            } else {
                this.isLogin = false;
                this.errorLogin();
            }
        });

        this.$inputPassword.addEventListener('change', () => {
            if (this.$inputPassword.value.length) {
                this.isPassword = true;
                this.removeErrorPassword();
            } else {
                this.isPassword = false;
                this.errorPassword();
            }
        });
    }

    checkForm() {
        if (!this.isLogin) {
            this.errorLogin();

            return false;
        } else if (!this.isPassword) {
            this.errorPassword();

            return false;
        }

        return true;
    }

    toLogin(dataToSend) {
        const that = this;

        Utils.send(dataToSend, '/api/login', {
            success(response) {
                const successStatus = 1;
                const failStatus = 0;

                if (response.request.status === successStatus) {
                    if (response.data && response.data.backUrl) {
                        window.location.href = response.data.backUrl;
                    } else {
                        window.location.href = '/';
                    }
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

    errorLogin() {
        this.$login.classList.add(this.errorInputClass);
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
