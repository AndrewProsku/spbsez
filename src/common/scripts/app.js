import Accordion from 'components/accordion';
import Anchor from '../../components/anchor-scroll';
import AnimatedLines from 'components/animation-line/index';
import Authorization from '../../components/authorization';
import Disclosure from '../../components/disclosure/disclosure';
import Glide from '@glidejs/glide';
import GlideCarousel from '../../components/glide-carousel';
import InputTel from '../../components/forms/telephone/telephone';
import Logout from 'components/logout';
import Mediator from 'common/scripts/mediator';
import Message from '../../components/message-popup';
import messagePopupTemplate from '../../components/message-popup/message-popup.twig';
import More from 'components/more';
import NewPassword from '../../components/new-password';
import News from '../../components/news';
import Particles from '../../components/background-animation';
import PasswordRecovery from '../../components/password-recovery';
import Popup from 'components/popup';
import ProfileAdministrators from '../../components/profile-administrators';
import ProfileDocs from '../../components/profile-docs';
import ProfileInfo from '../../components/profile-info';
import ProfileRequestPass from '../../components/request-pass';
import ReportForm from '../../components/reports/reports-form';
import Residents from '../../components/residents/';
import reviewPopupContentTemplate from '../../components/popup/review-popup-content.twig';
import reviewPopupTemplate from '../../components/popup/review-popup.twig';
import Select from '../../components/forms/select/';
import Service from '../../components/service-popup';
import servicePopupTemplate from '../../components/service-popup/service-popup.twig';
import TabsAjax from 'components/tabs/tabs-ajax';
import templateMessages from 'components/messages/messages.twig';
import Useful from '../../components/useful';
import vacanciesPopupTemplate from '../../components/popup/popup-vacancies.twig';
import Vacancy from '../../components/vacancy';
import YandexMap from 'components/yandex-map';
import yandexMapLoad from 'components/yandex-map/load';

const mediator = new Mediator();

const particlesNode = document.querySelector('.j-particles');

