import Utils from '../../common/scripts/utils';

class PasswordRecovery {
    constructor() {
        this.form = 'j-form-password-recovery';
        this.email = 'j-input-email';
        this.errorInputClass = 'b-form-block-error';
        this.messageInputClass = 'b-form-block__error-text';
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.$form = document.querySelector(`.${this.form}`);
        this.$email = document.querySelector(`.${this.email}`);
        this.$inputEmail = this.$email.querySelector('input');
        this.$messageEmail = this.$email.querySelector(`.${this.messageInputClass}`);
        this.isEmail = false;
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
            const sendData = new FormData(event.target);

            Utils.send(sendData, '/tests/check-email.json', {
                success(response) {
                    const {data} = response;

                    if (data.success) {
                        // Отображать сообщение об успехе
                        // document.querySelector('.j-password-recovery-button').classList.remove('password-recovery-block_is_hidden');
                    } else {
                        that.errorEmail(data.errorText);
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
        this.$email.classList.remove(this.errorInputClass);
    }

    errorEmail(message) {
        Utils.clearHtml(this.$messageEmail);
        if (message) {
            Utils.insetContent(this.$messageEmail, message);
        } else {
            Utils.insetContent(this.$messageEmail, this.emptyErrorMessage);
        }
        this.$email.classList.add(this.errorInputClass);
    }

    // getSuccessTemplate(email) {
    //     const template = `
    //         <div class="b-password-recovery-step3__desc">
    //             Мы выслали ссылку на восстановление пароля на адрес ${email}
    //         </div>`;
    // }
}

export default PasswordRecovery;

