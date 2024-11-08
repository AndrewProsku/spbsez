import $ from 'jquery';
import Language from '../language';
import Utils from '../../common/scripts/utils';

const Lang = new Language();

class NewPassword {
    constructor() {
        this.formClass = 'j-form-new-password';
        this.newPasswordWrapperClass = 'j-input-new-password';
        this.repeatPasswordWrapperClass = 'j-input-repeat-password';
        this.errorInputClass = 'b-form-block-error';
        this.messageInputClass = 'b-form-block__error-text';
        this.successButtonClass = 'j-password-recovery-button';

        this.isNewPassword = false;
        this.isRepeatPassword = false;

        this.emptyErrorMessage = Lang.get('validation.required');
        this.unequalErrorMessage = Lang.get('validation.passwordConfirm');
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.$form = document.querySelector(`.${this.formClass}`);
        this.$newPasswordWrapper = document.querySelector(`.${this.newPasswordWrapperClass}`);
        this.$repeatPasswordWrapper = document.querySelector(`.${this.repeatPasswordWrapperClass}`);
        this.$inputNewPassword = this.$newPasswordWrapper.querySelector('input');
        this.$inputRepeatPassword = this.$repeatPasswordWrapper.querySelector('input');
        this.$messageNewPassword = this.$repeatPasswordWrapper.querySelector(`.${this.messageInputClass}`);
        this.$messageRepeatPassword = this.$repeatPasswordWrapper.querySelector(`.${this.messageInputClass}`);
        this.$successButton = document.querySelector(`.${this.successButtonClass}`);
    }

    _bindEvents() {
        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();
            const that = this;

            if (!this.checkForm()) {
                return;
            }

            if (!this.checkRepeatPassword()) {
                return;
            }

            Utils.send(`${$(event.target).serialize()}&lang=${document.documentElement.lang}`,
                '/api/changePassword/',
                {
                    success(response) {
                        if (response.request.status) {
                            that.$successButton.classList.remove('password-recovery-block_is_hidden');
                            that.showSuccessMessage();
                            Utils.removeElement(that.$form);

                            return;
                        }

                        that.errorRepeatPassword(response.request.errors.join('</br>'));
                    },
                    error(error) {
                        console.error(error);
                    }
                });
        });

        this.$inputNewPassword.addEventListener('change', (event) => {
            if (event.target.value.length) {
                this.isNewPassword = true;
                this.removeError(this.$newPasswordWrapper);
            } else {
                this.isNewPassword = false;
                this.errorNewPassword(this.emptyErrorMessage);
            }
        });

        this.$inputRepeatPassword.addEventListener('change', (event) => {
            if (event.target.value.length) {
                this.isRepeatPassword = true;
                this.removeError(this.$repeatPasswordWrapper);
            } else {
                this.isRepeatPassword = false;
                this.errorRepeatPassword(this.emptyErrorMessage);
            }
        });
    }

    checkForm() {
        if (!this.isNewPassword) {
            this.errorNewPassword(this.emptyErrorMessage);

            return false;
        } else if (!this.isRepeatPassword) {
            this.errorRepeatPassword(this.emptyErrorMessage);

            return false;
        }

        return true;
    }

    removeError(element) {
        element.classList.remove(this.errorInputClass);
    }

    errorNewPassword(message) {
        Utils.clearHtml(this.$messageNewPassword);
        Utils.insetContent(this.$messageNewPassword, message);
        this.$newPasswordWrapper.classList.add(this.errorInputClass);
    }

    errorRepeatPassword(message) {
        Utils.clearHtml(this.$messageRepeatPassword);
        Utils.insetContent(this.$messageRepeatPassword, message);
        this.$repeatPasswordWrapper.classList.add(this.errorInputClass);
    }

    checkRepeatPassword() {
        let result = false;

        if (this.$inputNewPassword.value === this.$inputRepeatPassword.value) {
            result = true;
            this.removeError(this.$repeatPasswordWrapper);
        } else {
            this.isRepeatPassword = false;
            this.errorRepeatPassword(this.unequalErrorMessage);
        }

        return result;
    }

    showSuccessMessage() {
        const $title = document.querySelector('.j-new-password-title h1');

        if (!$title) {
            return;
        }

        Utils.clearHtml($title);
        Utils.insetContent($title, Lang.get('lk.savePassword'));
    }
}

export default NewPassword;

