import '../styles/app.scss';
import Select from '../../components/forms/select/';

/**
 * Добавляем класс на шапку при прокрутке.
 */
const getBodyScrollTop = function() {
    const header = document.querySelector('.j-home__header');
    const yOffset = self.pageYOffset;
    const maxYOffset = 700;
    const offset = yOffset ||
        (document.documentElement && document.documentElement.scrollTop) ||
        (document.body && document.body.scrollTop);

    if (offset > maxYOffset) {
        header.classList.add('is-scroll');
    } else {
        header.classList.remove('is-scroll');
    }
};

window.addEventListener('scroll', getBodyScrollTop);

/**
 * На главном экране появляется темная подложка на видео при прокрутке.
 */

const homeMainScreen = document.querySelector('.j-home__main-screen');

if (homeMainScreen) {
    const getOverlayScrollTop = function() {
        const mainScreen = document.querySelector('.j-home__main-screen');
        const yOffset = self.pageYOffset;
        const maxYOffset = 100;
        const offset = yOffset ||
            (document.documentElement && document.documentElement.scrollTop) ||
            (document.body && document.body.scrollTop);

        if (offset > maxYOffset) {
            mainScreen.classList.add('is-active-overlay');
        } else {
            mainScreen.classList.remove('is-active-overlay');
        }
    };

    window.addEventListener('scroll', getOverlayScrollTop);
}

/**
 * Клик по бургеру.
 * Открытие и закрытие меню.
 */

const openMenu = function() {
    const burger = document.querySelector('.j-burger-click');
    const menu = document.querySelector('.j-mobile-menu');
    const close = document.querySelector('.j-close-menu');


    burger.addEventListener('click', (event) => {
        event.preventDefault();
        menu.classList.add('is-open');
    });

    close.addEventListener('click', (event) => {
        event.preventDefault();
        menu.classList.remove('is-open');
    });
};

openMenu();

/**
 *  Когда пользователь авторизовался - запрещаем переход по ссылке личного кабинета.
 *  Просто показывается тултип с меню.
 */

const authBlock = document.querySelector('.j-account-auth');

if (authBlock) {
    const auth = function() {
        const isAuth = document.querySelector('.j-account-auth');

        isAuth.addEventListener('click', (event) => {
            event.preventDefault();
        });
    };

    auth();
}


/**
 * Обычный селект
 */

const selectDomItem = document.querySelector('.j-select');

if (selectDomItem) {
    const select = new Select({
        element: '.j-select',

        disableSearch: true
    });

    select.init();
}
