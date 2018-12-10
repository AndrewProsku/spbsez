import Mediator from 'common/scripts/mediator';

const mediator = new Mediator();

class MapRoutes {
    /**
     * Базовые свойства
     * @constructor
     */
    constructor() {
        this.target = null;
        this.from = [];
        this.activeClass = 'b-map-routes__button_is_active';
        this.buttons = null;
    }

    /**
     * Инициализирует плагин
     * @param {Object} outerSettings - настройки из внешнего файла
     */
    init(outerSettings) {
        this.target = outerSettings.target;
        this.buttons = this.target.querySelectorAll('.b-map-routes__button');

        this.bindClick();
    }

    /**
     * Навешивает обработчик клика
     */
    bindClick() {
        this.target.addEventListener('click', (event) => {
            this.from = [];

            event.target.dataset.routeFrom.split(', ')
                .forEach((coord) => {
                    this.from.push(parseFloat(coord));
                });

            this.changeState(event.target);

            mediator.publish('clickMapRouter');
        });
    }

    /**
     * Измененяет состяние активности у кнопки
     * @param {Node} target - кнопка по которой кликнули
     */
    changeState(target) {
        for (let i = 0; i < this.target.children.length; i++) {
            if (this.buttons[i] === target) {
                this.buttons[i].classList.add(this.activeClass);
            } else {
                this.buttons[i].classList.remove(this.activeClass);
            }
        }
    }
}

export default MapRoutes;
