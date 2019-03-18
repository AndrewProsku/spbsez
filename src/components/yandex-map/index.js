/* eslint-disable */

/**
 * @version 1.0
 * @author Kelnik Studios {http://kelnik.ru}
 * Взято с https://gitlab.kelnik.pro/samolet/himki/blob/dev/src/scripts/app/yandex-maps.js
 */
import axios from 'axios';
import mainHtmlMarker from './templates/markers/main.twig';
import secondaryHtmlMarker from './templates/markers/secondary.twig';
import textHtmlMarker from './templates/markers/text.twig';
import Utils from 'common/scripts/utils';

class YandexMap {
    constructor(ymaps) {
        /**
         * Экземпляр загруженных яндекс карт.
         */
        this.ymaps = ymaps;

        /**
         * Все настроки карты
         * @type {Object}
         */
        this.settings = {};

        /**
         * Яндексовый объект карты
         * @type {null}
         */
        this.map = {};

        /**
         * URL по которому будет выполнен ajax запрос за данными
         */
        this.url = '';

        /**
         * Класса DOM-элемента в который будет вставлена карта
         */
        this.base = '.b-yandex-map__base';
    }

    completeSettings() {
        axios.get('/test/yandex-map.json')
            .then((data) => {
                this.connectSettings(this.setDefaultSettings());
                this.connectSettings(this.getScriptSettings(outerOptions));
                this.connectSettings(data);
            })
            .catch((err) => console.log(err))
    }


    /**
     * @param {Object} outerOptions - настройки из внешнего файла
     * @param {Function} initYandexMap - функция обратного вызова, срабатывает, когда карта уже загрузилась
     * и показывается
     */
    init(outerOptions) {
        this.mapWrapper = outerOptions.wrapper;
        this.content = this.mapWrapper.querySelector(this.base);
        this.id = this.content.id;
        this.url = this.mapWrapper.dataset.ajax;
        this.json = this.mapWrapper.dataset.json || {};

        if (this.json) {
            this.json = JSON.parse(atob(this.json));
        }

        this.completeSettings(outerOptions, () => {
            this.initYandexMap(() => {
                this.initControls();
                this.initScrollZoom();
                this.initMarkers();
                this.initTabs();
                this.initCircles();
                this.initRoute();
                this.disableMobileDrag();
                this.handleLocationLinks();
            });
        });
    }

    /**
     * Непосредственное создание экземпляра Яндекс Карт
     * @param initMapCallback - коллбэк остальных методов модуля. Сработают только тогда, когда карта будет полностью создана
     */
    initYandexMap(initMapCallback = function() {}) {
        this.map = new this.ymaps.Map(this.id, this.settings, {suppressMapOpenBlock: true});

        // Если необходимо изменять зум карты при переходе от мобилок к планшету и наоборот
        // window.addEventListener('resize', (e) => {
        //     if (this.settings.wrapper.offsetWidth <= 669) {
        //         this.map.setZoom(9);
        //     } else {
        //         console.log('here');
        //         this.map.setZoom(10);
        //     }
        // });
        initMapCallback();
    }

    /**
     * Склеивает все настройки карты в одну перменную
     */
    completeSettings(outerOptions, initCallback = function() {}) {

        if (this.url) {
            this.connectSettings(this.setDefaultSettings());
            this.connectSettings(this.getScriptSettings(outerOptions));
            this.getServerSettings(this.url, initCallback);

            return;
        }

        this.connectSettings(this.setDefaultSettings());
        this.connectSettings(this.getScriptSettings(outerOptions));
        this.connectSettings(this.json);
        initCallback();
    }

    /**
     * Получает настройки от сервера
     */
    getServerSettings(url, serverSuccess = function () {}) {
        Utils.send('', url, {
            success: (response) => {
                this.connectSettings(response.data);

                serverSuccess();
            },
            error: (error) => {
                console.error(`При загрузке настроек с сервера произошла ошибка: ${error}`);
            }
        });
    }

