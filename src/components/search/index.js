import deliveryTemplate from './templates/delivery-city-result-tmpl.twig';
import errorTemplate from './templates/search-error-tmpl.twig';
import Mediator from 'common/scripts/mediator';
import preloadTemplate from './templates/preloader-template.twig';
import resultTemplate from './templates/search-result-tmpl.twig';
import Utils from '../../common/scripts/utils';

class Search {
    constructor() {
        this.mediator = new Mediator();

        /**
         * Нода DOM-элемента поиска
         * @type {node}
         */
        this.search = {};

        /**
         * Булево значение, отвечающее отправлять запрос на серер или нет
         * @type {boolean}
         */
        this.isSend = false;

        /**
         * Класс модификатор поиска, добавляемый при выведении результатов
         * @type {string}
         */
        this.isSearch = ``;

        /**
         * Время задержки отправки запроса на сервер после последнего изменения в инпуте
         * @type {number}
         */
        this.debounce = 300;

        /**
         * Строка введенная юзером в инпут
         * @type {string}
         */
        this.inputValue = ``;

        /**
         * Номер элемента из списка с результатами в фокусе
         * @type {number}
         */
        this.resultFocus = null;

        this.hoverClass = 'is-hover';

        this.linkClass = '.j-search-link';

        this.minLettersDefault = 3;

        this.preloader = '.j-preloader';

        this.keys = {
            backspace: 8,
            tab      : 9,
            enter    : 13,
            up       : 38,
            down     : 40
        };

        /**
         * Таймер при наборе запроса
         * @type {boolean}
         */
        this.timer = false;
    }

    init(options) {
        this.search = options.search;
        this.isSearch = options.searchModify || `is-search`;
        this.searchInput = this.search.querySelector('input');
        this.debounce = options.debounce || this.debounce;
        this.minLetters = options.minLetters || this.minLettersDefault;
        this.ajaxUrl = options.ajaxUrl;
        this.resultWrap = this.search.querySelector('.j-globalSearch-result');
        this.template = resultTemplate;
        this.errorTemplate = errorTemplate;
        this.template = options.template === 'search' ? resultTemplate : deliveryTemplate;
        this.filling = options.fillingInput || false;
        this.linkTransition = options.linkTransition || false;
        this.closeResultClickLink = options.closeResultClickLink || false;

        this._bindEvents();
    }

    _bindEvents() {
        this.searchInput.addEventListener('keypress', this._changeInput.bind(this));
        this.searchInput.addEventListener('keydown', this._inputBlur.bind(this));
        this.searchInput.addEventListener('keydown', this._changeResult.bind(this));

        this.searchInput.addEventListener('input', this._bindEventsMobile.bind(this));
    }

    _bindEventsMobile(event) {
        this._changeInput(event);
    }

