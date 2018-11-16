/**
 * @version 1.0alpha
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/tooltip.html documentation
 */
import './tooltip.scss';
import template from './tooltip.twig';
import Utils from '../../common/scripts/utils';

class Tooltip {
    constructor() {
        /**
         * цель, на которой появится тултип
         * @type {Node}
         */
        this.target = null;

        /**
         * Координаты цели
         * относительно всей страницы
         * @type {Array}
         */
        this.targetCoords = {};

        /**
         * DOM нода тултипа
         * @type {null}
         */
        this.tooltip = null;

        /**
         * Шаблон
         */
        this.template = template;

        /**
         * Позиция по x оси
         * @type {number}
         */
        this.x = 0;

        /**
         * Позиция по y оси
         * @type {number}
         */
        this.y = 0;

        /**
         * Расположение тултипа относительно цели
         * over - над целью
         * under - под целью
         * left - слева от цели
         * right - справа от цели
         * @type {string}
         */
        this.direction = 'over';

        /**
         * Смещение тултипа от цели
         * чем больше цифра, тем дальше тултип будет от цели
         * @type {number}
         */
        this.offset = 10;
    }

    init(options) {
        this.options = options;
        this.target = options.target;
        this.targetCoords = this.getTargetCoords();

        this.bindEvents();
    }

    getTargetCoords() {
        return {
            top   : this.target.getBoundingClientRect().top,
            right : this.target.getBoundingClientRect().right,
            bottom: this.target.getBoundingClientRect().bottom,
            left  : this.target.getBoundingClientRect().left
        };
    }

    /**
     * Вешаем слушателей на нужные контролы
     */
    bindEvents() {
        const esc = 27;

        window.addEventListener('resize', (event) => {
            return event;
        });

        document.addEventListener('keydown', (event) => {
            if (event.which === esc) {
                return event;
            }

            return event;
        });

        this.target.addEventListener('mouseover', (event) => {
            event.preventDefault();
            event.stopPropagation();

            this._insertTemplete();
        });

        this.target.addEventListener('mouseout', () => {
            Utils.removeElement(this.tooltip);
        });

        this.target.addEventListener('mousemove', (event) => {
            return event;
        });

        this.target.addEventListener('click', (event) => {
            return event;
        });
    }

    /**
     * Создает и вставляет тултип на странице
     * @private
     */
    _insertTemplete() {
        const firstElement = 0;

        Utils.insetContent(document.body, this.template({content: '<h1>привет</h1>'}));

        this.tooltip = document.querySelectorAll('.b-tooltip')[firstElement];

        const x = this._x(this.tooltip, this.direction);
        const y = this._y(this.tooltip, this.direction);

        this._position(x, y);
    }

    /**
     * Метод удаляет элемент со страницы
     * @param {Node} element - элемент, который надо удалить
     */
    remove(element) {
        Utils.removeElement(element);
    }

    /**
     * Hide element in page
     * @param {Object} element - tooltip
     */
    hide(element) {
        Utils.hide(element);
    }

    /**
     * @show
     * @param {Object} element - tooltip element
     */
    show(element) {
        element.style.display = 'block';
    }

    /**
     * координат элемента
     * @param {Object} element - элемент у короторого надо определить границы координат
     * @return {Object} - границы элемента - сверху, справа, снизу и слева
     */
    _coords(element) {
        return element.getBoundingClientRect();
    }

    /**
     * Определям X координату тултипа
     * @param {Object} tooltip - dom элемент границы тултипа
     * @param {String} direction - over|under|left|right
     * @return {number} - y координата тултипа
     */
    _x(tooltip, direction) {
        let x = 0;
        const halfWidth = 2;


        switch (direction) {
            case 'over':
                x = this.targetCoords.left +
                    (this.target.offsetWidth / halfWidth) - (tooltip.offsetWidth / halfWidth) - this.offset;
                break;
            case 'under':
                x = this.targetCoords.left +
                    (this.target.offsetWidth / halfWidth) - (tooltip.offsetWidth / halfWidth) - this.offset;
                break;
            case 'left':
                x = this.targetCoords.left - tooltip.offsetWidth - this.offset;
                break;
            case 'right':
                x = this.targetCoords.right + this.offset;
                break;
            default:
                x = this.targetCoords.left +
                    (this.target.offsetWidth / halfWidth) - (tooltip.offsetWidth / halfWidth) - this.offset;
                break;
        }

        return x;
    }

    /**
     * Определям Y координату тултипа
     * @param {Object} tooltip - dom элемент границы тултипа
     * @param {String} direction - over|under|left|right
     * @return {number} - y координата тултипа
     */
    _y(tooltip, direction) {
        let y = 0;
        const halfHeight = 2;

        switch (direction) {
            case 'over':
                y = this.targetCoords.top - tooltip.offsetHeight - this.offset;
                break;
            case 'under':
                y = this.targetCoords.bottom + this.offset;
                break;
            case 'left':
                y = this.targetCoords.top +
                    (this.target.offsetHeight / halfHeight) - (tooltip.offsetHeight / halfHeight);
                break;
            case 'right':
                y = this.targetCoords.top +
                    (this.target.offsetHeight / halfHeight) - (tooltip.offsetHeight / halfHeight);
                break;
            default:
                y = this.targetCoords.top - tooltip.offsetHeight - this.offset;
                break;
        }

        return y;
    }

    /**
     * Позиционирование элемента
     * @param {Number/String} x - координаты цели по x
     * @param {Number/String} y - координаты цели по y
     */
    _position(x, y) {
        this.tooltip.style.top = `${parseInt(y, 10)}px`;
        this.tooltip.style.left = `${parseInt(x, 10)}px`;
    }
}

export default Tooltip;
