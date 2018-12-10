/**
 * Этапы работы визуального
 *
 * Первая загрузка
 * 0. загружаем js
 * 1. Отправляем запрос на сервер
 * 2. и одновременно показываем лоадер
 * 3. генерим всю разметку
 * 4. после ответа загружаем картинку
 * 5. как только картинка загрузилась рендерим разметку
 *
 * Пошагово
 * 1. показываем лоадер
 * 2. загружаем картинку следующего шага
 * 3. паралельно удаляем разметку масок и остальных контролов если надо
 * 4. создаем новые маски и контролы
 * 5. как только загрузилась картинку предыдущего шага меняем на нынешнюю
 * 6. рендерим разметку
 *
 */

/**
 *
 * Работа с изображнием
 * 1. загрузка
 * 2. определние размеров
 * 3. добавление на svg
 * 4. смена
 *
 *
 * лоадер
 * +1. показать
 * +2. убрать
 *
 *
 * Маски
 * 1. Сбор масок для конкртеного вида
 * 2. перенос на svg
 * 3. Настройки
 * 3.1 ховер
 * 3.2 в зависимоти от комнатности
 * 3.2.1 если есть такая зависимость, то добавлять блок легенды
 * 3.3 Продана
 * 3.4 Забронирована
 *
 *
 * Тултипы
 * 1. Положение тултипа относительно курсора (слева, по центру, справа)
 * 2. Треугольник снизу - показать или удалить - стилями.
 * 3. Данные для тултипа
 * 4. Может переносить на следующий шаг
 * 5. Тултип не должен заезжать за экран, если маска находится слишком близко к концу  экрана
 *
 *
 * Поинтер
 * 1. При ховере на поинтер подсвечивается маска
 * 2. Данные для поинтера
 * 3. Треугольник снизу - показать или удалить - стилями.
 * 4. Переносит на следующий шаг
 * 5. Поинтер не должен заезжать за экран, если маска находится слишком близко к концу  экрана
 *
 *
 * Кнопка назад
 * 1. Может формироваться разные надписи - вернуться назад, вернуться на выбор дома/этажа/корпуса
 *
 *
 * Хлебные крошки
 * 1. Формирование хлебных крошек
 *
 *
 * Запрос на серве
 * 1. если ЖК маленький, то посылаем запрос и сохраняем в localStorage
 * 1.1. Сохраняем время последнего изменения и сравниваем при каждом обновлении
 * 2. определить размер и если < 5 Мб, то записывать в localStorage
 *
 *
 * Настройки выборщика
 * 1. Настройки по умолчанию
 * +1.2 fullscreen: true
 * +1.3 backLink: link || breadcrumbs || all
 */


/**
 * DEPENDENCIES
 */
// import throttle from 'lodash/throttle';

import './visual.scss';
import filter from './filter.twig';
import Loader from '../loader/index';
import parametricButton from './parametric-button.twig';
import rotateButton from './rotate-button.twig';


/**
 * всё что внутри сейчас, это на самом деле приватные методы
 * надо бы вынести, т.к. внутри должны быть публичные методы
 */
class Visual {
    /**
     * может прийти просто элемент или объекс с настройками
     * надо уметь обрабатывать такие ситуации
     * @param {Object} options - visual outer options
     */
    constructor(options) {
        // this.outerWrapper = options.element;
        //
        // this.baseElement = null;
        // this.svgWrapper = null;
        // this.svgElement = null;
        // this.svgImage = null;
        // this.outerWrapperWidth = null;
        // this.outerWrapperHeight = null;
        // this.elements = [];
        // this.lastPosition = {
        //     x: 0,
        //     y: 0
        // };

        this.setOptions(options);
        this.createLoader();
        // this.init();
        // this.bindEvents();
    }