    /**
     * Склеивает конкртеную настройку в общую настройку всей карты
     * @param {Object} outerSettings - объект настроек карты
     */
    connectSettings(outerSettings) {
        if (typeof outerSettings === `object`) {
            Object.assign(this.settings, outerSettings);
        }
    }

    /**
     * Устаналивает настройки по умолчанию
     * @return {{center: {lat, lng}, zoom: number, minZoom: number, maxZoom: number, zoomStep: number}} -
     * объект настроек карты
     */
    setDefaultSettings() {
        const latCenter = 59.939014;
        const lngCenter = 30.315545;

        // высчитываем высоту обертки карты, а равно высоту карты, для позиционирования элементов управления
        const heightMap = this.content.offsetHeight;
        const defaultZoom = document.documentElement.clientWidth > 669 ? 10 : 9;

        return {
            center                 : [latCenter, lngCenter],
            mapTypeControl         : false,
            zoom                   : defaultZoom,
            minZoom                : 8,
            maxZoom                : 18,
            zoomStep               : 1,
            controls               : [],
            zoomControl            : true,
            customZoomControl      : false,
            fullScreenControl      : true,
            customFullScreenControl: false,
            height                 : heightMap,
            zoomScroll             : false
        }
    }

    /**
     * Устаналивает пользовательские настройки
     * @return {object} - объект настроек карты из скрипта со страницы
     */
    getScriptSettings(outerOptions) {
        return outerOptions;
    }

    /**
     * Добавляем элементы управления на карту
     */
    initControls() {
        this.addZoomControl();
        this.addFullScreenControl();
    }

    /**
    * Добавляет элемент управления масштабом карты
    */
    addZoomControl() {
        // Проверяем отключен ли показ элементов управления вообще
        // Если да, то ничего не показываем
        if (!this.settings.zoomControl) {
            return;
        }

        // Проверяем нужно ли показывать кастомный элемент управление
        // Если нет то показываем стандартные
        if (!this.settings.customZoomControl) {
            this.map.controls.add(`zoomControl`);
            return;
        }

        // Показываем кастомные элементы управление
        const zoomControl = new ymaps.control.ZoomControl({
            options: {
                layout: this.createLayoutZoomControl()
            }
        });

        this.map.controls.add(zoomControl, {
            float   : 'none',
            position: {
                top  : 0,
                right: 0
            }
        });
    }

    initScrollZoom() {
        const scrollZoom = this.settings.zoomScroll;
        switch (scrollZoom) {
            case true:
                this.map.behaviors.enable('scrollZoom');
                break;
            case false:
                this.map.behaviors.disable('scrollZoom');
        }
    }

    /**
     * Создаем дизайнерский элемент управления картой
     * @return {object} - объект кастомного макета кнопок зуммирования карты
     */
    createLayoutZoomControl() {
        // если на странице более одной карты, то id кнопок зуммирования должны отличаться
        // иначе зум будет срабатывать одновременно на всех картах
        // mapId передаем в шаблонную строку для создания уникальных id кнопок
        const mapId = this.id;

        // Создадим пользовательский макет ползунка масштаба.
        const zoomLayout = ymaps.templateLayoutFactory.createClass(`<div class='b-yandex-map__zoom-controls'>
                <button type="button" id="${mapId}-in" class="b-yandex-map__zoom-btn"></button>
                <button type="button" id="${mapId}-out" class="b-yandex-map__zoom-btn"></button>
            </div>`, {

                // Переопределяем методы макета, чтобы выполнять дополнительные действия
                // при построении и очистке макета.
                build: function() {
                    // Вызываем родительский метод build.
                    zoomLayout.superclass.build.call(this);
                    // Привязываем функции-обработчики к контексту и сохраняем ссылки
                    // на них, чтобы потом отписаться от событий.
                    this.zoomInCallback = ymaps.util.bind(this.zoomIn, this);
                    this.zoomOutCallback = ymaps.util.bind(this.zoomOut, this);

                    // Начинаем слушать клики на кнопках макета.
                    document.getElementById(`${mapId}-in`).addEventListener('click', this.zoomInCallback);
                    document.getElementById(`${mapId}-out`).addEventListener('click', this.zoomOutCallback);
                },

                clear: function() {
                    // Снимаем обработчики кликов.
                    document.getElementById(`${mapId}-in`).removeEventListener('click', this.zoomInCallback);
                    document.getElementById(`${mapId}-out`).removeEventListener('click', this.zoomOutCallback);

                    // Вызываем родительский метод clear.
                    zoomLayout.superclass.clear.call(this);
                },

                zoomIn: function() {
                    let map = this.getData().control.getMap();
                    map.setZoom(map.getZoom() + 1, {checkZoomRange: true});
                },

                zoomOut: function() {
                    let map = this.getData().control.getMap();
                    map.setZoom(map.getZoom() - 1, {checkZoomRange: true});
                }
            }
        );

        return zoomLayout;
    }

