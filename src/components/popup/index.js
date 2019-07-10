/**
 * @version 1.1beta
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/popup.html documentation
 */

/**
 * DEPENDENCIES
 */
import {disableBodyScroll, enableBodyScroll} from 'body-scroll-lock';
import $ from 'jquery';
import Language from '../language';
import Mediator from 'common/scripts/mediator';
import template from 'components/popup/popup.twig';
import Utils from 'common/scripts/utils';
import Message from "components/message-popup";
import Service from "components/service-popup";
import servicePopupTemplate from "components/service-popup/service-popup.twig";

const Lang = new Language();

const mediator = new Mediator();

class Popup {
    init(options) {
        this.body = document.body;
        this.template = options.template || template;
        this.templateContent = options.templateContent || false;
        this.target = options.target;
        this.closeButtonAriaLabel = options.closeButtonAriaLabel || Lang.get('popup.closeAriaLabel');
        this.stateClass = 'b-popup_state_open';

        this.bindEvents();
    }

    /**
     * Вешаем слушателей событий
     */
    bindEvents() {
        if(this.target) {
            this.target.addEventListener('click', (event) => {
                event.preventDefault();
                this.id = this.target.dataset.href;
                this.slug = this.target.dataset.id || '';
                this.makeOpen();

                console.log(this);
            });
        }

        document.addEventListener('keyup', (element) => {
            this.closeOnPressButton(element);
        });

        mediator.subscribe('successRequest', (data) => {
            this.id = data.id;
            this.slug = '';

            this.makeOpen();
            console.log(this);
        });
    }

    /**
     * Байнд событий после создания popup.
     */
    bindEventsAfterOpen() {
        this.closeButton.addEventListener('click', this.close.bind(this));
        this.popup.addEventListener('click', (element) => {
            this.closeOnClickOverlay(element);
        });
    }

    /**
     * Добавляет body position:fixed, дабы избежать прокрутки страницы при открытом попапе.
     * Когда-нибудь перенесем в Mediator.
     */
    fixedBody() {
        this.body.classList.toggle('body-fixed');
    }

    /**
     * Запуск попапа при клике на кнопку.
     */
    makeOpen() {
        this.openPopup = this.body.querySelector(`[data-popup="${this.id}${this.slug}"]`);

        if (this.openPopup) {
            this.popup = this.openPopup;
            this.open();
        } else {
            this.contentTypeCheck();
        }
    }

    /**
     * Проверка типа попапа - статика/ajax.
     */
    contentTypeCheck() {
        const dataAttributeHref = this.target.dataset.href;
        const dataAttributeAjax = this.target.dataset.ajax;
        const dataAttributeAjaxData = $.param(this.target.dataset);
        let dataAttributeJson = this.target.dataset.json;

        if (dataAttributeJson) {
            dataAttributeJson = atob(dataAttributeJson);
            dataAttributeJson = JSON.parse(dataAttributeJson);
        }

        if (dataAttributeHref) {
            const contentId = document.getElementById(dataAttributeHref);

            if (!contentId) {
                console.error(`Создай контентный блок попапа с id "${dataAttributeHref}"`);

                return;
            }

            const staticContent = contentId.innerHTML;

            this.outputOnDisplay(staticContent);
            this.open();
        } else if (dataAttributeAjax) {
            this.outputOnDisplay();
            this.send(dataAttributeAjaxData, this.open.bind(this));
        } else if (dataAttributeJson) {
            this.outputOnDisplay();
            Popup.insetContent(this.content, this.templateContent(dataAttributeJson));
            this.open();
        }
    }

    /**
     * Вывод попапа на экран.
     * @param {String} content - контент из статики или из json.
     */
    outputOnDisplay(content) {
        this.setData(content);
        this.insertTemplate();
        this.getElements();
    }

    /**
     * Подготовка данных для шаблона
     * @param {String} content - контент из статики или из json.
     */
    setData(content) {
        this.data = {
            content,
            id             : this.id,
            slug           : this.slug,
            buttonAriaLabel: this.closeButtonAriaLabel
        };
    }

    /**
     * Вставка шаблона.
     */
    insertTemplate() {
        this.body.insertAdjacentHTML('afterbegin', this.template(this.data));
    }

