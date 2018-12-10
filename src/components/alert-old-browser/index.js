/**
 * @version 1.1
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/alert-old-browser.html documentation
 */

/**
 * DEPENDENCIES
 */
import './alert-old-browser.scss';
import {detect} from 'detect-browser';
import merge from 'lodash/merge';
import popupTemplate from './alert-old-browser.twig';


class AlertOldBrowser {
    init(options) {
        if (AlertOldBrowser.getLocalStorage()) {
            return;
        }

        this.setSettings(options);
        this.checkBrowserName();
    }

    setSettings(options) {
        const defaultBrowserSupport = [{
            name   : 'chrome',
            version: 22
        }, {
            name   : 'firefox',
            version: 28
        }, {
            name   : 'opera',
            version: 12
        }, {
            name   : 'ie',
            version: 11
        }, {
            name   : 'safari',
            version: 8
        }, {
            name   : 'android',
            version: 5
        }, {
            name   : 'ios',
            version: 10
        }];

        this.supportBrowsers = merge(defaultBrowserSupport, options);
        this.userBrowser = detect();
        this.userBrowser.version = parseInt(this.userBrowser.version, 10);
        this.body = document.body;
        this.popup = null;
        this.closeButton = null;
    }

    /**
     * Вешаем слушателей событий
     */
    bindEvents() {
        const that = this;

        this.closeButton.addEventListener('click', this.removePopup.bind(this));
        this.ignoreButton.addEventListener('click', this.removePopup.bind(this));
        this.popup.addEventListener('click', (element) => {
            if (element.target === that.popup) {
                that.removePopup();
            }
        });
    }

    /**
     * Определение имени браузера
     */
    checkBrowserName() {
        this.supportBrowsers.forEach((item) => {
            if (this.userBrowser.name === item.name) {
                this.checkBrowserVersion(item);
            }
        });
    }

    /**
     * Определение версии браузера
     * @param {Object} supportBrowser -Сопоставленая версия браузера из настроек.
     */
    checkBrowserVersion(supportBrowser) {
        if (this.userBrowser.version < supportBrowser.version) {
            this.showPopup();
        }
    }

    /**
     * Получение элементов окна
     */
    getElements() {
        this.popup = this.body.querySelector('.b-alert-old-browser');
        this.closeButton = this.body.querySelector('.b-alert-old-browser__close');
        this.ignoreButton = this.body.querySelector('.b-alert-old-browser__ignore-button');
        this.popup.classList.remove('is-hidden');
    }

    /**
     * Открытие попапа
     */
    showPopup() {
        this.body.insertAdjacentHTML('afterbegin', popupTemplate());
        this.getElements();
        this.bindEvents();
        AlertOldBrowser.setLocalStorage();
    }

    /**
     * Удаление попапа
     */
    removePopup() {
        this.body.removeChild(this.popup);
    }

    /**
     * Отмечаем в localstorage, что плашка уже опказывалась
     */
    static setLocalStorage() {
        localStorage.setItem('shown', true);
    }

    /**
     * Чтение ключа из localstorage
     * @returns {Boolean} true|false - показана ли уже плашка или нет
     */
    static getLocalStorage() {
        return localStorage.getItem('shown');
    }
}

export default AlertOldBrowser;