    /**
     * Добавляет кнопку открытия карты на полный экран
     */
    addFullScreenControl() {
        if (!this.settings.fullScreenControl) {
            return;
        }

        if (!this.settings.customFullScreenControl) {
            this.map.controls.add('fullscreenControl');
            return;
        }

        //тут вызываем метод создающий кастомный контрол
    }

    /**
     * Отрисовка путей
     */
    initRoute() {
       /* const CustomLayoutClass = ymaps.templateLayoutFactory.createClass(textHtmlMarker({
            title: '20 минут до КАД',
            modify: 'theme_violet'
        }));*/

        // const routes = this.settings.routes;
        /*const routes = [{
            points: [
                [59.840573, 30.005940],
                [59.817581, 29.928189]
            ],
            wayPointFinishIconLayout: CustomLayoutClass,
            activeStrokeWidth: 6,
            activeStrokeColor: "rgba(48,64,154,0.48)"
        }, {
            points: [
                [59.799774, 30.273029],
                [59.834640, 30.276709],
                [59.840573, 30.005940]
            ],
            customViaPoint: true,
            wayPointFinishIconLayout: null,
            activeStrokeWidth: 6,
            activeStrokeColor: "rgba(102,45,145,0.48)"
        }];*/

        if (typeof this.settings.routes !== `object`) {
            return;
        }
        console.log('this.settings');

        this.settings.routes.forEach((rout) => {
            console.log('routes');
            let FinishLayout = null;
            if (typeof rout.finishMarker === `object`) {
                FinishLayout = ymaps.templateLayoutFactory.createClass(textHtmlMarker({
                    title: rout.finishMarker.title,
                    modify: `theme_${rout.finishMarker.theme}`
                }));
            }

            const multiRoute = new ymaps.multiRouter.MultiRoute({
                referencePoints: rout.points,
                params: {
                    results: 1
                }
            }, {
                boundsAutoApply: false,
                balloonLayout: null,
                wayPointStartVisible: false,
                cursor: 'default',
                wayPointFinishIconLayout: FinishLayout,
                routeActiveStrokeWidth: rout.activeStrokeWidth,
                routeActiveStrokeColor: rout.activeStrokeColor
            });

            // Добавление маршрута на карту.
            this.map.geoObjects.add(multiRoute);

            if (typeof rout.viaPoint === `object`) {
                this.customizeViaPoint(multiRoute, rout.viaPoint);
            }
        });
    }

    customizeViaPoint(route, pointData) {
        const LayoutClass = ymaps.templateLayoutFactory.createClass(textHtmlMarker({
            title: pointData.title,
            modify: `theme_${pointData.theme}`
        }));

        route.model.events.once("requestsuccess", function () {
            let yandexWayPoint = route.getWayPoints().get(1);

            ymaps.geoObject.addon.balloon.get(yandexWayPoint);
            yandexWayPoint.options.set({
                iconLayout: LayoutClass,
                balloonContentLayout: null
            });
        });
    }

