import './button-up.scss';
import $ from 'jquery';
import template from './button-up.twig';
import Utils from 'common/scripts/utils';

class ButtonUp {
    constructor() {
        /**
         * @type {number}
         * Значение позиции скролла к которому будет прокручена страница.
         */
        this.initialValue = 0;

        /**
         * Расстояние в пикселях с учетом которого будет появлятся кнопка после скролла первого экрана.
         * @type {number}
         */
        this.softZone = 0;

        /**
         * Скорость скролинга до начала страницы.
         * @type {number}
         */
        this.duration = 400;

        /**
         * Положение скрола на странице.
         * @type {number}
         */
        this.scrollTop = 0;
    }

    /**
     *
     * @param {Object} options - настройки плагина.
     */
    init(options) {
        this.insetElement = document.querySelector(options.insetElement);
        this.firstScreen = document.querySelector(options.firstScreen);
        Utils.insetContent(this.insetElement, template());
        this._getParams();
        this._bindEvents();
        this._control();
    }

    /**
     * Получает параметры.
     */
    _getParams() {
        this.buttonUp = this.insetElement.querySelector('.j-button-up');
        this.heightFirstScreen = this.firstScreen.offsetHeight;
        this.positionFirstScreen = this.firstScreen.getBoundingClientRect().top + pageYOffset;
        this.checkPoint = this.heightFirstScreen + this.positionFirstScreen + this.softZone;
    }

    /**
     * Обрабатывает события.
     */
    _bindEvents() {
        window.addEventListener('scroll', () => {
            this._control();
        });

        this.buttonUp.addEventListener('click', this._scrollToTop.bind(this));
    }

    /**
     * Управляет появлением/скрытием кнопки.
     */
    _control() {
        this.scrollTop = window.scrollY;

        if (this.scrollTop > this.checkPoint) {
            this._show();
        } else {
            this._hide();
        }
    }

    /**
     * Показывает кнопку.
     */
    _show() {
        this.buttonUp.classList.add('is-visible');
    }

    /**
     * Скрывает кнопку.
     */
    _hide() {
        this.buttonUp.classList.remove('is-visible');
    }

    /**
     * Скролит до значения с задержкой.
     */
    _scrollToTop() {
        $('html, body').animate({scrollTop: this.initialValue}, this.duration);
    }
}

export default ButtonUp;
