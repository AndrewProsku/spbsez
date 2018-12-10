/**
 * @version 1.0
 * @author Kelnik Studios {http://kelnik.ru}
 * @link поставить ссылку, как напишется дока
 */
import Mediator from 'common/scripts/mediator';

const mediator = new Mediator();

class MobileMenu {
    constructor() {
        /**
         * Блок меню
         * @type {HTMLElement}
         */
        this.menu = null;

        /**
         * Возможные варианты позиционирования меню
         * @type {{top: string,
         * bottom: string,
         * left: string,
         * right: string}}
         */
        this.position = {
            top   : 'translateY(-100%)',
            bottom: 'translateY(100%)',
            left  : 'translateX(-100%)',
            right : 'translateX(100%)'
        };

        /**
         * позиция меню
         * @type {string}
         */
        this.currentPosition = null;
    }

    /**
     * Инициализирует меню
     * @param {string} direction - направление открытия/закрытия меню
     */
    init(direction = 'left') {
        this.menu = document.querySelector('.j-mobile-menu');
        this._transitionEnd(this.menu);
        this.currentPosition = this.position[direction];
        this.menu.style.transform = this.currentPosition;
        this.menu.style[direction] = '0';
    }

    /**
     * Открывает меню
     */
    open() {
        this.menu.style.transform = 'translate(0, 0)';
        this._stateOpen();
    }

    /**
     * Закрывает меню
     */
    close() {
        this.menu.style.transform = this.currentPosition;
        this._stateClose();
    }

    /**
     * Вешает на элемент событие окончания анимации
     * @param {HTMLElement} target - элемент, на который вешается событие
     * @private
     */
    _transitionEnd(target) {
        target.addEventListener('transitionend', () => {
            target.style.opacity = '1';
            target.style.visibility = 'visible';
        }, false);
    }

    /**
     * Сообщает, что компонент открыт
     * @private
     */
    _stateOpen() {
        mediator.publish('openMobileMenu');
    }

    /**
     * Сообщает, что компонент закрыт
     * @private
     */
    _stateClose() {
        mediator.publish('closeMobileMenu');
    }
}

export default MobileMenu;
