import Tooltip from './tooltip';
const tooltip = new Tooltip();

export default class Visual {
    constructor() {
        /**
         * Вся DOM структура выборщика
         * @type {NodeList}
         */
        this.target = null;


        /**
         * SVG элемент
         * @type {Node}
         */
        this.svg = null;

        /**
         * элемент <path> для которого был показан тултип
         * @type {Node}
         */
        this.activePath = null;

        /**
         * Обработчик для закрытия выпадающих списков с привязкой к this компонента
         * Используется для корректоного удаления обработчика при закрытии списка
         */
        this.closeTooltipHandlerBound = this.closeTooltipHandler.bind(this);
    }

    init(options) {
        this.target = options.target;
        this.svg = this.target.querySelector('.b-visual__svg');
        this.masks = Array.from(this.svg.querySelectorAll('.b-visual__svg path'));
        this.area = this.target.dataset.area;
        this.isThemePoints = this.target.classList.contains('b-visual_theme_points');
        this.mobileMode = document.body.clientWidth < 669;
        this.targetOffsetTop = this.target.getBoundingClientRect().top;

        this.insertMaskTitle();
        this.bindMasksEvents();
    }

    resizeHandler() {
        this.targetOffsetTop = this.target.getBoundingClientRect().top;
        const half = 2;

        this.masks.forEach((path) => {
            const halfPathHeight = path.getBoundingClientRect().height / half;
            const centerY = path.getBoundingClientRect().top + halfPathHeight - this.target.getBoundingClientRect().top;
            const centerX = path.getBoundingClientRect().left + (path.getBoundingClientRect().width / half);
            const titleElement = this.target.querySelector(`#area-${path.dataset.id}`);

            titleElement.style.top = `${centerY}px`;
            titleElement.style.left = `${centerX}px`;
        });
    }

    insertMaskTitle() {
        window.addEventListener('resize', this.resizeHandler.bind(this));
        const half = 2;

        this.masks.forEach((path) => {
            const halfPathHeight = path.getBoundingClientRect().height / half;
            const centerY = path.getBoundingClientRect().top + halfPathHeight - this.target.getBoundingClientRect().top;
            const centerX = path.getBoundingClientRect().left + (path.getBoundingClientRect().width / half);
            const titleElement = document.createElement('div');

            if (this.isThemePoints) {
                titleElement.classList.add('b-visual__point');
                if (path.classList.contains('is-empty')) {
                    titleElement.classList.add('is-empty');
                }
            } else {
                titleElement.classList.add('b-visual__area-title');
                titleElement.textContent = path.dataset.title;
            }

            titleElement.style.top = `${centerY}px`;
            titleElement.style.left = `${centerX}px`;
            titleElement.setAttribute('id', `area-${path.dataset.id}`);

            this.target.appendChild(titleElement);
        });
    }

    /**
     * Биндит события на маски
     */
    bindMasksEvents() {
        if (this.mobileMode) {
            this.masks.forEach((path) => {
                path.addEventListener('click', this.tooltipShow.bind(this, path));
            });
        } else {
            this.masks.forEach((path) => {
                path.addEventListener('mouseenter', this.tooltipShow.bind(this, path));
                path.addEventListener('mouseout', (event) => {
                    if (!event.relatedTarget.closest('.b-visual-tooltip')) {
                        this.tooltipRemove();
                    }
                });
            });
        }
    }

    tooltipShow(path) {
        const data = JSON.parse(atob(path.dataset.json));
        const half = 2;

        if (this.target.querySelector(`.b-visual-tooltip`)) {
            tooltip.remove();
            document.removeEventListener('click', this.closeTooltipHandlerBound);
        }

        tooltip.init({
            templateTarget: this.target,
            target        : path,
            data,
            mobileMode    : this.mobileMode
        });

        const coordY = path.getBoundingClientRect().top + (path.getBoundingClientRect().height / half);
        const coordX = path.getBoundingClientRect().right - (path.getBoundingClientRect().width / 4);

        tooltip.show();
        tooltip.position({
            x: coordX,
            y: coordY
        });

        if (this.mobileMode) {
            this.activePath = path;
            document.addEventListener('click', this.closeTooltipHandlerBound);
        }
    }

    closeTooltipHandler() {
        if (!event.target.closest('.b-visual-tooltip') && !event.target.closest('path')) {
            this.tooltipRemove();
        }
    }

    tooltipPosition(path) {
        tooltip.position({
            top : path.clientY,
            left: path.clientX
        });
    }

    /**
     * Удаляет тултип
     */
    tooltipRemove() {
        tooltip.remove();
        document.removeEventListener('click', this.closeTooltipHandlerBound);
    }
}
