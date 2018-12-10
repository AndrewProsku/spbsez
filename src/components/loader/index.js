/**
 * @version 1.1
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/loader.html documentation
 */

/**
 * DEPENDENCIES
 */
import './loader.scss';
import Mediator from 'common/scripts/mediator';
import template from './loader.twig';

const mediator = new Mediator();

class Loader {
    constructor() {
        /**
         * Размер по умолчанию
         * @type {number}
         */
        const svgSize = 24;

        /**
         * Node куда будет вставлен лоадер
         * @type {null}
         */
        this.target = null;

        /**
         * размер
         * @type {*[]}
         */
        this.size = [svgSize, svgSize];

        /**
         * цвет лоадера
         * @type {string} - color
         */
        this.color = 'gray';

        /**
         * скорость вращения. Скорость одного полного оборота
         * в миллисекундах или секундах
         * @type {string}
         */
        this.speed = '600ms';

        /**
         * HTML Шаблон
         * @type {null}
         */
        this.template = template;

        /**
         * Node лоадера
         * @type {null}
         */
        this.loader = null;
    }

    /**
     * Инициализирует лоадер
     * @param {Object} options - внещние настройки лоадера
     */
    init(options) {
        this.target = options.target;

        if (!this.target) {
            console.error('Укажи цель - https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/loader.html');

            return;
        }

        this._setOptions(options);
        this._render();
    }

    /**
     * Указывает настройки лоадера
     * @param {Object} options - внешние настройки
     * @see https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/loader.html#short-settings settings
     * @private
     */
    _setOptions(options) {
        this.size = options.size || this.size;
        this.color = options.color || this.color;
        this.speed = options.speed || this.speed;
    }

    /**
     * Создает HTML
     * @private
     * @return {Object} HTML лоадера
     */
    _createHTML() {
        const width = 0;
        const height = 1;

        return this.template({
            width : this.size[width],
            height: this.size[height] ? this.size[height] : this.size[width],
            color : this.color,
            speed : this.speed
        });
    }

    /**
     * Render loader html in target element
     * @private
     */
    _render() {
        this.target.insertAdjacentHTML('beforeend', this._createHTML());
        this.loader = this.target.querySelector('.b-loader');

        mediator.publish('renderLoader');
    }

    /**
     * Show loader
     * @see https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/loader.html#show show
     * @public
     */
    show() {
        this.loader.style.display = 'block';
        this.loader.style.animationPlayState = 'running';

        mediator.publish('showLoader');
    }

    /**
     * Hide loader
     * @see https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/loader.html#hide hide
     * @public
     */
    hide() {
        this.loader.style.display = 'none';
        this.loader.style.animationPlayState = 'paused';

        mediator.publish('hideLoader');
    }
}

export default Loader;
