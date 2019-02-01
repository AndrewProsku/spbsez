import Utils from 'common/scripts/utils';

class More {
    constructor() {
        /**
         * Кнопка по которой будут кликать ,чтобы добавить новый контент
         */
        this.button = null;

        /**
         * Шаблон для контента
         */
        this.template = null;

        /**
         * Место куда будет вставлен новый контент
         */
        this.content = null;

        /**
         * Урл до сервера
         */
        this.requestUrl = null;

        /**
         * Шаг подгрузки
         * @type {number}
         */
        this.step = 1;
    }

    init(options) {
        this.button = options.button;
        this.content = options.content;
        this.template = options.template;
        this.requestUrl = this.button.dataset.send;
        this._bindEvents();
    }

    _bindEvents() {
        this.button.addEventListener('click', (event) => {
            event.preventDefault();

            this._download();
        });
    }

    _download() {
        this.requestUrl = this.button.dataset.send;

        Utils.send(`step=${this.step}`, this.requestUrl, {
            success: (response) => {
                this._stepsCount();
                Utils.insetContent(this.content, this.template(response));
                this._toggleButton(response.data.IS_END);
            }
        });
    }

    /**
     * Считает шаг для передачи на сервер
     * @private
     */
    _stepsCount() {
        const increment = 1;

        this.step = this.step + increment;
    }

    resetStep() {
        this.step = 1;
    }

    _toggleButton(hide) {
        this.button.style.display = hide ? 'none' : 'block';
    }
}

export default More;
