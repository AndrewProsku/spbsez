import $ from 'jquery';
import Utils from '../../common/scripts/utils';

class PasswordRecovery {
    constructor() {
        this.formClass = 'j-form-password-recovery';
        this.emailWrapperClass = 'j-input-email';
        this.errorInputClass = 'b-form-block-error';
        this.messageInputClass = 'b-form-block__error-text';
        this.descriptionClass = 'password-recovery-desc';
        this.successButtonClass = 'j-password-recovery-button';
        this.isEmail = false;
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.$form = document.querySelector(`.${this.formClass}`);
        this.$emailWrapper = this.$form.querySelector(`.${this.emailWrapperClass}`);
        this.$inputEmail = this.$emailWrapper.querySelector('input');
        this.$messageEmail = this.$emailWrapper.querySelector(`.${this.messageInputClass}`);
        this.$description = document.querySelector(`.${this.descriptionClass}`);
        this.$successButton = document.querySelector(`.${this.successButtonClass}`);
        this.emptyErrorMessage = this.$messageEmail.innerText;
    }

    _bindEvents() {
        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (!this.isEmail) {
                this.errorEmail();

                return;
            }

            const that = this;
            const sentEmail = new FormData(event.target).get('email');
            const dataToSend = $(event.target).serialize();

            Utils.send(dataToSend, '/api/forgot/', {
                success(response) {
                    const successStatus = 1;
                    const failStatus = 0;

                    if (response.request.status === successStatus) {
                        let email = '';

                        if (response.data && response.data.email) {
                            email = response.data.email;
                        } else {
                            email = sentEmail;
                        }

                        that.$successButton.classList.remove('password-recovery-block_is_hidden');
                        that.showSuccessMessage(email);
                        Utils.removeElement(that.$form);
                    } else if (response.request.status === failStatus) {
                        const errorMessage = response.request.errors.join('</br>');

                        that.errorEmail(errorMessage);
                    }
                }
            });
        });


        this.$inputEmail.addEventListener('change', () => {
            if (this.$inputEmail.value.length) {
                this.isEmail = true;
                this.removeErrorEmail();
            } else {
                this.isEmail = false;
                this.errorEmail();
            }
        });
    }

    removeErrorEmail() {
        this.$emailWrapper.classList.remove(this.errorInputClass);
    }

    errorEmail(message) {
        Utils.clearHtml(this.$messageEmail);
        if (message) {
            Utils.insetContent(this.$messageEmail, message);
        } else {
            Utils.insetContent(this.$messageEmail, this.emptyErrorMessage);
        }
        this.$emailWrapper.classList.add(this.errorInputClass);
    }

    showSuccessMessage(email) {
        const successMessage = `Мы выслали ссылку на восстановление пароля на адрес ${email}`;

        Utils.clearHtml(this.$description);
        Utils.insetContent(this.$description, successMessage);
        this.$description.classList.add('password-recovery-desc_is_success');
    }
}

export default PasswordRecovery;