    /**
     * Отслеживаем изменения в инпуте
     * @private
     */
    _changeInput() {
        const that = this;

        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            that._getValue();
            that._sendRequest();
        }, this.debounce);
    }

    _changeResult(event) {
        if (this.searchInput.value.length < this.minLetters) {
            this._hideResult();
            this._removeResult();
        }

        this._tabEvents(event);

        this._enterEvents(event);

        this._backspaceEvent(event);
    }

    /**
     * Отрабатываем событие нажатия на backspace внутри инпута
     * @param {event} event - событие
     * @private
     */
    _backspaceEvent(event) {
        const that = this;

        if (event.keyCode === this.keys.backspace) {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                that._getValue();
                that._sendRequest();
            }, this.debounce);
        }
    }

    /**
     * Отрабатываем событие нажатия на tab внутри инпута
     * @param {event} event - событие
     * @private
     */
    _tabEvents(event) {
        if (event.keyCode === this.keys.tab && this.resultWrap.firstElementChild) {
            // event.preventDefault();
            this._removeResult();
            this._hideResult();
        }
    }

    /**
     * Отрабатываем событие нажатия на enter внутри инпута
     * @param {event} event - событие
     * @private
     */
    _enterEvents(event) {
        if (event.keyCode !== this.keys.enter) {
            return;
        }

        if (this.resultWrap.firstElementChild && this.linkTransition) {
            const linksArray = Array.from(this.resultWrap.querySelectorAll(this.linkClass));
            const hrefLink = linksArray[this.resultFocus].getAttribute('href');

            window.location.href = hrefLink;

            return;
        }

        if (!this.closeResultClickLink && this.searchInput.value.length > this.minLetters) {
            event.preventDefault();
            window.location.href = `/catalog/search/?q=${this.searchInput.value}`;

            return;
        }

        if (this.resultWrap.firstElementChild) {
            event.preventDefault();
            const link = this.resultWrap.firstElementChild.querySelector(this.linkClass);

            this._fillingInput(link.innerText, link);
            this._removeResult();
            this._hideResult();
        }
    }

    /**
     * Забираем value инпута
     * @private
     */
    _getValue() {
        this.inputValue = this.searchInput.value;
    }

    /**
     * Подготавливаем запрос на сервер
     * @private
     */
    _sendRequest() {
        if (this.inputValue.length < this.minLetters) {
            this._hideResult();
            this._removeResult();

            return;
        }

        this._send(`${this.searchInput.name}=${this.inputValue}`);
    }

    /**
     * Перезаписываем в объекте с ответом от сервера поле NAME. Меняем строку на строку с доп разметкой для подсветки
     * совпадения с введенным пользователем значением
     */
    setResultString() {
        for (const item in this.searchData) {
            const obj = this.searchData[item];

            for (const result in obj) {
                if (result === 'items') {
                    obj[result].forEach((limk) => {
                        limk.NAME = this._getEntryString(limk.NAME);
                    });
                }
            }
        }
    }

    /**
     * Подготавливаем строку элемента выдачи. Делаем подстветку совпадающих символов строки
     * @param {string} str - строка, полученная с сервера
     * @returns {string} resultString - преобразованную строку str
     * @private
     */
    _getEntryString(str) {
        if (!str) {
            return str;
        }

        let resultString = str;
        const stepIndex = 1;
        const val = this.inputValue.toUpperCase();
        const inString = str.toUpperCase();

        if (inString.indexOf(val) + stepIndex) {
            const pointZero = 0;
            const pointOne = inString.indexOf(val);
            const accentLength = this.inputValue.length;
            const pointThree = pointOne + accentLength;

            resultString = `${resultString.substring(pointZero, pointOne)
            }<em>${resultString.substring(pointOne, pointThree)}</em>${resultString.substring(pointThree)}`;
        }

        return resultString;
    }

    /**
     * Обновляем результаты поисковой выдачи
     */
    refreshResult() {
        if (!this.searchData.length) {
            this._hideResult();

            return;
        }
        // обновляем вывод результатов поиска. Убиваем старые результаты, показываем новые
        this._removeResult();
        this._showResult();
    }

    /**
     * Визуалььно скрываем поисковую выдачу
     * @private
     */
    _hideResult() {
        if (this.resultWrap.classList.contains(this.isSearch)) {
            this.resultWrap.classList.remove(this.isSearch);
        }
    }

    /**
     * Показываем список с вариантами поиска. Добавляем класс, который делает его видимым
     * @private
     */
    _showResult() {
        this.resultWrap.classList.add(this.isSearch);
    }

    /**
     * Заполняем шаблоны, чтобы потом добавить в результат
     * @param {object} data - данные полученные с сервера
     * @returns {string}  - результат из шаблонов
     * @private
     */
    _addExtendResult(data) {
        let miniTemplate = '';
        let miniDocTemplate = '';

        for (const object in data) {
            const dataObj = data[object];

            for (const items in dataObj) {
                // для обычных результатов
                if (items === 'items') {
                    dataObj[items].forEach((item) => {
                        miniTemplate += this.template(item);
                    });
                    if (dataObj.linkMore) {
                        miniTemplate +=
                            `<li class="b-search__result-item">
                                <a href="${dataObj.linkMore}" 
                                class="b-search__result-text j-search-link" 
                                style="text-align: center">
                                    <span class="b-search__result-title">
                                        Посмотреть еще
                                    </span>
                                </a>
                            </li>`;
                    }
                }

                // для документов
                if (items === 'documents') {
                    dataObj[items].forEach((item) => {
                        miniDocTemplate += this.template(item);
                    });
                    if (dataObj.linkMore) {
                        miniDocTemplate +=
                            `<li class="b-search__result-item">
                                <a href="${dataObj.linkMore}" 
                                class="b-search__result-text j-search-link" 
                                style="text-align: center">
                                    <span class="b-search__result-title">
                                        Посмотреть еще
                                    </span>
                                </a>
                            </li>`;
                    }
                }
            }
        }
        miniTemplate = `<ul class="b-search__result-wrapp">Поиск по сайту:${miniTemplate}</ul>`;
        miniDocTemplate = `<br><hr class="b-search__line">
                            <ul class="b-search__result-wrapp">Поиск по документам:${miniDocTemplate}</ul>`;


        return miniTemplate + miniDocTemplate;
    }

    /**
     * Добавляем на страницу список с вариантами поиска. Навешиваем обработчики событий
     */
    addResult() {
        this.setResultString();
        // проверка на searchError, если слова в поиске не подходят
        if (this.searchData.searchError) {
            Utils.insetContent(this.resultWrap, this.errorTemplate(this.searchData));
        } else {
            const insertResult = this._addExtendResult(this.searchData);

            Utils.insetContent(this.resultWrap, insertResult);
        }
        this._initLinksEvents();
    }

    /**
     * Удаляем из DOM элементы списка
     * @private
     */
    _removeResult() {
        Utils.clearHtml(this.resultWrap);
    }

    /**
     * Отправляем данные на сервер
     * @param {String} data - данные для бэка
     */
    _send(data) {
        const that = this;

        this._showPreloader();

        Utils.send(data,
            that.ajaxUrl,
            {
                success(req) {
                    // Скрываем прелоадер
                    that._hidePreloader();

                    that.searchData = req.data;

                    that.refreshResult();
                    that.addResult();
                },
                error(err) {
                    console.error(`ошибка на сервере: ${err}`);
                }
            },
            'POST');

        // Utils.send(data, '/tests/globalSearch.json',
        //     {
        //         success(req) {
        //             // Скрываем прелоадер
        //             that._hidePreloader();
        //
        //             that.searchData = req.data;
        //             console.log(that.searchData);
        //
        //             that.refreshResult();
        //             that.addResult();
        //         },
        //         error(err) {
        //             console.error(`ошибка на сервере: ${err}`);
        //         }
        //     },
        //     'GET');
    }

    /**
     * убираем фокус с инпута и ставим фокус на первый элемент списка
     * @param {event} event - ловим событие на стрелке вниз находясь в инпуте. Меняем фокус на первый элемент списка
     * @private
     */
    _inputBlur(event) {
        const zeroIndex = 0;
        const linksArray = [].slice.call(this.resultWrap.querySelectorAll(this.linkClass));

        if (linksArray.length && event.keyCode === this.keys.down) {
            event.preventDefault();
            this.userValue = this.searchInput.value;
            linksArray[zeroIndex].focus();
            this._fillingInput(linksArray[zeroIndex].innerText, linksArray[zeroIndex]);

            this.resultFocus = zeroIndex;
        }
    }

    /**
     * устанавливаем события на ссылки в списке результатов
     * @private
     */
    _initLinksEvents() {
        const linksArray = Array.from(this.resultWrap.querySelectorAll(this.linkClass));

        if (!linksArray.length) {
            return;
        }

        linksArray.forEach((linkResult) => {
            linkResult.addEventListener('keydown', this._changeFocus.bind(this));
            linkResult.addEventListener('keydown', this._tabEvents.bind(this));

            if (this.closeResultClickLink) {
                linkResult.addEventListener('click', this._clickLink.bind(this));
            }
        });
    }

    _clickLink(event) {
        event.preventDefault();
        const link = event.target;

        this._fillingInput(link.innerText, link);
        this._hideResult();
        this._removeResult();
    }


    /**
     * Меняем фокус при клике на стрелки вверх и вниз
     * @param {event} event - событие keydown
     * @private
     */
    _changeFocus(event) {
        switch (event.keyCode) {
            case this.keys.up:
                event.preventDefault();
                this._prevFocus(event.currentTarget);
                break;
            case this.keys.down:
                event.preventDefault();
                this._nextFocus(event.currentTarget);
                break;
            default:
        }
    }

    /**
     * вешаем фокус на предыдущую ссылку
     * @param {node} link - ссылка на которой был фокус
     * @private
     */
    _prevFocus(link) {
        const step = 1;
        const resultItem = link.parentElement;

        if (!resultItem.previousElementSibling) {
            this.searchInput.focus();
            this.resultFocus = null;

            this._fillingInput(this.userValue);

            return;
        }

        const newLink = resultItem.previousElementSibling.firstElementChild;

        this.resultFocus = this.resultFocus - step;
        newLink.focus();
        this._fillingInput(newLink.innerText, newLink);
    }

    /**
     * вешаем фокус на следующую ссылку
     * @param {node} link - ссылка на которой был фокус
     * @private
     */
    _nextFocus(link) {
        const step = 1;
        const resultItem = link.parentElement;

        if (resultItem.nextElementSibling) {
            const newLink = resultItem.nextElementSibling.firstElementChild;

            this.resultFocus = this.resultFocus + step;
            newLink.focus();
            this._fillingInput(newLink.innerText, newLink);
        }
    }

    /**
     * Перезаписываем значение инпута, на то, что в ссылке из результата, попавшей в фокус
     * @param {string} str - значение текста внутри ссылки
     * @param {node} link - нода ссылки из которой берется значение str. Может быть undefined, если юзер не выбрал
     * вариант из пришедший с сервера или с сервера пришел пустой ответ
     * @private
     */
    _fillingInput(str, link) {
        if (!this.filling) {
            return;
        }
        this.searchInput.value = str;

        if (link) {
            const id = link.dataset.id;

            this.mediator.publish('searchInputChange', id);

            return;
        }

        this.mediator.publish('searchInputChange', false);
    }

    /**
     * Показываем прелоадер в инпуте
     */
    _showPreloader() {
        Utils.insetContent(this.searchInput.parentElement, preloadTemplate());
    }

    /**
     * Убираем прелоадер с инпута
     */
    _hidePreloader() {
        const preloader = this.searchInput.parentElement.querySelector(this.preloader);

        Utils.removeElement(preloader);
    }
}

export default Search;
