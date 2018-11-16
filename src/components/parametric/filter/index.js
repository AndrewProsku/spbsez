/**
 * @version 2.0
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/parametric/parametric-filter.html
 */
/* временно */
/* eslint-disable */

import './preloader/preloader.scss';
import './filter.scss';
import filterTemplate from './filter.twig';
import preloaderTemplate from './preloader/preloader.twig';
import Utils from 'common/scripts/utils';

let target = null;
let preloader = null;
let filter = null;
let inputs = null;
let data = null;
let template = null;


/**
 * Создаёт шаблон прелоадера
 * @return {HTMLElement} html - возращает html щаблона
 */

/**
 * Создаёт шаблон прелоадера
 * @param {Object} data - данные для шаблона
 * @return {HTMLElement} html - возращает html щаблона
 */
const createPreloader = function(data = {}) {
    return preloaderTemplate(data);
};

/**
 * Вставляет шаблон прелоадера на страницу и сохраняет прелоадер для дальнейшего обращения к нему
 * @param {Node} target - цель, то место куда будем вставлять шаблон
 * @param {HTMLElement} template - html шаблона
 */
const insertAndSavePreloader = function(target, template) {
    Utils.insetContent(target, template);
    preloader = target.querySelector('.b-preloader-parametric-filter');
};

/**
 * Удаляет прелоадер
 */
const removePreloader = function() {
    Utils.removeElement(preloader);
};

/**
 * Проверяет наличие urlArgs и относится ли он к фильтру
 * @return {boolean}
 */
const checkUrlArgs = function() {
    const hashes = window.location.search.replace('?', '')
        .split('&');

    return hashes.some(hash => {
        const [key] = hash.split('=');

        if (key === 'type') {
            return true;
        } else {
            return false;
        }
    });
};

/**
 * Отправляет запрос на сервер за значениями инпутов
 */
const send = function(succes = function() {
}, error = function() {
}) {
    Utils.send({}, '/tests/parametric.json', {
        success: function(data) {
            succes(data);
        },
        error  : function(err) {
            error(err);
        }
    });
};

/**
 * Создат шаблон фильтра
 * @param {Object} data - данные для генерации шаблона фильтра
 * @return {HTMLElement} - возрашает html шаблона
 */
const createTemplate = function(data = {}) {
    return filterTemplate(data);
};

/**
 * Вставляет шаблон фильтра на страницу
 * @param {Node} target - цель, то место куда будем вставлять шаблон
 * @param {HTMLElement} template - html шаблона
 */
const insertTemplateInDom = function(target, template) {
    Utils.insetContent(target, template);

    /**
     * надо сохранить HTMLElement фильтра в переменную, чтобы потом, можно было повесить обработчиков
     * `???` селектор созданный в twig шаблоне прелоадера
     */
    filter = target.querySelector('.b-parametric-filter');
    inputs = target.querySelectorAll('input');

};

/**
 * Фильтрует данные для шаблона фильтра
 * @param {Object} data - все данные параметрического выборщика
 * @return {Object} filterData - отфильтрованные данные для шаблона фильтра
 */
const filterDataForFilterTemplate = function(data) {
    let filterData = {
        minPrice  : 0,
        maxPrice  : 0,
        minArea   : 0,
        maxArea   : 0,
        minFloor  : 0,
        maxFloor  : 0,
        features  : null,
        floorTypes: null,
        rooms     : [],
        buildings : []
    };

    /**
     * забираем данные об особенностях квартир и типах этажей
     */
    filterData.features = data.features;
    filterData.floorTypes = data.floorTypes;

    /**
     * проходимся по всем данным и собираем нужную нам информацию
     */
    data.flats.forEach(value => {
        // создание массива с комнатностью
        const isRepeatedRoom = filterData.rooms.some(room => value.room === room);
        if (!isRepeatedRoom) {
            filterData.rooms.push(value.room);
        }

        // создание массива с корпусами
        const isRepeatedBuilding = filterData.buildings.some(building => value.building === building);
        if (!isRepeatedBuilding) {
            filterData.buildings.push(value.building);
        }

        switch (true) {
            // минимальная цена
            case (value.price < filterData.minPrice):
                filterData.minPrice = value.price;
                break;
            // максимальная цена
            case (value.price > filterData.maxPrice):
                filterData.maxPrice = value.price;
                break;
            // минимальная площадь
            case (value.totalArea < filterData.minArea):
                filterData.minArea = value.totalArea;
                break;
            // максимальная площадь
            case (value.totalArea < filterData.maxArea):
                filterData.maxArea = value.totalArea;
                break;
            // минимальный этаж
            case (value.floor < filterData.minFloor):
                filterData.minFloor = value.floor;
                break;
            // максимальный этаж
            case (value.floor < filterData.maxFloor):
                filterData.maxFloor = value.floor;
                break;
        }
    });

    // сортировка массива с комнатностью
    filterData.rooms = filterData.rooms.sort();
    // сортировка массива с корпусами
    filterData.buildings = filterData.buildings.sort();

    return filterData;
};