if (particlesNode) {
    new Particles(particlesNode).init();
}

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
        const x = window.scrollX;
        const y = window.scrollY;

        window.onscroll = function winScroll() {
            window.scrollTo(x, y);
        };
    });

    close.addEventListener('click', (event) => {
        event.preventDefault();
        menu.classList.remove('is-open');
        document.body.classList.remove('is-scroll-disabled');
        window.onscroll = function winScroll() {};
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
    // Селект для сообщений инциализируется отдельно
    if (!selectDomItem.closest('.j-messages-select') &&
        !selectDomItem.closest('.b-service-popup')) {
        const select = new Select({
            element: '.j-select',

            disableSearch: true
        });

        select.init();
    }
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
    yandexMapLoad(mapWrapper.dataset.lang || 'ru')
        .then((ymaps) => {
            (new YandexMap(ymaps)).init({wrapper: mapWrapper});
        })
        .catch((error) => {
            console.error(`При загрузке яндекс карт произошла ошибка: ${error}`);
        });
}

const noidorfMapWrapper = document.querySelector('.j-yandex-map-noidorf');

if (noidorfMapWrapper) {
    yandexMapLoad(noidorfMapWrapper.dataset.lang || 'ru')
        .then((ymaps) => {
            (new YandexMap(ymaps)).init({wrapper: noidorfMapWrapper, zoom: 12});
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

const newsCarouselEl = document.querySelector('.j-news-slider');
const areaCarouselEl = document.querySelector('.j-area-slider');

if (newsCarouselEl || areaCarouselEl) {
    const sliderClass = newsCarouselEl ? '.j-news-slider' : '.j-area-slider';

    const glideInstance = new Glide(sliderClass, {
        type   : 'carousel',
        startAt: 0,
        perView: 1,
        gap    : 0,
        classes: {
            activeNav: 'glide__cust-dot_is_active'
        }
    });
    const glideCarousel = new GlideCarousel();

    glideCarousel.init();

    glideInstance.mount();
    glideInstance.on('move.after', () => {
        glideCarousel.setControlsPosition();
    });
}


/**
 * Добавляем класс для контена главного экрана,
 * чтобы увеличить значение отступа на величну панелей управления в мобильных браузерах
 * @param {node} mainScreenContent - элемент с текстовым контентом главного экрана
 */
const fixMainScreenHeight = function(mainScreenContent) {
    const innerHeightWindow = window.innerHeight;

    mainScreenContent.style.paddingTop = `${innerHeightWindow}px`;

    window.addEventListener('resize', () => {
        const resizeWindowHeight = window.innerHeight;

        mainScreenContent.style.paddingTop = `${resizeWindowHeight}px`;
    });
};

const homeMainScreenContent = document.querySelector('.b-main-screen-content');
const residentPageTitle = document.querySelector('.b-area-main-screen__title');
const areaMainScreen = document.querySelector('.j-area-main-screen');

if (homeMainScreenContent) {
    fixMainScreenHeight(homeMainScreenContent);
}

if (residentPageTitle) {
    fixMainScreenHeight(residentPageTitle);
}

/**
 * Добавляем анимацию фона при скролле главной страницы,
 */

const bgAnimationLines = document.querySelector('.j-animation-block');

if (bgAnimationLines && homeMainScreen) {
    const animatedLines = new AnimatedLines(bgAnimationLines, homeMainScreen);

    animatedLines.init();
}

if (bgAnimationLines && areaMainScreen) {
    const animatedLines = new AnimatedLines(bgAnimationLines, areaMainScreen);

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
    new Anchor().init({
        targets: anchorSelector
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

/**
 * Инициализация страницы Заявка на пропуск
 */
const requestPass = document.querySelector('.j-request-pass');

if (requestPass) {
    const profileRequestPass = new ProfileRequestPass();

    profileRequestPass.init();
}

/**
 * Инициализация выпадающего меню (страница резиденты)
 */
const residentsBlock = document.querySelector('.j-residents-page');

if (residentsBlock) {
    const residents = new Residents();

    residents.init();
}

/**
 * Для картинок мобильных карт - устанавливаем позицию скролла
 */

const blockMap = document.querySelector('.j-home-plots');

if (blockMap) {
    const novoorlovskay = document.querySelector('.l-home-plots__map-novoorlovskaya');
    const neudorf = document.querySelector('.l-home-plots__map-neudorf');

    novoorlovskay.scrollLeft += 220;
    neudorf.scrollTop += 150;
}

/**
 * Инициализация аккордиона вакансий
 */

const vacancies = Array.from(document.querySelectorAll('.j-vacancy'));

if (vacancies.length) {
    vacancies.forEach((vacancy) => {
        const accordion = new Accordion();

        accordion.init({
            target             : vacancy,
            activeClass        : 'b-vacancy_is_open',
            headerClass        : 'b-vacancy__header',
            contentWrapperClass: 'b-vacancy__content-wrapper',
            contentClass       : 'b-vacancy__content'
        });
    });
}

/**
 * Инициализация аккордиона вакансий
 */
const vacancyPopupButtons = Array.from(document.querySelectorAll('.j-vacancy-button'));

if (vacancyPopupButtons.length) {
    vacancyPopupButtons.forEach((button) => {
        const popup = new Popup();

        popup.init({
            target              : button,
            template            : vacanciesPopupTemplate,
            closeButtonAriaLabel: 'Закрыть'
        });
    });
}


mediator.subscribe('openPopup', (popup) => {
    if (popup.popup.classList.contains('b-popup_theme_vacancy')) {
        const vacancy = new Vacancy();

        vacancy.init({popup});
    }
});

/**
 * Инициализация попапа "Отзыв полностью" в футере
 */

const openPopupReview = Array.from(document.querySelectorAll('.j-popup-review'));

if (openPopupReview) {
    openPopupReview.forEach((reviewButton) => {
        const reviewPopup = new Popup();

        reviewPopup.init({
            target              : reviewButton,
            template            : reviewPopupTemplate,
            templateContent     : reviewPopupContentTemplate,
            closeButtonAriaLabel: 'Закрыть'
        });
    });
}

/**
 * Инициализация попапа "Написать сообщение" в футере
 */

const messageButtons = Array.from(document.querySelectorAll('.j-message-button'));

if (messageButtons) {
    messageButtons.forEach((messageButton) => {
        const messagePopup = new Popup();

        messagePopup.init({
            target              : messageButton,
            template            : messagePopupTemplate,
            closeButtonAriaLabel: 'Закрыть'
        });
    });
}

mediator.subscribe('openPopup', (popup) => {
    if (popup.popup.classList.contains('b-popup_theme_message')) {
        const message = new Message();

        message.init({popup});
    }
});

/**
 * Инициализация попапа для страницы услуг
 */

const servicePopupButtons = Array.from(document.querySelectorAll('.j-service-button'));

if (servicePopupButtons.length) {
    servicePopupButtons.forEach((button) => {
        const service = new Popup();

        service.init({
            target              : button,
            template            : servicePopupTemplate,
            closeButtonAriaLabel: 'Закрыть'
        });
    });
}

mediator.subscribe('openPopup', (popup) => {
    if (popup.popup.classList.contains('b-popup_theme_service')) {
        const service = new Service();

        service.init({popup});
    }
});


/**
 * Инициализация подгрузки сообщений от ОЭЗ
 */

const moreMessagesButton = document.querySelector('.j-more');
let moreMessages = null;

if (moreMessagesButton) {
    moreMessages = new More();

    moreMessages.init({
        button  : moreMessagesButton,
        content : document.querySelector('.j-messages'),
        template: templateMessages
    });
}


/**
 * Инициализация табов для сообщений от ОЭЗ
 */

const messagesTabs = document.querySelector('.j-messages-tabs');
const messagesSelectWrapper = document.querySelector('.j-messages-select');

if (messagesTabs && messagesSelectWrapper) {
    // Инициализируем мобильный селект
    const messagesSelect = new Select({
        element: '.j-messages-select .j-select',

        disableSearch: true
    });

    messagesSelect.init();

    // Инициализируем десктопные табы
    const tabs = new TabsAjax();
    const tabContent = document.querySelector('.j-messages');

    tabs.init({
        target  : messagesTabs,
        content : tabContent,
        template: templateMessages
    });

    mediator.subscribe('tabLoaded', (tab) => {
        if (moreMessages) {
            moreMessages.resetStep();
        }

        messagesSelectWrapper.querySelector(`select`).value = tab.dataset.year;
        messagesSelect.triggerUpdate();
    });

    mediator.subscribe('chosen-select-change', () => {
        const selectedValue = messagesSelectWrapper.querySelector('select').value;

        mediator.publish('tabSelected', {
            name : 'year',
            value: selectedValue
        });
    });
}


/**
 * Инициализация выпадающего меню для личного кабинета
 */

const accordionLinks = document.querySelector('.j-accordion-links');

if (accordionLinks) {
    const accordionLinksHeader = accordionLinks.querySelector('.j-accordion-links__header');
    const accordionLinksMobile = accordionLinks.querySelector('.j-accordion-links__mobile');

    accordionLinksHeader.addEventListener('click', () => {
        accordionLinksMobile.classList.toggle('is-open');
    });
}

/**
 *  Инициализация фильтрации и подгрузки новостей
 */
if (document.querySelector('.j-news-filter') || document.querySelector('.j-news-load-more')) {
    (new News()).init();
}

/**
 *  Инициализация фильтрации и подгрузки новостей
 */
if (document.querySelector('.j-useful-content')) {
    (new Useful()).init();
}

/**
 * Инициализация форм отчетов
 */

const reportFormEl = document.querySelector('.j-report-form');

if (reportFormEl) {
    (new ReportForm()).init({
        target: reportFormEl
    });
}


/**
 * Инициализация страниц "Раскрытие информации"
 */

const disclosureItems = Array.from(document.querySelectorAll('.j-disclosure-block'));

if (disclosureItems.length) {
    disclosureItems.forEach((target) => {
        (new Disclosure()).init({
            target
        });
    });
}


/**
 * Первый экран на страницах резидентов
 */

const residentMainScreen = document.querySelector('.j-area-main-screen');

if (residentMainScreen) {
    const getOverlayScrollTop = function() {
        const yOffset = self.pageYOffset;
        const maxYOffset = 100;
        const offset = yOffset ||
            (document.documentElement && document.documentElement.scrollTop) ||
            (document.body && document.body.scrollTop);

        if (offset > maxYOffset) {
            residentMainScreen.classList.add('is-active-overlay');
        } else {
            residentMainScreen.classList.remove('is-active-overlay');
        }
    };

    window.addEventListener('scroll', getOverlayScrollTop);
}

