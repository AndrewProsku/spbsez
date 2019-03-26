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
        this.templateTarget = options.templateTarget;
        this.target = options.target;
        this.data = options.data;
        this.mobileMode = options.mobileMode;
        this.template = this.getTemplate();
    }

    /**
     * Показывает тултип
     */
    show() {
        Utils.insetContent(this.templateTarget, this.template(this.data));
        this.tooltip = this.templateTarget.querySelector('.b-visual-tooltip');
        this.tooltip.setAttribute('id', `area-${this.target.dataset.id}`);
        this.size();

        if (!this.mobileMode) {
            this.tooltip.addEventListener('mouseout', (event) => {
                if (!event.relatedTarget.closest('.b-visual-tooltip')) {
                    this.remove();
                }
            });
        }
    }

    getTemplate() {
        return this.target.classList.contains('is-empty') ? templateEmpty : templateResident;
    }

    /**
     * Узнает и устанавливает размеры тултипа
     */
    size() {
        this.width = this.tooltip.offsetWidth;
        this.height = this.tooltip.getBoundingClientRect().height;
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
        const half = 2;

        // не дает тултипу уйти за экран сверху
        if (coords.y - (this.height / half) - offset <= offset) {
            top = `${offset}px`;
        } else {
            top = `${coords.y - (this.height / half) - offset}px`;
        }

        // не дает тултипу уйти за экран справа
        if (coords.x + this.width - offset <= document.documentElement.offsetWidth) {
            left = `${coords.x - offset}px`;
        } else {
            left = `${document.documentElement.offsetWidth - this.width - offset}px`;
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
