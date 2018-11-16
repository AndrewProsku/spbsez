/**
 * @version 1.1alpha
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/mobile-header.html documentation
 */

/**
 * Dependencies
 */
import './mobile-header.scss';
import Hammer from 'hammerjs';


/**
 * Component
 */
class Header {
    init(options) {
        this.headerParent = document.querySelector(options.element) || null;


        /**
         * Проверка на наличие хэдера
         */
        if (!this.headerParent) {
            console.error('Need target element - https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/mobile-header.html');

            return;
        }

        this.body = document.body;
        this.header = this.headerParent.querySelector('.b-header');
        this.menu = this.headerParent.querySelector('.b-header__mobile-menu');
        this.openButton = this.headerParent.querySelector('.b-header__open-menu');
        this.closeButton = this.headerParent.querySelector('.b-header__mobile-menu-close');
        this.scrollPosition = 0;
        this.autoFixed = options.autoFixed;
        this.autoPositionCloseButton = options.autoPositionCloseButton;
        this.returnPosition = options.returnPosition;
        this.swipeDirection = options.swipeDirection || 'right';

        /**
         * Проверка на корректное указание направления для свайпа (доступно только слева направо, и справа налево)
         */
        if (this.swipeDirection !== 'left' && this.swipeDirection !== 'right') {
            console.error('Wrong direction swipe - https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/mobile-header.html');
        }

        this.useOptions();
        this.bindEvents();
        this.setHeightHeader();
    }

    /**
     * События
     */
    bindEvents() {
        /**
         * Cобытия для позиционирования кнопки закрыть и установки высоты хэдеру
         */
        window.addEventListener('resize', this.changePositionCloseButton.bind(this));
        window.addEventListener('resize', this.setHeightHeader.bind(this));

        /**
         * Cобытия для открытия/закрытия меню
         */
        this.openButton.addEventListener('click', this.openMenu.bind(this));
        this.closeButton.addEventListener('click', this.closeMenu.bind(this));

        /**
         * Закрытие меню по свайпу (по умолчанию - слева направо)
         */
        new Hammer(this.menu).on(`swipe${this.swipeDirection}`, () => {
            this.closeMenu();
        });
    }

    /**
     * Метод применения настроек
     */
    useOptions() {
        /**
         * Запуск скрипта скролла при настройке autoFixed: true
         * @type {boolean}
         */
        if (this.autoFixed) {
            this.header.classList.add('b-header_position_fixed');
            this.changePositionHeader();
        }
    }

    /**
     * Метод реализации появления/скрытия хэдера
     */
    changePositionHeader() {
        // шаг времени задержки отрабатывания функции скрытия хэдера
        const stepScrollTop = -5;
        // шаг времени задержки отрабатывания функции отображения хэдера
        const stepScrollBottom = 5;
        const heightHeader = this.header.offsetHeight;
        // текущая позиция скролла
        let scrollPosition = 0;
        const minScrollPosition = 0;

        document.addEventListener('scroll', () => {
            /**
             * Отключение метода если меню открыто
             */
            if (this.menu.classList.contains('b-header__mobile-menu_state_open')) {
                return;
            }

            const scrollTopPosition = window.pageYOffset;

            /**
             * Cкролл вниз при условии, что прошла половина высоты хэдера
             */
            if ((scrollTopPosition > heightHeader) && (scrollTopPosition - scrollPosition) > stepScrollBottom) {
                this.header.classList.add('b-header_position_fixed');
                this.header.classList.add('b-header_position_hide');
            }

            /**
             * Cкролл вверх
             */
            if ((scrollTopPosition - scrollPosition) < stepScrollTop) {
                this.header.classList.remove('b-header_position_hide');
            }

            /**
             * Отрицательный скролл (прокрутка вверх, находясь при этом в самой верхней точке)
             */
            if (scrollTopPosition < minScrollPosition) {
                this.header.classList.remove('b-header_position_fixed');
            }

            scrollPosition = scrollTopPosition;
        });
    }

    /**
     * Метод открытия меню
     */
    openMenu() {
        this.scrollPosition = window.pageYOffset;
        this.menu.classList.add('b-header__mobile-menu_state_open');
        this.changePositionCloseButton();
        const defaultSpeed = 300;

        setTimeout(() => {
            this.body.classList.add('body-fixed');
        }, defaultSpeed);
        // this.setHeightMenu(); //!BUG
    }

    /**
     * Метод закрытия меню
     */
    closeMenu() {
        this.menu.classList.remove('b-header__mobile-menu_state_open');
        this.body.classList.remove('body-fixed');
        this.returnContentPosition();
    }

    /**
     * Метод возврата позиции контента при закрытии меню
     */
    returnContentPosition() {
        if (!this.returnPosition) {
            return;
        }
        const topScrollPosition = 0;

        window.scrollBy(topScrollPosition, this.scrollPosition);
    }

    /**
     * Метод позиционирования кнопки закрытия
     */
    changePositionCloseButton() {
        if (!this.autoPositionCloseButton) {
            return;
        }
        const positionOpenButtonTop = this.openButton.getBoundingClientRect().top;
        const positionOpenButtonLeft = this.openButton.getBoundingClientRect().left;

        this.closeButton.style.left = `${positionOpenButtonLeft}px`;
        this.closeButton.style.top = `${positionOpenButtonTop}px`;
    }

    /**
     * Метод установки высоты хэдеру
     */
    setHeightHeader() {
        this.headerParent.style.height = `${this.header.offsetHeight}px`;
    }

    /**
     * BUG! Метод установки высоты меню исходя из высоты видимой области экрана
     */
    setHeightMenu() {
        const heightScreen = document.documentElement.clientHeight;

        this.menu.style.height = `${heightScreen}px`;
    }
}

export default Header;
