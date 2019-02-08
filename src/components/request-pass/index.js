import InputTel from '../../components/forms/telephone/telephone';
import templatePass from './templates/form-add-pass.twig';
import Utils from '../../common/scripts/utils';

class RequestPass {
    constructor() {
        this.$administrators = document.querySelector('.j-request-pass');
        this.$passContainer = document.querySelector('.j-pass-container');
        this.$addPassButton = document.querySelector('.j-add-pass');

        this.deletePassClass = 'j-delete-pass';
    }

    init() {
        this.bindEvents();
        this.initTelInput();
        this.initDateInput();
    }

    bindEvents() {
        // Добавление контактного лица
        this.$addPassButton.addEventListener('click', () => {
            this.addPass();
        });
    }

    initTelInput() {
        const inputTel = new InputTel();
        const telInputs = Array.from(this.$administrators.querySelectorAll('input[type="tel"]'));

        inputTel.init({input: telInputs});
    }

    initDateInput() {
        const inputDate = new InputTel();
        const dateInputs = Array.from(this.$administrators.querySelectorAll('input[data-date="true"]'));

        inputDate.init({
            input: dateInputs,
            mask : '99.99.9999, 99:99'
        });
    }

    addPass() {
        Utils.insetContent(this.$passContainer, templatePass());
        this.initDelete();
    }

    initDelete() {
        Array.from(document.querySelectorAll(`.${this.deletePassClass}`)).forEach(($delete) => {
            $delete.addEventListener('click', () => {
                Utils.removeElement($delete.parentNode);
            });
        });
    }
}

export default RequestPass;

