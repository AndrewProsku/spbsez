import successTpl from 'components/forms/validation/validation.twig';
import Utils from 'common/scripts/utils';
import Validation from 'components/forms/validation';

class Submit {
    init(options) {
        this.target = options.target;
        this.form = this.target.closest('form');
        this.formWrap = this.target.closest('.b-callback__form');
        this.replyWrap = this.formWrap.nextElementSibling;
        this.url = this.form.getAttribute('action');
        this.inputs = this.form.querySelectorAll('.j-input');
        this.noErrors = 0;

        this.bindEvents();
    }

    bindEvents() {
        this.target.addEventListener('click', (event) => {
            event.preventDefault();
            this.validationCheck();
        });
    }

    validationCheck() {
        this.inputs.forEach((item) => {
            const inputValidation = new Validation();
            const errorMessage = item.dataset.error;

            inputValidation.init({
                target: item,
                error : errorMessage
            });

            inputValidation.validate();
        });

        if (document.querySelectorAll('.has-error').length === this.noErrors) {
            this.send();
        }
    }

    send() {
        const that = this;
        const formData = $(this.form).serialize();

        Utils.send(formData,
            this.url,
            {
                success(respond) {
                    that.showSuccessMessage(respond);
                },
                error(error) {
                    console.error(`ошибка на сервере: ${error}`);
                }
            });
    }

    showSuccessMessage(data) {
        this.formWrap.style.display = 'none';
        Utils.insetContent(this.replyWrap, successTpl(data.request.data));
    }
}

export default Submit;
