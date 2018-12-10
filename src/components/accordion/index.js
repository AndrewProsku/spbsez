/**
 * @version 1.1
 * @author Kelnik Studios {http://kelnik.ru}
 */

/**
 * Активный класс
 * @type {string}
 */
const activeClass = 'b-accordion_is_open';

class Accordion {
    /**
     * @constructor
     */
    constructor() {
        this.defaultHeight = 0;
        this.target = {};
        this.header = null;
        this.contentWrapper = null;
        this.content = null;
    }

    /**
     * Инициализация
     * @param {Object} options - внешние параметры
     */
    init(options) {
        this._setOptions(options);
        this._setHeightContentWrapper(this.defaultHeight);
        this._bindEvents();
    }

    /**
     * Установка элементов
     * @param {Object} options - опции
     * @private
     */
    _setOptions(options) {
        this.target = options.target;
        this.header = this.target.querySelector('.b-accordion__header');
        this.contentWrapper = this.target.querySelector('.b-accordion__content-wrapper');
        this.content = this.target.querySelector('.b-accordion__content');
    }

    /**
     * Биндим события
     * @private
     */
    _bindEvents() {
        this.header.addEventListener('click', this._onHeaderClick.bind(this));
        window.addEventListener('resize', this._onWindowResize.bind(this));
    }

    /**
     * Установка высоты обертке контента
     * @param {number} height - высота
     * @private
     */
    _setHeightContentWrapper(height) {
        this.contentWrapper.style.height = `${height}px`;
    }

    /**
     * Событие, которое происходит при нажатии на заголовок
     * @private
     */
    _onHeaderClick() {
        const method = this.target.classList.contains(activeClass) ?
            '_hideContent' :
            '_showContent';

        this[method]();
    }

    /**
     * Скрытие контента
     * @private
     */
    _hideContent() {
        this.target.classList.remove(activeClass);
        this._setHeightContentWrapper(this.defaultHeight);
    }

    /**
     * Показывание контента
     * @private
     */
    _showContent() {
        this.target.classList.add(activeClass);
        this._updateHeightContent();
    }

    /**
     * Событие, которое происходит при изменении ширины окна
     * @private
     */
    _onWindowResize() {
        if (this.target.classList.contains(activeClass)) {
            this._updateHeightContent();
        }
    }

    /**
     * Обновление высоты обертки контента
     * @private
     */
    _updateHeightContent() {
        const height = this.content.offsetHeight;

        this._setHeightContentWrapper(height);
    }
}

export default Accordion;
