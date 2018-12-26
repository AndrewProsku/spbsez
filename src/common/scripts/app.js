import '../styles/app.scss';
import Anchor from '../../components/anchor-scroll';
import AnimatedLines from 'components/animation-line/index';
import Authorization from '../../components/authorization';
import Glide from '@glidejs/glide';
import InputTel from '../../components/forms/telephone/telephone';
import Logout from 'components/logout';
import NewPassword from '../../components/new-password';
import PasswordRecovery from '../../components/password-recovery';
import ProfileAdministrators from '../../components/profile-administrators';
import ProfileDocs from '../../components/profile-docs';
import ProfileInfo from '../../components/profile-info';
import Select from '../../components/forms/select/';
import Utils from './utils';
import YandexMap from 'components/yandex-map';
import yandexMapLoad from 'components/yandex-map/load';

/**
 * Полифилл метода closest()
 */
/* eslint-disable */
if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector ||
        Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        let el = this;

        if (!document.documentElement.contains(el)) {
            return null;
        }
        do {
            if (el.matches(s)) {
                return el;
            }
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);

        return null;
    };
}
/* eslint-enable */

/**
 * Добавляем класс на шапку при прокрутке.
 */
const getBodyScrollTop = function() {
    const header = document.querySelector('.j-home__header');
    const yOffset = self.pageYOffset;
    const maxYOffset = 600;
    const mainScreenText = document.querySelector('.b-main-screen-content');
    const windowHeight = document.documentElement.clientHeight;
    const offset = yOffset ||
        (document.documentElement && document.documentElement.scrollTop) ||
        (document.body && document.body.scrollTop);

    if (mainScreenText) {
        const isTextVisible = (-mainScreenText.getBoundingClientRect().top + header.clientHeight) < windowHeight;

        if (offset > maxYOffset || !isTextVisible) {
            header.classList.add('is-scroll');
        } else {
            header.classList.remove('is-scroll');
        }
    } else if (offset > maxYOffset) {
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
        document.body.classList.add('is-scroll-disabled');
    });

    close.addEventListener('click', (event) => {
        event.preventDefault();
        menu.classList.remove('is-open');
        document.body.classList.remove('is-scroll-disabled');
    });
};

openMenu();

/**
 *  Когда пользователь авторизовался - запрещаем переход по ссылке личного кабинета. (Для мобильной версии)
 *  Просто показывается тултип с меню.
 */

const authBlock = document.querySelector('.j-account-auth');

if (authBlock) {
    const auth = function() {
        const authLink = authBlock.querySelector('.b-account__link.is-auth');

        if (authLink) {
            authLink.addEventListener('click', (event) => {
                event.preventDefault();
                authBlock.classList.toggle('b-account_is_hover');
            });
        }
    };

    auth();
}

/**
 * Подключение маски телефона
 */
const phoneInputs = Array.from(document.querySelectorAll('input[type="tel"]:not(.b-input-phone)'));
let inputTel = {};

