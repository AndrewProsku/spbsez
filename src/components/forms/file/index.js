import Utils from 'common/scripts/utils';

class InputFile {
    init(options) {
        this.target = options.target;
        this.clearButton = this.target.querySelector('.b-input-file__delete');
        this.addButton = this.target.querySelector('.b-input-file__add');
        this.fileInput = this.target.querySelector('.b-input-file__input');
        this.fileInputText = this.target.querySelector('.b-input-file__text');

        this._bindEvents();
    }

    _bindEvents() {
        this.fileInput.addEventListener('change', (event) => {
            // eslint-disable-next-line no-magic-numbers
            this.fileInputText.textContent = event.target.files[0].name;
            Utils.hide(this.addButton);
            Utils.show(this.clearButton);
        });
        this.clearButton.addEventListener('click', () => {
            const NBSP_CHAR_CODE = 160;

            this.fileInput.value = null;
            this.fileInputText.textContent = String.fromCharCode(NBSP_CHAR_CODE);

            Utils.hide(this.clearButton);
            Utils.show(this.addButton);
        });
    }
}

export default InputFile;
