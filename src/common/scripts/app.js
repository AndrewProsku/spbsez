import '../styles/app.scss';
import Glide from '@glidejs/glide';
import InputTel from '../../components/forms/telephone/telephone';
import Select from '../../components/forms/select/';
import YandexMap from 'components/yandex-map';
import yandexMapLoad from 'components/yandex-map/load';


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
 *  Когда пользователь авторизовался - запрещаем переход по ссылке личного кабинета. (Для мобильной версии)
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
 * Подключение маски телефона
 */
const phoneInputs = Array.from(document.querySelectorAll('input[type="tel"]'));
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