if (phoneInputs.length) {
    inputTel = new InputTel();

    inputTel.init({input: phoneInputs});
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


/**
 * Открытие и закрытие подменю в мобильной навигации.
 */
const openSubmenu = function() {
    const navHeaders = Array.from(document.querySelectorAll('.j-mobile-menu-accordion'));

    navHeaders.forEach((element) => {
        element.addEventListener('click', (event) => {
            const laptopWidth = 670;

            if (document.documentElement.clientWidth < laptopWidth) {
                event.preventDefault();
                element.classList.toggle('is-open');

                navHeaders.forEach((header) => {
                    if (header !== element) {
                        header.classList.remove('is-open');
                    }
                });
            }
        });
    });
};

openSubmenu();

/**
 * Открытие и закрытие в документах кнопки удалить.
 */

const docWrap = Array.from(document.querySelectorAll('.j-profile-document__item'));

if (docWrap.length) {
    docWrap.forEach((element) => {
        const delDocWrap = element.querySelector('.j-profile-document__item-delete');
        const delDoc = element.querySelector('.j-delete-doc-button');

        delDoc.addEventListener('click', (event) => {
            event.preventDefault();

            element.classList.toggle('is-active-item');
            delDocWrap.classList.toggle('is-active-delete');

            delDoc.classList.toggle('is-delete');
        });
    });
}

/* eslint-disable */
const mapWrapper = document.querySelector('.j-yandex-map');

if (mapWrapper) {
    yandexMapLoad()
        .then((ymaps) => {
            const yandexMap = new YandexMap(ymaps);

            yandexMap.init({wrapper: mapWrapper});
        })
        .catch((error) => {
            console.error(`При загрузке яндекс карт произошла ошибка: ${error}`);
        });
}
/* eslint-enable */


/**
 * Добавляем слайдеры на главной странице.
 */

const defaultCarouselSettings = {
    type       : 'carousel',
    startAt    : 0,
    perView    : 3,
    gap        : 0,
    breakpoints: {
        1279: {
            perView: 2
        },
        668: {
            perView: 1
        }
    },
    classes: {
        activeNav  : 'b-carousel__dot_is_active',
        activeSlide: 'b-carousel__slide_is_active'
    }
};


const residentsCarouselEl = document.querySelector('.j-residents-carousel');

if (residentsCarouselEl) {
    const residentsCarousel = new Glide('.j-residents-carousel', defaultCarouselSettings);

    residentsCarousel.on('swipe.start', () => {
        const carouselActiveSlide = residentsCarouselEl.querySelector('.b-carousel__slide_is_active');

        carouselActiveSlide.classList.add('b-carousel__slide_is_swiping');
    });
    residentsCarousel.on('swipe.end', () => {
        const carouselActiveSlide = residentsCarouselEl.querySelector('.b-carousel__slide_is_active');

        carouselActiveSlide.classList.remove('b-carousel__slide_is_swiping');
    });

    residentsCarousel.mount();
}


const partnersCarouselEl = document.querySelector('.j-partners-carousel');

if (partnersCarouselEl) {
    const partnersCarousel = new Glide('.j-partners-carousel', defaultCarouselSettings);

    partnersCarousel.on('swipe.start', () => {
        const carouselActiveSlide = partnersCarouselEl.querySelector('.b-carousel__slide_is_active');

        carouselActiveSlide.classList.add('b-carousel__slide_is_swiping');
    });
    partnersCarousel.on('swipe.end', () => {
        const carouselActiveSlide = partnersCarouselEl.querySelector('.b-carousel__slide_is_active');

        carouselActiveSlide.classList.remove('b-carousel__slide_is_swiping');
    });

    partnersCarousel.mount();
}


const reviewsCarouselEl = document.querySelector('.j-reviews-carousel');

if (reviewsCarouselEl) {
    const reviewsCarousel = new Glide('.j-reviews-carousel', {
        type   : 'carousel',
        startAt: 0,
        perView: 1,
        gap    : 0,
        classes: {
            activeNav: 'b-slider-reviews__dot_is_active'
        }
    });

    reviewsCarousel.mount();
}


/**
 * Добавляем класс для контена главного экрана,
 * чтобы увеличить значение отступа на величну панелей управления в мобильных браузерах
 */
const mainScreenContent = document.querySelector('.b-main-screen-content');

if (mainScreenContent) {
    if (Utils.isMobile()) {
        mainScreenContent.classList.add('b-main-screen-content_is_mobile');
    }
}

/**
 * Добавляем анимацию фона при скролле главной страницы,
 */

const bgAnimationLines = document.querySelector('.j-animation-block');

if (bgAnimationLines && homeMainScreen) {
    const animatedLines = new AnimatedLines(bgAnimationLines, homeMainScreen);

    animatedLines.init();
}

/**
 * На добавленной анимации фона вешаем класс
 */

const animationBg = document.querySelector('.j-animation-block');

if (animationBg) {
    const animationBlock = document.querySelector('.j-animation-block');
    const addClass = 5000;
    const removeClass = 3000;

    const animationBlockDeleteClass = () => {
        animationBlock.classList.remove('go-animation');
    };

    const animationBlockAddClass = () => {
        animationBlock.classList.add('go-animation');
        setTimeout(animationBlockDeleteClass, removeClass);
    };

    setInterval(animationBlockAddClass, addClass);
}

/**
 * Скролл к якорю
 */
const anchorSelector = Array.from(document.querySelectorAll('.j-anchor-link'));

if (anchorSelector.length) {
    anchorSelector.forEach((item) => {
        const anchor = new Anchor();

        anchor.init({
            target: item
        });
    });
}

/**
 * Инициализация формы авторизации
 */
const authorizationForm = document.querySelector('.j-form-authorization');

if (authorizationForm) {
    const authorization = new Authorization();

    authorization.init();
}


/**
 * Инициализация формы восстановления пароля
 */
const recoveryForm = document.querySelector('.j-form-password-recovery');

if (recoveryForm) {
    const passwordRecovery = new PasswordRecovery();

    passwordRecovery.init();
}


/**
 * Инициализация формы ввода нового пароля
 */
const newPasswordForm = document.querySelector('.j-form-new-password');

if (newPasswordForm) {
    const newPassword = new NewPassword();

    newPassword.init();
}

/**
 * Инициализация личных данных пользователя
 */
const profileInfoForm = document.querySelector('.j-profile-info');

if (profileInfoForm) {
    const profileInfo = new ProfileInfo();

    profileInfo.init();
}

/**
 * Инициализация кноки выхода из личного кабинета
 */
const logoutButton = document.querySelector('.j-logout');

if (logoutButton) {
    const logout = new Logout();

    logout.init({button: logoutButton});
}

/**
 * Инициализация формы загрузки документов
 */
if (document.querySelector(ProfileDocs.selector)) {
    (new ProfileDocs()).init();
}

/**
 * Инициализация страницы Администраторы
 */
const administrators = document.querySelector('.j-profile-administrators');

if (administrators) {
    const profileAdministrators = new ProfileAdministrators();

    profileAdministrators.init();
}
