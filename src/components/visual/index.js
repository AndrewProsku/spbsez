/* временно */
/* eslint-disable */

import Tooltip from './tooltip';
import Utils from 'common/scripts/utils';
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

        this.places = null;
    }

    init(options) {
        this.allDOM = options.allDOM;
        this.target = options.target; // .b-visual
        this.svg = this.target.querySelector('.b-visual__svg');
        this.masks = Array.from(this.svg.querySelectorAll('.b-visual__svg path'));
        this.area = this.target.dataset.area;
        this.FAIL_STATUS = 0;

        /*const that = this;
        Utils.send(`action=getPlan`, `/tests/plan-${this.area}.json`, {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }

                that.places = response.data.places;
            },
            error(error) {
                console.error(error);
            }
        });*/

        this.masksBindEvents();
    }

    /**
     * Биндит события на маски
     */
    masksBindEvents() {
        this.masks.forEach((path) => {
            path.addEventListener('mouseenter', this.tooltipShow.bind(this, path));
            // path.addEventListener('mousemove', this.tooltipPosition);
            path.addEventListener('mouseout', this.tooltipRemove);
        });
    }

    /**
     * удаляет событие с масок
     */
    masksUnbindEvents() {
        // this.masks.paths.forEach((path) => {
        this.masks.forEach((path) => {
            path.removeEventListener('mouseenter', this.tooltipShow);
            // path.removeEventListener('mousemove', this.tooltipPosition);
            path.removeEventListener('mouseout', this.tooltipRemove);
        });
    }

    /**
     * Показывает тултип
     */
    tooltipShow(path) {
        const data = JSON.parse(atob(path.dataset.json));

        tooltip.init({
            allDOM        : this.allDOM,
            templateTarget: this.target,
            target        : path,
            data          : data
        });

        tooltip.show();
        tooltip.position({
            top : path.clientY,
            left: path.clientX
        });
    }

    imageTooltipShow(image) {
        tooltip.initImage({
            allDOM        : this.allDOM,
            templateTarget: this.target,
            target        : image,
            data          : image.data
        });

        tooltip.showImage();
        tooltip.position({
            top : image.clientY,
            left: image.clientX
        });
    }

    /**
     * Позиционирует тултип
     */
    tooltipPosition(path) {
        tooltip.position({
            top : path.clientY,
            left: path.clientX
        });
    }

    imageTooltipPosition(image) {
        tooltip.position({
            top : image.clientY,
            left: image.clientX
        });
    }

    /**
     * Удаляет тултип
     */
    tooltipRemove() {
        tooltip.remove();
    }
}

/* временно */
/* eslint-enable */