/**
 * Component
 */
class ParametricFilter {
    init(options) {
        target = options.target;

        if (checkUrlArgs()) {

        } else {
            insertAndSavePreloader(target, createPreloader());

            send(function succes(req) {
                data = req;
                insertTemplateInDom(target, createTemplate(filterDataForFilterTemplate(data)));
                removePreloader();
            });
        }


        // this.wrapper = options.element;
        // this.form = this.wrapper.querySelector('form');
        // this.inputs = this.wrapper.querySelectorAll('input');
    }

    /**
     * Привязывает обработчики событий к инпутам
     */
    bindEvents() {
        /**
         * События для инпутов
         */
        inputs.forEach(item => {
            /**
             * Событие изменения элемента. Срабатывает при `ENTER`.
             */
            item.addEventListener('change', element => {
            });

            /**
             * Событие ввода текста в элемент. Срабатывает при каждом вводе.
             */
            item.addEventListener('input', element => {
            });

            /**
             * Событие фокусировки на элемент. Срабатывает при клике на элемент.
             */
            item.addEventListener('focus', element => {
            });

            /**
             * Событие потери фокуса с элемента. Срабатывает когда происходит клик вне элемента.
             */
            item.addEventListener('blur', element => {
            });

            /**
             * Событие нажатия клавиши в элементе. Можно указывать любой keyCode.
             */
            item.addEventListener('keypress', element => {
                const keyCodeENTER = 13;

                if (keyCodeENTER === element.keyCode) {
                }
            });

            /**
             * Событие выделения текста в элементе, так же может вывести выделенный текст.
             */
            item.addEventListener('select', element => {
                const selectionText = item.value.substr(item.selectionStart, item.selectionEnd - item.selectionStart);
            });
        });

        /**
         * События для формы
         */

        /**
         * Событие отправки формы. Происходит при нажатии на кнопку type='submit'.
         */
        this.form.addEventListener('submit', element => {
            element.preventDefault();
        });

        /**
         * Событие сброса формы. Происходит при нажатии на кнопку type='reset'.
         */
        this.form.addEventListener('reset', () => {
        });
    }

    /**
     * Метод получает и сохраняет в объект данные из get запроса.
     * @return {object} this.urlParams - объект с данными. Ключ - имя инпута, значение - его значение.
     */
    getUrlParams() {
        const hashes = window.location.search.replace('?', '')
            .split('&');

        this.urlParams = {};

        hashes.map(hash => {
            const [key, val] = hash.split('=');

            this.urlParams[key] = decodeURIComponent(val);

            return this.urlParams;
        });

        return this.urlParams;
    }

    /**
     * Метод сопоставляет элементы формы и данные из this.urlParams. Если элемент присутствует, то устанавливает
     * значение.
     */
    fillUrlParams() {
        this.getUrlParams();
        const formElements = Array.from(this.form.elements);

        const that = this;

        formElements.forEach(formElement => {
            for (const key in that.urlParams) {
                if (formElement.name === key) {
                    formElement.setAttribute('value', that.urlParams[key]);

                    if (formElement.getAttribute('value') === 'on') {
                        formElement.setAttribute('checked', 'checked');
                    }
                }
            }
        });
    }
}

export default ParametricFilter;
/* временно */
/* eslint-enable */
