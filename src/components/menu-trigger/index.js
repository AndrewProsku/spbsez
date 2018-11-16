/**
 * @version 1.0
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/menu-trigger.html documentation
 */

import Mediator from 'common/scripts/mediator';

const mediator = new Mediator();

class MenuTrigger {
    constructor() {
        /**
         * Элемент по которому кликают
         * @type {null}
         */
        this.button = null;

        /**
         * css-класс для состояния открытия
         * @type {string}
         */
        this.classStateOpen = 'b-menu-trigger_theme_open';

        /**
         * css-класс для состояния закрытия
         * @type {string}
         */
        this.classStateClose = 'b-menu-trigger_theme_close';

        /**
         * Состояния кнопки
         * open - открывает
         * close - закрывает
         * @type {string}
         */
        this.state = null;

        /**
         * Динамичность кнопки
         * Если динамичная, то состояние меняется при клике
         * если не динамичное, то состояние вообще не меняется
         * @type {boolean}
         */
        this.dynamic = false;
    }

    /**
     * Инициализирует компонент
     * @param {Object} options - объект настроек
     */
    init(options) {
        this.button = options.target;
        this.state = this.button.classList.contains(this.classStateOpen) ? 'close' : 'open';
        this.dynamic = this.button.dataset.dynamic || false;

        this._bindEvents();
    }

    /**
     * Навешивает слушателей на элементы
     * @private
     */
    _bindEvents() {
        this.button.addEventListener('click', this._clickBind.bind(this));
    }

    /**
     * Обрабатывает клик
     * @private
     */
    _clickBind() {
        if (this.dynamic) {
            this._toggleClass();
            this._changeState();
        } else {
            this._changeState();
        }
    }

    /**
     * Изменяет состояние
     * с отрытого на закрытое и наоборот
     * @private
     */
    _changeState() {
        if (this.state === 'open') {
            this._stateClose();
            this.state = 'close';
        } else {
            this.state = 'open';
            this._stateOpen();
        }
    }

    /**
     * Изменяет классы на элементе с открытого на закрытое и открытое
     * @private
     */
    _toggleClass() {
        this.button.classList.toggle(this.classStateClose);
        this.button.classList.toggle(this.classStateOpen);
    }

    /**
     * Сообщает, что компонент открыт
     * @private
     */
    _stateOpen() {
        mediator.publish('openMenuTrigger');
    }

    /**
     * Сообщает, что компонент закрыт
     * @private
     */
    _stateClose() {
        mediator.publish('closeMenuTrigger');
    }
}

export default MenuTrigger;