    setOptions() {
        // здесь надо помежить опции из вне с внутренними
        // опции из вне приоритетнее
        const size = 80;

        this.options = {
            filling: true,
            loader : {
                element: document.body,
                size   : [size],
                color  : 'red',
                speed  : '600ms'
            },
            elements: {
                fill       : 'yellow',
                stroke     : 'none',
                fillOpacity: 0.5
            }
        };

        // this.outerWrapperWidth = this.options.filling ? `${this.outerWrapper.offsetWidth}` : '500px';
        // this.outerWrapperHeight = this.options.filling ? `${this.outerWrapper.offsetHeight}` : '500px';
        // this.outerWrapperRatio = this.outerWrapperHeight / this.outerWrapperWidth;
    }

    // bindEvents() {
    //     /**
    //      * фильтруем маски на планах
    //      */
    //     // this.filters.addEventListener('change', () => {
    //         // пофильтровать маски
    //     // });
    //
    //     /**
    //      * пересчитываем расположение svg внутри элемента
    //      */
    //     // window.addEventListener('resize', throttle(this.reposition, 300));
    //
    //
    //     /**
    //      * если визуальный не помещается в своего родителя
    //      */
    //     // document.addEventListener('mousemove', throttle(this.movingVisual.bind(this), 300));
    //     document.addEventListener('mousemove', (event) => {
    //         this.movingVisual(event);
    //     })
    //
    // }

    init() {
        // this.create();
        this.createLoader();
    }

    createLoader() {
        this.loader = new Loader(this.options.loader);
        this.loader.show();
    }

    create() {
        this.createBase();
        this.createSvg();
        this.createImage();
        // this.createPaths();
        // this.createRotateButton();
        // this.createParametricButton();
        // this.createFilter();

        // перенести в метод paint (все три)
        this.outerWrapper.appendChild(this.baseElement);
        this.svgWrapper.appendChild(this.svgElement);
        this.svgElement.appendChild(this.svgImage);
    }

    /**
     * создаем html основу для выборщика
     */
    createBase() {
        this.baseElement = document.createElement('div');
        this.baseElement.className = 'b-visual';
        this.svgWrapper = document.createElement('div');
        this.svgWrapper.className = 'b-visual__svg-wrapper';
        this.baseElement.appendChild(this.svgWrapper);
    }


    /**
     * создаем svg
     */
    createSvg() {
        this.svgElement = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        this.svgElement.setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xlink', 'http://www.w3.org/1999/xlink');
        this.svgElement.setAttribute('class', 'b-visual__svg');

        // временная заглушка, чтобы видеть изображение
        this.svgElement.setAttribute('width', this.outerWrapperWidth);
        this.svgElement.setAttribute('height', this.outerWrapperHeight);
    }

    createImage(url = '/images/visual.jpg') {
        const image = new Image();

        image.src = url;

        this.svgImage = document.createElementNS('http://www.w3.org/2000/svg', 'image');
        this.svgImage.setAttributeNS('http://www.w3.org/1999/xlink', 'href', url);

        /**
         * надо научится сохранять картинки, чтобы в следующий раз не загружать её
         * особенно важно, когда ходишь по шагам и возвращаешься обратно
         */
        image.addEventListener('load', () => {
            const x = 0;
            const y = 0;

            this.imageWidth = image.width;
            this.imageHeight = image.height;
            this.imageRatio = this.imageHeight / this.imageWidth;

            this.svgElement.setAttribute('viewBox', `0 0 ${this.imageWidth} ${this.imageHeight}`);
            this.svgImage.setAttribute('x', x);
            this.svgImage.setAttribute('y', y);
            this.svgImage.setAttribute('width', this.imageWidth);
            this.svgImage.setAttribute('height', this.imageHeight);
            this.svgImage.setAttribute('preserveAspectRatio', 'none');

            this.reposition();
        });
    }

    /**
     * создаем path
     */
    createPaths() {
        const that = this;
        const xhr = new XMLHttpRequest();

        xhr.open('POST', '/tests/visual.json', true);
        xhr.send();

        xhr.onload = function load() {
            const response = JSON.parse(xhr.response);
            const elements = response.elements.def;

            /**
             * сначала надо создать массив path node и потом только весь добавить в svg, а не
             * итеративно кадый path отдельно
             */
            for (let i = 0; i < elements.length; i++) {
                // that.elements.push()

                that.svgElement.appendChild(that.createPath(elements[i]));
            }
        };
    }

