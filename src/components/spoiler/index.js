/**
 * @version 1.2
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/spoiler.html documentation
 */

/**
 * DEPENDENCIES
 */
import './spoiler.scss';

class Spoiler {
    constructor(options) {
        this.options = options;
        this.init();
    }

    /**
     * Инициализируем спойлер
     */
    init() {
        this.setSettings();
        this.getHeightSpoiler();
        this.bindEvents();

        if (this.options.isActive) {
            this.openSpoiler();
        }
    }

    /**
     * Создаём настройки
     */
    setSettings() {
        this.target = this.options.target;
        this.spoiler = this.target.querySelector('.b-spoiler');
        // состояние слайдера true: открыт, false: закрыт
        this.stateOpen = null;
        this.spoilerWrap = this.spoiler.querySelector('.b-spoiler__wrap');
        this.content = this.spoiler.querySelector('.b-spoiler__content');
        this.button = this.spoiler.querySelector('.b-spoiler__button');
        this.buttonName = this.spoiler.querySelector('.b-spoiler__button-name');
        this.showNameButton = this.buttonName.textContent;
        this.icon = this.spoiler.querySelector('.b-spoiler__button-icon');
    }

    /**
     * Вешаем слушателей событий
     */
    bindEvents() {
        this.button.addEventListener('click', this.changeStateSpoiler.bind(this));

        window.addEventListener('resize', () => {
            this.update();
        });
    }

    /**
     * Получение начальной высоты спойлера
     */
    getHeightSpoiler() {
        this.spoilerHeight = this.spoilerWrap.offsetHeight;
    }

    /**
     * Изменение имени кнопки согласно дата атрибуту
     */
    changeButtonName() {
        const hideNameButton = this.buttonName.getAttribute('data-hide-name');

        if (!hideNameButton) {
            return;
        }

        const nameButton = this.buttonName.textContent;

        if (nameButton === hideNameButton) {
            this.buttonName.innerHTML = this.showNameButton;
        } else {
            this.buttonName.innerHTML = hideNameButton;
        }
    }

    /**
     * Изменение состояния спойлера. Открытый или закрытый
     */
    changeStateSpoiler() {
        if (this.stateOpen) {
            this.closeSpoiler();
        } else {
            this.openSpoiler();
        }
    }

    /**
     * Изменение состояние иконки. Активная или неактивная
     */
    changeStateIcon() {
        if (!this.icon) {
            return;
        }

        if (this.stateOpen) {
            this.icon.classList.add('b-spoiler__button-icon_state_active');
        } else {
            this.icon.classList.remove('b-spoiler__button-icon_state_active');
        }
    }

    /**
     * Открытие спойлера
     */
    openSpoiler() {
        this.stateOpen = true;
        this.spoilerWrap.style.height = `${this.content.scrollHeight}px`;
        this.spoilerWrap.classList.add('b-spoiler__wrap_state_active');
        this.changeStateIcon();
        this.changeButtonName();
    }

    /**
     * Закрытие спойлера
     */
    closeSpoiler() {
        this.stateOpen = false;
        this.spoilerWrap.style.height = `${this.spoilerHeight}px`;
        this.spoilerWrap.classList.remove('b-spoiler__wrap_state_active');
        this.changeStateIcon();
        this.changeButtonName();
    }

    /**
     * Обновление высоты открытого спойлера
     */
    update() {
        if (this.stateOpen) {
            this.spoilerWrap.style.height = 'auto';
            this.spoilerWrap.style.height = `${this.content.scrollHeight}px`;
        }
    }
}

export default Spoiler;