    /**
     * Получаем попап и его элементы, вешаем события.
     */
    getElements() {
        this.popup = document.querySelector('.b-popup');
        this.content = document.querySelector('.b-popup__content');
        this.closeButton = this.popup.querySelector('.b-popup__close');
        this.overlay = this.popup.querySelector('.b-popup__overlay');
        this.bindEventsAfterOpen();
    }

    /**
     * Вставка контента
     * @param {String} popup - элемент, куда вставляется контент. (не сам попап, а его контентная часть!)
     * @param {String} content - вставляемый контент.
     */
    static insetContent(popup, content) {
        Utils.clearHtml(popup);
        Utils.insetContent(popup, content);
    }

    /**
     * Метод отправки ajax запроса
     * @param {Object} sentData - отправляемые данные.
     * @param {Function} complete - функция открытия попапа
     * @return {Boolean} - false - если нет интернета, запрос прерывается.
     * @return {Boolean} - true - успешное выполнение запроса.
     */
    send(sentData, complete = function() {}) {
        if (!this.checkInternet()) {
            this.resending(sentData);

            return false;
        }

        const that = this;
        const url = this.target.dataset.ajax;
        const callback = {
            success(req) {
                if (req.request.result) {
                    if (that.templateContent) {
                        Popup.insetContent(that.content, that.templateContent(req.data));
                    } else {
                        Popup.insetContent(that.content, req.data.content);
                    }

                    complete();
                } else {
                    that.errorHandler('data');
                    console.error(req.request.errors);
                }
            },

            error(err) {
                that.errorHandler('server');
                console.error(`server error ${err}`);
            }
        };

        Utils.send(sentData, url, callback, 'get');

        return true;
    }

    /**
     * Метод закрытия попапа по клику на оверлей.
     * @param {Object} element - элемент по которому произошло событие. Cм. метод bindEventsAfterOpen
     */
    closeOnClickOverlay(element) {
        if (element.target === this.overlay) {
            this.close();
        }
    }

    /**
     * Метод закрытия попапа по нажатию на ESC
     * @param {Object} element - элемент по которому произошло событие. Cм. метод bindEvents
     */
    closeOnPressButton(element) {
        const keyCodeESC = 27;

        if (element.keyCode === keyCodeESC) {
            this.close();
        }
    }

    /**
     * Метод открывает попап
     */
    open() {
        this.popup.classList.add(this.stateClass);
        this.fixedBody();

        disableBodyScroll(this.popup);

        mediator.publish('openPopup', this);

        if (!this.openPopup) {
            mediator.publish('openPopupFirst', this);
        }
    }

    /**
     * Метод закрывает попап
     */
    close() {
        this.popup.classList.remove(this.stateClass);

        if (!this.popup.dataset.popup) {
            this.remove();
        }

        this.fixedBody();

        enableBodyScroll(this.popup);

        mediator.publish('closePopup', this);
    }

    /**
     * Удаление попапа со страницы.
     */
    remove() {
        Utils.removeElement(this.popup);
    }

    /**
     * Метод обработки ошибок.
     * @param {String} typeError - тип ошибки.
     */
    errorHandler(typeError) {
        const errorList = [{
            type: 'internet',
            html: `<h1>${Lang.get('popup.errors.internet')}</h1>`
        }, {
            type: 'server',
            html: `<h1>${Lang.get('popup.errors.server')}</h1>`
        }, {
            type: 'data',
            html: `<h1>${Lang.get('popup.errors.data')}</h1>`
        }];

        errorList.forEach((item) => {
            if (typeError === item.type) {
                Popup.insetContent(this.content, item.html);
            }
        });
    }

    /**
     * Проверка на наличие интернета.
     * @return {boolean} true - интернет есть.
     * @return {boolean} false - интернетa нет.
     */
    checkInternet() {
        clearInterval(this.setInterval);
        if (!Utils.checkInternetConnection()) {
            this.errorHandler('internet');

            return false;
        }

        return true;
    }

    /**
     * Повторный запрос на сервер.
     * @param {object} sentData - отрправляемые данные.
     *
     */
    resending(sentData) {
        const interval = 5000;
        const that = this;

        this.setInterval = setInterval(() => {
            that.send(sentData);
        }, interval);
    }
}

export default Popup;
