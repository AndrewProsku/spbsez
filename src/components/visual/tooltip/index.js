import templateEmpty from './tooltip-empty.twig';
import templateResident from './tooltip.twig';
import Utils from 'common/scripts/utils';

export default class Tooltip {
    constructor() {
        /**
         * шаблон
         * @type {null}
         */
        this.template = null;

        /**
         * Цель над которой показывается тултип
         * @type {null}
         */
        this.target = null;

        /**
         * Данные для шаблона
         * @type {null}
         */
        this.data = null;

        /**
         * DOM элемент тултипа
         * @type {Node}
         */
        this.tooltip = null;

        /**
         * Ширина тултипа
         * @type {number}
         */
        this.width = 0;

        /**
         * Высота тултипа
         * @type {number}
         */
        this.height = 0;
    }

    /**
     * Инициализация
     * @param {Object} options - настройки тултипа
     */
    init(options) {
        this.allDOM = options.allDOM;
        this.templateTarget = options.templateTarget;
        this.target = options.target;
        this.data = options.data;
        this.template = this.setTemplate();
        // this.checkTemplate();
    }

    /**
     * Показывает тултип
     */
    show() {
        Utils.insetContent(this.templateTarget, this.template(this.data));
        this.tooltip = document.querySelector('.b-visual-tooltip');
        this.size();
    }

    setTemplate() {
        return this.target.classList.contains('is-empty') ? templateEmpty : templateResident;
    }

    /**
     * Узнает и устанавливает размеры тултипа
     */
    size() {
        // некоторый запас ширины
        const buffer = 5;

        this.width = this.tooltip.offsetWidth;
        this.height = this.tooltip.offsetHeight;
        this.tooltip.style.width = `${this.width + buffer}px`;
        this.tooltip.style.height = `${this.height}px`;
    }

    /**
     * Меняет позицию тултипа
     * @param {Object} coords - coords.left, coords.right - координаты слева и сверху относительно всей страницы
     */
    position(coords) {
        this.tooltip.style.top = this._calculatePosition(coords).top;
        this.tooltip.style.left = this._calculatePosition(coords).left;
    }

    /**
     * Высчитывает позицию тултипа
     * @param {Object} coords - top, left - позиция курсор
     * @return {{top: string, left: string}} - координаты x, y - для тулптипа
     * @private
     */
    _calculatePosition(coords) {
        const offset = 10;
        let top = 0;
        let left = 0;

        // не дает тултипу уйти за экран сверху
        if (coords.top - this.height - offset <= offset) {
            top = `${offset}px`;
        } else {
            top = `${coords.top - this.height - offset}px`;
        }

        // не дает тултипу уйти за экран справа
        if (coords.left + this.width + offset <= this.allDOM.offsetWidth) {
            left = `${coords.left}px`;
        } else {
            left = `${this.allDOM.offsetWidth - this.width - offset}px`;
        }

        return {
            top,
            left
        };
    }

    /**
     * Удаляет тулптип
     */
    remove() {
        Utils.removeElement(this.tooltip);
    }
}