    /**
     * Показываем маркеры объектов инфраструктуры на карте
     */
    initMarkers() {
        const markers = this.settings.markers;

        if (typeof markers !== `object`) {
            return;
        }

        if (this.settings.htmlMarkers) {
            this.initHtmlMarkers();
        }

        markers.forEach((element) => {
            // проверяем есть ли свойство offset у маркера, если нет
            // создаем массив значений смещения маркера на половину своей ширины и высоты
            // для того, что бы координата маркера и центр иконки маркера были совмещены
            const markerOffset = element.offset ? element.offset : element.size.map((size) => {
                return -(size / 2)
            });

            // проверяем является ли маркер маркером ЖК.
            let iconBalloon = ``;
            let objectType = ``;
            const complex = `complex`;

            if (element.type === 0) {
                iconBalloon = element.iconBalloon;
                objectType = iconBalloon ? complex : false;
            } else {
                iconBalloon = element.iconBalloon ? element.iconBalloon : element.icon;
                objectType = element.objectType;
            }

            // если в объекте маркера нет ни текста ни заголовка, то баллун не показываем
            if (!element.title && !element.text) {
                let marker = new ymaps.Placemark(element.coords, {
                    type: element.type
                }, {
                    iconLayout     : 'default#image',
                    iconImageHref  : element.icon,
                    iconImageSize  : element.size,
                    iconImageOffset: markerOffset
                });

                this.map.geoObjects.add(marker);
                this.closeBallonOnClickMap();

                return;
            }

            // выводим класс модификатор для разметки баллуна маркера ЖК, вне зависимости - есть в объекте маркера
            // objectType или нет

            const marker = new ymaps.Placemark(element.coords, {
                type          : element.type,
                balloonIcon   : iconBalloon,
                balloonHeader : element.title,
                balloonContent: element.text
            }, {
                balloonShadow         : false,
                balloonLayout         : this.createBalloonLayout(objectType),
                balloonContentLayout  : this.createBalloonContentLayout(iconBalloon),
                balloonPanelMaxMapArea: 0,
                // Не скрываем иконку при открытом балуне.
                hideIconOnBalloonOpen : false,
                iconLayout            : 'default#image',
                iconImageHref         : element.icon,
                iconImageSize         : element.size,
                iconImageOffset       : markerOffset
            });

            this.map.geoObjects.add(marker);
        });

    }

    /**
     * Показываем html маркеры объектов инфраструктуры на карте
     */
    initHtmlMarkers() {
        const htmlMarkers = this.settings.htmlMarkers;

        let template = null;

        htmlMarkers.forEach((element) => {
            switch (element.layout) {
                case 'main':
                    template = mainHtmlMarker;
                    break;
                case 'secondary':
                    template = secondaryHtmlMarker;
                    break;
                case 'text':
                    template = textHtmlMarker;
                    break;
                default:
                    template = mainHtmlMarker;
            }

            const properties = element; //Как-то по другому передаватать настройки

            const CustomLayoutClass = ymaps.templateLayoutFactory.createClass(template(properties));

            const placemark = new ymaps.Placemark(element.coords, {
            }, {
                iconLayout: CustomLayoutClass
            });

            this.map.geoObjects.add(placemark);
        })
    }

    /**
     * Навешивает обработчки события клик по карте и геобъектам для закрытия балунов.
     */
    closeBallonOnClickMap() {
        this.map.geoObjects.events.add('click', () => {
            this.map.balloon.close();
        });

        this.map.events.add('click', () => {
            this.map.balloon.close();
        })
    }

    /**
     * @param {string} iconBalloon - строка: значение свойства iconBalloon объекта маркера
     * @returns {Object} - объект макета контента балуна
     */
    createBalloonContentLayout(iconBalloon) {
        let iconHtml = ``;

        if (iconBalloon) {
            iconHtml = `<img class="b-yandex-map__balloon-icon" src="$[properties.balloonIcon]" alt="icon map">`;
        }

        const html = `${iconHtml}
            <div class="b-yandex-map__balloon-title">$[properties.balloonHeader]</div>`;

        const balloonContentLayout = ymaps.templateLayoutFactory.createClass(html);

        return balloonContentLayout;
    }

