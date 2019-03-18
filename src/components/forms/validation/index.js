/**
 * Dependencies
 */

import hyperform from 'hyperform';
import Language from '../../language';
import Utils from 'common/scripts/utils';

const Lang = new Language();

class Validation {
    constructor() {
        this.errorClass = 'has-error';
        this.errorWrapClass = 'b-form-error';
    }

    init(options) {
        this.input = options.target;
        this.errorMessage = options.error || Lang.get('validation.required');
        this.inputWrap = this.input.closest('div');
        this.errorWrap = this.inputWrap.querySelector(`.${this.errorWrapClass}`);

        this.bindEvents();
    }

    bindEvents() {
        this.input.addEventListener('blur', this.validate.bind(this));
        this.input.addEventListener('input', this.validate.bind(this));
    }

    createErrorWrap() {
        if (this.errorWrap === null) {
            Utils.insetContent(this.inputWrap, `<div class="${this.errorWrapClass}"></div>`);
        }
        this.errorWrap = this.inputWrap.querySelector(`.${this.errorWrapClass}`);
    }

    validate() {
        this.createErrorWrap();

        if (hyperform.willValidate(this.input)) {
            const isValid = hyperform.ValidityState(this.input).valid; // eslint-disable-line

            if (isValid) {
                Utils.clearHtml(this.errorWrap);
                this.inputWrap.classList.remove(this.errorClass);
            } else {
                Utils.clearHtml(this.errorWrap);
                Utils.insetContent(this.errorWrap, this.errorMessage);
                this.inputWrap.classList.add(this.errorClass);
            }
        }
    }
}

export default Validation;