    /**
     * создаем path
     * @param {Object} elm - path
     * @return {Object} element;
     */
    createPath(elm) {
        const element = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        element.setAttribute('fill', this.options.elements.fill);
        element.setAttribute('stroke', this.options.elements.stroke);
        element.setAttribute('fill-opacity', this.options.elements.fillOpacity);
        element.setAttribute('d', elm.coords);

        /**
         * здесь надо заполнить элемент данными
         * их потом можно переиспользовать при заполнение тултипов
         */
        element.data = {
            title: 'дом 1'
            // 'pipe': [1, 2, 3, 4]
        };

        return element;
    }

    createRotateButton() {
        // response.elements > 0
        const isRotate = true;

        if (!isRotate) {
            return;
        }

        // здесь нужно толлько создать, а отрисовку
        this.wrapperElement.insertAdjacentHTML('beforeend', rotateButton());
        // перенести в метод paint
    }

    createParametricButton() {
        this.wrapperElement.insertAdjacentHTML('beforeend', parametricButton());
    }

    createFilter() {
        this.wrapperElement.insertAdjacentHTML('beforeend', filter());
    }


    /**
     * до этого момента все элементы должны быть уже созданы,
     * но ещё не отрисованы
     * отрисовать надо здесь за одно DOM событие
     * сборка происходит в методе create()
     */
    static paint() {
        // do nothing
    }

    /**
     * меняет размер svg обертки и меняет позиционирование, стараясь помесить картинку в визуальный выборщик
     * методу надо передавать новые размеры, иначе resize не работает
     */
    reposition() {
        const halfSize = 2;

        if (this.imageRatio > this.outerWrapperRatio) {
            const width = this.outerWrapperWidth;
            const height = this.outerWrapperWidth * this.imageRatio;

            this.svgElement.setAttribute('width', width);
            this.svgElement.setAttribute('height', height);

            this.svgWrapper.style.width = `${width}px`;
            this.svgWrapper.style.height = `${height}px`;

            this.svgWrapper.style.top = `${-(height - this.outerWrapperHeight) / halfSize}px`;
            this.svgWrapper.style.left = `${-(width - this.outerWrapperWidth) / halfSize}px`;
        } else if (this.imageRatio < this.outerWrapperRatio) {
            const width = this.outerWrapperHeight / this.imageRatio;
            const height = this.outerWrapperHeight;

            this.svgElement.setAttribute('width', width);
            this.svgElement.setAttribute('height', height);

            this.svgWrapper.style.width = `${width}px`;
            this.svgWrapper.style.height = `${height}px`;

            this.svgWrapper.style.top = `${-(height - this.outerWrapperHeight) / halfSize}px`;
            this.svgWrapper.style.left = `${-(width - this.outerWrapperWidth) / halfSize}px`;
        }
    }

    static movingVisual() {
        // //check to make sure there is data to compare against
        //
        // //get the change from last position to this position
        // var deltaX = this.lastPosition.x - event.clientX;
        // var deltaY = this.lastPosition.y - event.clientY;
        //
        // //check which direction had the highest amplitude and then figure out direction by checking if the value is greater or less than zero
        // if (Math.abs(deltaX) > Math.abs(deltaY) && deltaX > 0) {
        //     this.svgWrapper.style.left = `${parseInt(this.svgWrapper.style.left) + 10}px`;
        // } else if (Math.abs(deltaX) > Math.abs(deltaY) && deltaX < 0) {
        //     this.svgWrapper.style.left = `${parseInt(this.svgWrapper.style.left) - 10}px`;
        // } else if (Math.abs(deltaY) > Math.abs(deltaX) && deltaY > 0) {
        //     this.svgWrapper.style.top = `${parseInt(this.svgWrapper.style.top) + 10}px`;
        // } else if (Math.abs(deltaY) > Math.abs(deltaX) && deltaY < 0) {
        //     this.svgWrapper.style.top = `${parseInt(this.svgWrapper.style.top) - 10}px`;
        // }
        //
        // this.lastPosition = {
        //     x: event.clientX,
        //     y: event.clientY
        // };
    }
}

export default Visual;