    /**
     *
     * @param {string} classModify - строка: значение свойства objectType объекта маркера
     * @returns {object} - объект макета баллуна
     */
    createBalloonLayout(classModify) {
        const balloonClass = `b-yandex-map__balloon`;
        const balloomClassModify = classModify ? `${balloonClass}_theme_${classModify}` : ``;
        const balloonClose = `b-map-balloon__close`;
        const balloonArrow = `b-map-balloon__arrow`;
        const balloonContentWrap = `b-map-balloon__content-wrap`;
        const classTab = `.b-map-tabs__item`;
        const tabItems = [...this.mapWrapper.querySelectorAll(classTab)];

        // Создание макета балуна
        const balloonLayout = ymaps.templateLayoutFactory.createClass(
            `<div class="${balloonClass} ${balloomClassModify}">
               
                <div class="${balloonArrow}"></div>
                <div class="${balloonContentWrap}">
                    $[[options.contentLayout observeSize maxWidth=200 maxHeight=350]]
                </div>
            </div>`, {
                /**
                 * Строит экземпляр макета на основе шаблона и добавляет его в родительский HTML-элемент.
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/layout.templateBased.Base.xml#build
                 * @function
                 * @name build
                 */
                build: function() {

                    this.constructor.superclass.build.call(this);

                    this._$element = $(`.${balloonClass}`, this.getParentElement());

                    this.applyElementOffset();


                    this._$element.find(`.${balloonClose}`)
                        .on('click', $.proxy(this.onCloseClick, this));

                    tabItems.forEach((tab) => {
                        tab.addEventListener('click', $.proxy(this.onCloseClick, this));
                    });
                },

                /**
                 * Удаляет содержимое макета из DOM.
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/layout.templateBased.Base.xml#clear
                 * @function
                 * @name clear
                 */
                clear: function() {
                    this._$element.find(`.${balloonClose}`)
                        .off('click');

                    this.constructor.superclass.clear.call(this);
                },

                /**
                 * Метод будет вызван системой шаблонов АПИ при изменении размеров вложенного макета.
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/IBalloonLayout.xml#event-userclose
                 * @function
                 * @name onSublayoutSizeChange
                 */
                onSublayoutSizeChange: function() {
                    balloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);

                    if (!this._isElement(this._$element)) {
                        return;
                    }

                    this.applyElementOffset();

                    this.events.fire('shapechange');
                },

                /**
                 * Сдвигаем балун, чтобы "хвостик" указывал на точку привязки.
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/IBalloonLayout.xml#event-userclose
                 * @function
                 * @name applyElementOffset
                 */
                applyElementOffset: function() {
                    this._$element.css({
                        left: -(this._$element[0].offsetWidth / 2),
                        top : -(this._$element[0].offsetHeight +
                            this._$element.find(`.${balloonArrow}`)[0].offsetHeight * 2)
                    });
                },

                /**
                 * Закрывает балун при клике на крестик, кидая событие "userclose" на макете.
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/IBalloonLayout.xml#event-userclose
                 * @function
                 * @param {event} event - событие
                 * @name onCloseClick
                 */
                onCloseClick: function(event) {
                    event.preventDefault();

                    this.events.fire('userclose');
                },

                /**
                 * Используется для автопозиционирования (balloonAutoPan).
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/ILayout.xml#getClientBounds
                 * @function
                 * @returns {object} - Координаты левого верхнего и правого нижнего углов шаблона относительно
                 * точки привязки.
                 * @name getShape
                 */
                getShape: function() {
                    if (!this._isElement(this._$element)) {
                        return balloonLayout.superclass.getShape.call(this);
                    }

                    let position = this._$element.position();

                    return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                        [position.left, position.top], [
                            position.left + this._$element[0].offsetWidth,
                            position.top + this._$element[0].offsetHeight +
                            this._$element.find(`.${balloonArrow}`)[0].offsetHeight
                        ]
                    ]));
                },

                /**
                 * Проверяем наличие элемента (в ИЕ и Опере его еще может не быть).
                 * @function
                 * @private
                 * @name _isElement
                 * @param {jQuery} [element] Элемент.
                 * @returns {Boolean} Флаг наличия.
                 */
                _isElement: function(element) {
                    return element && element[0] && element.find(`.${balloonArrow}`)[0];
                }
            }
        );

        return balloonLayout;
    }

    /**
     * Инициализируем табы
     */
    initTabs() {
        const classTab = `.b-yandex-map__tab-item`;
        const tabItems = this.mapWrapper.querySelectorAll(classTab);

        // Проверяем наличие табов
        if (!tabItems.length) {
            return;
        }

        const tabsArray = [...tabItems];

        tabsArray.forEach((tab) => {
            tab.addEventListener(`click`, (event) => {
                event.preventDefault();
                this.toggleTabs(tab);
                this.toggleMarkers(tab);
            });
        });
    }

    /**
     * Инициализируем ссылки на локации
     */
    handleLocationLinks() {
        const classLocationLink = `.j-contacts-location`;
        const locationLinks = Array.from(document.querySelectorAll(classLocationLink));

        // Проверяем ссылок на локации
        if (!locationLinks.length) {
            return;
        }

        locationLinks.forEach((link) => {
            if (link.dataset.coordinates) {
                const locationCoordinates = JSON.parse(link.dataset.coordinates);

                link.addEventListener(`click`, (event) => {
                    event.preventDefault();
                    this.centerOnCoordinates(locationCoordinates)
                });
            }
        });
    }

    /**
     * Центрируем карту на координатах
     * @param {array} coordinates - массив с координами маста
     */
    centerOnCoordinates(coordinates) {
        this.map.setCenter(coordinates, 11, {
            duration: 300,
            timingFunction: 'ease'
        });
    }

    /**
     * Показываем/скрываем маркеры на карте
     * @param {node} tab - нода таба по которому произошел клик
     */
    toggleMarkers(tab) {
        const classLink = `.b-yandex-map__tab-link`;

        const type = parseInt(tab.querySelector(classLink).dataset.type);

        this.map.geoObjects.each(function(geoObject) {

            const currentType = geoObject.properties.get('type');

            if (currentType) {
                if (type === 0) {
                    geoObject.options.set('visible', true);
                } else if (currentType === 0) {
                    geoObject.options.set('visible', true);
                } else if (currentType !== type) {
                    geoObject.options.set('visible', false);
                } else {
                    geoObject.options.set('visible', true);
                }
            }
        });
    }

    /**
     * Переключаем табы
     * @param {node} tab - нода таба по которому произошол клик
     */
    toggleTabs(tab) {
        const isActive = `is-active`;
        const classLink = `.b-yandex-map__tab-link`;
        const classTab = `.b-yandex-map__tab-item`;

        const activeType = tab.querySelector(classLink).dataset.type;
        const tabItems = [...this.mapWrapper.querySelectorAll(classTab)];

        tabItems.forEach((item) => {
            const itemType = item.querySelector(classLink).dataset.type;
            if (itemType === activeType) {
                item.classList.add(isActive);
                return;
            }
            item.classList.remove(isActive);
        });
    }

    /**
     * Инициализация радиусов
     */
    initCircles() {
        const circles = this.settings.circles;


        if (circles) {
            circles.forEach((circle) => {
                const myCircle = new ymaps.Circle([
                    circle.center, circle.radius
                ], {}, {
                    fillColor: circle.fillColor,
                    fillOpacity: circle.fillOpacity,
                    strokeColor: circle.strokeColor,
                    strokeOpacity: circle.strokeOpacity,
                    strokeWidth: circle.strokeWidth,
                    strokeStyle: circle.strokeStyle
                });

                this.map.geoObjects.add(myCircle);
            });
        }
    }

    /**
     * Отключаем перетаскивание карты при прокрутке одним пальцем на мобилке
     */
    // TODO Переписать правильно, что бы убрать игнорирование линтом
    /* eslint-disable */
    disableMobileDrag() {
        const isMobile = {
            Android: () => {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: () => {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: () => {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: () => {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: () => {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: () => {
                return isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows();
            }
        };

        if (isMobile.any()) {
            // this.map.behaviors.disable('drag');
        }
    }
}

export default YandexMap;

/* eslint-enable */
