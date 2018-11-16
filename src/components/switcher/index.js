// Зависимости
import Mediator from 'common/scripts/mediator';

const mediator = new Mediator();

class Switcher {
    constructor() {
        /**
         * Весь html компонента
         * @type {null}
         */
        this.target = null;

        /**
         * состояние свитчера
         * 0 - по умолчанию (не активное)
         * 1 - активное состояние
         * @type {number}
         */
        this.state = 0;

        /**
         * класс активности в CSS
         * понадобится для стилистических украшений в активном состояние
         * @type {string}
         */
        this.activeStateClass = 'b-switcher_state_change';

        /**
         * DOM трэка
         * @type {null}
         */
        this.track = null;

        /**
         * Ширина трэка
         * нужно для перемещения кнопки
         * @type {number}
         */
        this.trackWidth = 0;

        /**
         * DOM кнопки
         * @type {null}
         */
        this.handler = null;

        /**
         * Чекбокс
         * может понадобится если свитчер используется в формах
         * false - неактивное состояние
         * true - активное состояние
         * @type {null}
         */
        this.checkbox = false;
    }

    /**
     * Инициализирует компонент
     * @param {Object} options - внешние опции свитчера
     */
    init(options) {
        this.target = options.target;
        this.track = this.target.querySelector('.b-switcher__track');
        this.handler = this.target.querySelector('.b-switcher__handler');
        this.checkbox = this.target.querySelector('.b-switcher__checkbox');
        this.trackWidth = this.track.offsetWidth;
        this.bindEvents();
    }

    /**
     * Привязывает события к элементам
     */
    bindEvents() {
        this.target.addEventListener('click', this.bindClick.bind(this));
    }

    /**
     * Обрабатывает клик на всем комопненте
     * @param {Object} event - объект события
     */
    bindClick(event) {
        event.preventDefault();
        event.stopPropagation();
        this.changeState();
    }

    /**
     * При каждом вызове инвертирует состояние и вызывает противоположный обработчик состояния
     */
    changeState() {
        this.target.classList.toggle(this.activeStateClass);
        this.state = !this.state;
        this.checkbox.checked = !this.checkbox.checked;

        if (this.state) {
            this.activeState();
        } else {
            this.defaultState();
        }
    }

    /**
     * Активное состояния
     */
    activeState() {
        this.handler.style.transform = `translateX(${this.trackWidth}px)`;

        mediator.publish('stateActiveSwitcher');
    }

    /**
     * Состояние по умолчанию
     */
    defaultState() {
        this.handler.style.transform = `translateX(0px)`;

        mediator.publish('stateDefaultSwitcher');
    }
}

export default Switcher;
