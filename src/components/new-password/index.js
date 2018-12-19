import Utils from '../../common/scripts/utils';

class PasswordRecovery {
    constructor() {
        this.form = 'j-form-password-recovery';
        this.newPassword = 'j-input-new-password';
        this.repeatPassword = 'j-input-repeat-password';
        this.password = 'j-input-current-password';
        this.submit = 'j-new-password-submit';
        this.messageClass = 'b-lb-input__message';
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.$form = document.querySelector(`.${this.form}`);
        this.$newPassword = document.querySelector(`.${this.newPassword}`);
        this.$repeatPassword = document.querySelector(`.${this.repeatPassword}`);
        this.$password = document.querySelector(`.${this.password}`);
        // this.$submit = document.querySelector(`.${this.submit}`);
    }

    _bindEvents() {
        const $inputNewPassword = this.$newPassword.querySelector('input');

        this.$form.addEventListener('submit', (event) => {
            event.preventDefault();
            const that = this;

            if (!this.checkMinLength($inputNewPassword)) {
                return;
            }

            if (!this.checkRepeatPassword()) {
                return;
            }

            const sendData = new FormData(this.$form);

            Utils.send(sendData, '/tests/new-password.json', {
                success(response) {
                    const {data} = response;

                    if (data.password) {
                        that.hideMessage(that.$password);
                    } else {
                        that.showMessage(that.$password);
                    }

                    if (data.replacePassword) {
                        that.successMessage();
                        that.showMessage(that.$password);
                    }
                }
            });
        });


        $inputNewPassword.addEventListener('change', () => {
            this.checkMinLength($inputNewPassword);
        });
    }

    showMessage($item) {
        const $message = $item.querySelector(`.${this.messageClass}`);

        if (!$message) {
            return;
        }

        Utils.show($message);
    }

    hideMessage($item) {
        const $message = $item.querySelector(`.${this.messageClass}`);

        if (!$message) {
            return;
        }

        Utils.hide($message);
    }

    checkMinLength($input) {
        const minSize = 6;

        if ($input.value.length >= minSize) {
            this.hideMessage(this.$newPassword);

            return true;
        }

        this.showMessage(this.$newPassword);

        return false;
    }

    checkRepeatPassword() {
        const $inputNewPassword = this.$newPassword.querySelector('input');
        const $inputRepeatPassword = this.$repeatPassword.querySelector('input');

        if ($inputNewPassword.value === $inputRepeatPassword.value) {
            this.hideMessage(this.$repeatPassword);

            return true;
        }

        this.showMessage(this.$repeatPassword);

        return false;
    }

    successMessage() {
        const $message = this.$password.querySelector(`.${this.messageClass}`);

        if (!$message) {
            return;
        }

        Utils.clearHtml($message);
        Utils.insetContent($message, 'Пароль успешно изменен!');
    }
}

export default PasswordRecovery;

