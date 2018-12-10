/* временно */
/* eslint-disable */

/**
 * @version 2.0
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/maps.html documentation
 */


/**
 * ключ для тестирования на localhost - AIzaSyB1k18NIGKRnRJxpUY5eGadjW286_-CkFQ
 */

/**
 * DEPENDENCIES
 */
import './map.scss';
import './preloader/preloader.scss';
import preloaderTemplate from './preloader/preloader.twig';
import Utils from 'common/scripts/utils';
import fullscreenButtonTemplate from './fullscreen-button/fullscreen-button.twig';
import zoomTemplate from './zoom/zoom.twig';
import screenfull from 'screenfull';
// import MarkerClusterer from 'node-js-marker-clusterer';

/**
 * FUNCTIONS
 */

/**
 * update options to draw circle (concatenate new and default options)
 * @param {Object} radius - new options for radius, example {stroke: {color: 'blue', opacity: .5, weight: 2}}
 * @param {Object} defOptions - default options for radius, which writing in method of setOptions
 * @return {Object} {stroke: {color: 'brown', opacity: .8, weight: 2}, fill: {color: 'brown', opacity: .8}, center: [67.92386, 32.924279], radius: 1000}
 */
// const updateOptionsRadius = function(radius, defOptions) {
//     const lat = 0;
//     const lng = 1;
//
//     return {
//         strokeColor  : radius.stroke ? radius.stroke.color : defOptions.radiuses.stroke.color,
//         strokeOpacity: radius.stroke ? radius.stroke.opacity : defOptions.radiuses.stroke.opacity,
//         strokeWeight : radius.stroke ? radius.stroke.weight : defOptions.radiuses.stroke.weight,
//         fillColor    : radius.fill ? radius.fill.color : defOptions.radiuses.fill.color,
//         fillOpacity  : radius.fill ? radius.fill.opacity : defOptions.radiuses.fill.opacity,
//         center       : new google.maps.LatLng(radius.center[lat], radius.center[lng]),
//         radius       : radius.radius ? radius.radius : defOptions.radiuses.radius
//     };
// };

class Map {
    constructor() {
        /**
         * Node обёртка карты всей карты
         * @type {Node}
         */
        this.target = null;
        //
        /**
         * Node карты, та часть куда будет вставлена карта
         * @type {Node}
         */
        this.content = null;

        /**
         * Node прелоадера
         * @type {Node}
         */
        this.preloader = null;

        /**
         * все настроки карты
         * @type {Object}
         */
        this.settings = {};

        /**
         * гугловый объект карты
         * @type {null}
         */
        this.googleMap = {};

        /**
         * Кнопка фуллскрин режима
         * @type {Node}
         */
        this.fullscreen = null;

        /**
         * Гугломаркеры
         * @type {Array}
         */
        this.gMarkers = [];

        /**
         * Маршруты
         * @type {Array}
         */
        this.routes = [];

        this.fromMarker = null;

        /**
         * Тултип
         * @type {Object}
         */
        this.infoWindow = null;

        /**
         * Активный тултип
         * @type {Object}
         */
        this.activeInfoWindow = null;
    }

    /**
     *
     * @param {Object} outerOptions - настройки из внешнего файла
     * @param {Function} initGoogleMap - функция обратного вызова, срабатывает, когда гугл карта уже загрузилась
     * и показывается
     */
    init(outerOptions, initGoogleMap = function() {}) {
        if (!this.checkInitParameters(outerOptions)) {
            return false;
        }

        this.target = outerOptions.target;
        this.content = this.target.querySelector('.b-map__content');

        this.insetPreloader(this.content);
        // сохраняем node прелоадера, чтобы потом удалить
        this.preloader = this.target.querySelector('.b-map-preloader');
        this.reserveHeight(this.target);


        this.completeSettings(outerOptions, () => {
            this.loadGoogleMap();
            this.removePreloader();
            this.removeSpacer();
            this.initGoogleMap(() => {
                if (this.settings.customFullscreen) {
                    this.initCustomFullscrenn();
                }

                if (this.settings.customZoom) {
                    this.initCustomZoom();
                }

                if (Utils.keyExist(this.settings, 'markers')) {
                    this.initMarkers(this.settings.markers);
                }

                if (this.settings.developerMode) {
                    this.developerMode();
                }

                if (this.settings.images.length) {
                    // this.images();
                }

                initGoogleMap();
            });
        });
    }

    images() {
        this.historicalOverlay = new google.maps.GroundOverlay(
            '/icons/map/media/markers/house.png',
            {
                north: 55.55603,
                west : 37.546645,
                south: 55.552536,
                east : 37.538877
            });

        this.historicalOverlay.setMap(this.googleMap);
    }

    /**
     * Проверяет все ли условия удовлетворяют запуску всего кода карты. Если нет, то карты даже не начинают работать
     * @param {Object} options - настройки, которые передали в карту
     * @return {boolean} - true, если если все проверки пройдены и false если нет .
     */
    checkInitParameters(options) {
        let mapKeyCorrect = null;

        if (typeof options === 'undefined' || Utils.isEmptyObject(options)) {
            console.error('В настройки ничего не передано. Передай как минимум ключ(key) и цель(target))');

            return false;
        }

        mapKeyCorrect = Utils.isString(options.key) || Utils.keyExist(options, 'key');

        if (!mapKeyCorrect) {
            console.error('Не указан ключ, либо ключ указан в неверном формате');

            return false;
        }

        if (!options.target) {
            console.error('Не указан элемент карты');

            return false;
        }

        return true;
    }

    /**
     * Создает прелоадер - заглушка перед тем как загрузилась google карта
     * @param {Node} element - элемент внутрь которого будет вставлен прелоадер
     */
    insetPreloader(element) {
        Utils.insetContent(element, preloaderTemplate());
    }

    /**
     * Удаляет preloader
     * @param {Node} preloader - элемент прелодера, который требуется удалить
     */
    removePreloader() {
        Utils.removeElement(this.preloader);
    }

    removeSpacer() {
        Utils.removeElement(this.target.querySelector('.b-map__spacer'));
    }

    /**
     * Резервирует высоту блока для карты. Для того чтобы карты была видна, иначе высота и ширина равно 0
     * @param {Node} element - элемент которому требуется зарезервировать высоту
     */
    reserveHeight(element) {
        element.style.height = `${element.offsetHeight}px`;
        element.style.width = `100%`;
    }

    /**
     * Склеивает все настройки карты в одну перменную
     * @param {Object} outerOptions - настройки из внешнего скрипта
     * @param {Object} serverSuccess - настройки от сервера
     */
    completeSettings(outerOptions, serverSuccess = function() {}) {
        this.connectSettings(this.setDefaultSettings());
        this.connectSettings(outerOptions);
        this.getServerSettings(serverSettings => {
            this.connectSettings(serverSettings);
            this.normalizeSettings();
            serverSuccess();
        });
    }

    /**
     * Преобразует настройки в гугло понятные настройки
     * Например: center:[59, 60] -> center: {lat: 59, lng: 60}
     */
    normalizeSettings() {
        for (const key in this.settings) {
            if (key === 'center') {
                this.settings[key] = this.coordsArrayConvertToGoogleCoords(this.settings[key]);
            }
        }
    }

    /**
     * Склеивает конкртеную настройку в общую настройку всей карты
     * @param {Object} outerSettings - объект настроек карты
     */
    connectSettings(outerSettings) {
        Object.assign(this.settings, outerSettings);
    }

    /**
     * Устаналивает настройки по умолчанию
     * @return {{center: {lat, lng}, zoom: number, minZoom: number, maxZoom: number, zoomStep: number, gestureHandling: string, routes: {travelMode: string, color: string, opacity: number, weight: number}}} - объект настроек карты
     */
    setDefaultSettings() {
        const latCenter = 67.92386;
        const lngCenter = 32.924279;

        return {
            center           : [latCenter, lngCenter],
            mapTypeControl   : false,
            zoom             : 12,
            minZoom          : 10,
            maxZoom          : 18,
            zoomStep         : 1,
            gestureHandling  : 'cooperative',
            fullscreenControl: true,
            routes           : {
                travelMode: 'DRIVING',
                color     : '#383855',
                opacity   : 1,
                weight    : 3
            }
        };
    }

    /**
     * Запрашивает настройки карты у сервера
     * @param {Function} callback - срабатывает после положительного ответа сервера
     */
    getServerSettings(callback = function() {    }) {
        // временное решение пока не работает сервер
        // эмуляция работы сервера

        setTimeout(() => {
            callback({
                zoom: 15
            });
        }, 4);

        // Utils.send({}, '/test/map.json', {
        //     success: serverSettings => {
        //         callback(serverSettings);
        //     }
        // });
    }

    /**
     * Трансформирует массив координат в объект координат
     * @param {Array} coords - пример [35.545654, 75.231321]
     * @returns {Object} - пример {lat: 35.545654, lng: 75.231321}
     */
    coordsArrayConvertToGoogleCoords(coords) {
        const lat = 0;
        const lng = 1;

        return {
            lat: coords[lat],
            lng: coords[lng]
        };
    }

    /**
     * Загрузка google карты
     */
    loadGoogleMap() {
        const script = document.createElement('script');

        script.async = true;
        script.defer = true;
        script.type = 'text/javascript';
        script.src = `https://maps.googleapis.com/maps/api/js?key=${this.settings.key}&callback=initGoogleMap`;
        document.body.appendChild(script);
    }

    /**
     * Инициализирует google карту
     * @param {Function} initGoogleMap - функция обратного вызова, запускается после создания google карты
     */
    initGoogleMap(initGoogleMap) {
        window.initGoogleMap = () => {
            this.googleMap = new google.maps.Map(this.content, this.settings);
            initGoogleMap();
        };
    }

    /**
     * Инициализирует кастомную кнпоку fullscreen
     */
    initCustomFullscrenn() {
        this.googleMap.set('fullscreenControl', false);
        Utils.insetContent(this.target, fullscreenButtonTemplate());
        this.fullscreen = this.target.querySelector('.b-map__fullsreen-button');

        this.fullscreenBindClick();
    }

    /**
     * Добавляет клики по кнопке fullscreen
     */
    fullscreenBindClick() {
        this.fullscreen.addEventListener('click', () => {
            if (this.fullscreen.classList.contains('b-map__fullsreen-button_is_active')) {
                this.fullscreen.classList.remove('b-map__fullsreen-button_is_active');
                screenfull.exit();
            } else {
                screenfull.request(this.target);
                this.fullscreen.classList.add('b-map__fullsreen-button_is_active');
                this.target.webkitRequestFullscreen();
            }
        });
    }

    /**
     * Инициализирует кастомный зум на карте
     */
    initCustomZoom() {
        this.googleMap.set('zoomControl', false);
        Utils.insetContent(this.target, zoomTemplate());

        this.zoomInButton = this.target.querySelector('.b-map__zoom-button-plus');
        this.zoomOutButton = this.target.querySelector('.b-map__zoom-button-minus');

        this.zoomBindClick();
    }

    /**
     * Вешает клики на кнопки zoom
     */
    zoomBindClick() {
        this.zoomInButton.addEventListener('click', () => {
            this.zoomIn();
            this.zoomCheckState();
        });

        this.zoomOutButton.addEventListener('click', () => {
            this.zoomOut();
            this.zoomCheckState();
        });
    }

    /**
     * Проверяет и меняет состояние zoom кнопок
     */
    zoomCheckState() {
        const zoomHiddenClass = 'b-map__zoom-button_is_hidden';

        if (this.getZoom() === this.settings.maxZoom) {
            this.zoomInButton.classList.add(zoomHiddenClass);
        } else {
            this.zoomInButton.classList.remove(zoomHiddenClass);
        }

        if (this.getZoom() === this.settings.minZoom) {
            this.zoomOutButton.classList.add(zoomHiddenClass);
        } else {
            this.zoomOutButton.classList.remove(zoomHiddenClass);
        }
    }

    /**
     * Показывает какой сейчас зум используется на карте
     * @returns {Number} - число от 1 до 20. 1 и 20 это минимальное и максимальное число google карты
     * @see https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#getZoom getZoom
     */
    getZoom() {
        return this.googleMap.getZoom();
    }

    /**
     * Изменяет зум карты на заданный
     * @param {Number} zoom - число между минимального и максимального зума
     * @param {Function} callback - срабатывает после изменения зума
     * @see https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#setZoom setZoom
     */
    setZoom(zoom = this.settings.zoom, callback = function() {}) {
        const currentZoom = this.getZoom();

        if (zoom < this.settings.minZoom ||
            zoom > this.settings.maxZoom || currentZoom === zoom || typeof zoom !== 'number') {
            console.error(`setZoom(${zoom}) - is bad value. Please check documentation - https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#setZoom`);

            return;
        }

        this.googleMap.setZoom(zoom);

        callback();
    }

    /**
     * Увеличивает зум на карте на один шаг. Шаг указывается в настройках карты
     * @see https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#zoomIn zoomIn
     */
    zoomIn() {
        this.setZoom(this.getZoom() + this.settings.zoomStep);
    }

    /**
     * Уменбшает зум на карте на один шаг. Шаг указывается в настройках карты
     * @see https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#zoomOut zoomOut
     */
    zoomOut() {
        this.setZoom(this.getZoom() - this.settings.zoomStep);
    }

    /**
     * map get center
     * @returns {Object} - google latLng {lat: functuion(), lngL function()}
     * @see https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#getCenter getCenter*
     */
    getCenter() {
        return googleMap.getCenter();
    }

    /**
     * Change map center
     * @param {Array} coords - [latitude, longitude]
     * @param {Function} callback - callback function
     * @see https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#setCenter setCenter
     */
    setCenter(coords, callback = function() {}) {
        const coorectArrayLength = 2;

        if (!Array.isArray(coords) || coords.length !== coorectArrayLength) {
            console.error(`setCenter(${coords}) - is bad value. Please check documentation - https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#setCenter`);

            return;
        }

        this.googleMap.setCenter(this.coordsArrayConvertToGoogleCoords(coords));

        callback();
    }

    /**
     * Перезагружает карту.
     * Требуется, когда карта показывается из скрытого блока или когда происодит ресайз и надо обновить карту
     * @param {Function} callback - функция обратного вызова, срабатывает когда карта перезагрузилась
     * @see https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#refresh refresh
     */
    refresh(callback = function() {}) {
        google.maps.event.trigger(this.googleMap, 'resize');

        callback();
    }

    /**
     * init marker in map
     * @param {Object} markers - object marker
     */
    initMarkers(markers) {
        if (!markers) {
            return;
        }

        for (let i = 0; i < markers.length; i++) {
            this.createMarker(markers[i]);
        }
    }

    /**
     * Добавляет маркеры на карты
     * @param {Array} markers - массив пользовательских макркеров
     * @param {Function} callback - callback function
     */
    // addMarkers(markers, callback = function() {}) {
    //     for (let i = 0; i < markers.length; i++) {
    //         this.addMarker(markers[i]);
    //     }
    //
    //     callback();
    // }

    /**
     * Добавляет маркер на карту
     * @param {Object} marker - пользовательского маркера
     */
    // addMarker(marker) {
    //     this.createMarker(marker).setMap(this.googleMap);
    // }

    /**
     * Превращает пользовательский макер в гуглмаркер
     * @param {Object} marker - пользовательский маркер
     * @returns {Object} gmarker - возвращает гугломаркер
     */
    createMarker(marker) {
        const defaultSizeMarker = 26;
        const minMarkerSize = 2;
        const width = 0;
        const height = 1;
        let gMarker = {};
        let half = 2;

        // если в массиве пришли строки, приводим их к integer
        const toInteger = function(array) {
            return array.map(item => {
                return parseInt(item, 10);
            });
        };

        // проверка на правильность данных из marker.sizes = Array [Number, Number]
        const sizes = Array.isArray(marker.sizes) && marker.sizes.length === minMarkerSize ?
            toInteger(marker.sizes) :
            [defaultSizeMarker, defaultSizeMarker];

        // проверка на правильность данных из marker.icon = String
        let icon = typeof marker.icon === 'string' ? marker.icon : '/icons/map/media/markers/marker.svg';
        const match = icon.match(/^\.|\.svg$|\.gif$|.jpg$|\.png$/i);
        const svg = 0;

        if (match[svg] === '.svg') {
            icon = {
                url       : icon,
                scaledSize: new google.maps.Size(sizes[width], sizes[height]),
                anchor    : new google.maps.Point(sizes[width] / half, sizes[width] / half)
            };
        }

        const markerOptions = {
            position : this.coordsArrayConvertToGoogleCoords(marker.coords),
            map      : this.googleMap,
            animation: google.maps.Animation.DROP,
            optimized: false
        };

        Object.assign(markerOptions, marker);
        gMarker = new google.maps.Marker(markerOptions);

        this.gMarkers.push(gMarker);

        return gMarker;
    }

    /**
     * Полностью и безвозвратно удаляет маркеры
     * @param markers
     */
    removeMarkers(markers) {
        for (let i = 0; i < markers.length; i++) {
            this.removeMarker(markers[i]);
        }
    }

    /**
     * Полностью и безвозвратно удаляет маркеры
     * @param marker
     */
    removeMarker(marker) {
        marker.setMap(null);
    }

    /**
     * initialize directions of display
     * @param {String} color - color of route - example 'red' or '#000000'
     * @param {Number} opacity - opacity of route
     * @param {Number} weight - weight of route
     * @return {Object} {google.maps.DirectionsRenderer}
     */
    initDirectionsDisplay(color, opacity, weight) {
        return new google.maps.DirectionsRenderer({
            polylineOptions: {
                strokeColor  : color,
                strokeOpacity: opacity,
                strokeWeight : weight
            }
        });
    };

    /**
     * initialize directions of service
     * @return {Object} {google.maps.DirectionsService}
     */
    initDirectionsService() {
        return new google.maps.DirectionsService();
    };

    /**
     * Draw a route by two points
     * @param {Array} from - coordinates of the starting point - example [35.545654, 75.231321]
     * @param {Array} to - coordinates of the ending point - example [35.545654, 75.231321]
     * @param {Function} callback - callback function
     * @see https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/maps.html#drawRoute
     */
    drawRoute(from, to, callback = function() {}) {
        if (this.routes.length > 0) {
            this.clearRoutes();
        }

        const coorectArrayLength = 2;

        if (!Array.isArray(from) ||
            from.length !== coorectArrayLength || !Array.isArray(to) || to.length !== coorectArrayLength) {
            console.error(`drawRoute(${from}, ${to}) - is bad value. Please check documentation - https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#drawRoute`);

            return;
        }

        const request = {
            origin     : this.coordsArrayConvertToGoogleCoords(from),
            destination: this.coordsArrayConvertToGoogleCoords(to),
            travelMode : this.settings.routes.travelMode
        };

        this.directionsDisplay = this.initDirectionsDisplay(
            this.settings.routes.color,
            this.settings.routes.opacity,
            this.settings.routes.weight
        );

        this.directionsDisplay.setOptions({
            suppressMarkers: true
        });

        this.directionsService = this.initDirectionsService();
        this.directionsDisplay.setMap(this.googleMap);

        this.directionsService.route(request, (response, status) => {
            if (status === 'OK') {

                this.fromMarker = this.createMarker({
                    coords: from,
                    sizes : [60, 60],
                    icon  : {
                        anchor: new google.maps.Point(30, 30),
                        url   : '/icons/map/media/markers/marker-from.svg'
                    }
                });

                this.directionsDisplay.setDirections(response);

                const routeInfo = response.routes[0];

                this.showRouteInfoWindow(routeInfo);
            } else {
                console.error(`Directions request failed due to ${status}`);
            }
        });

        this.routes.push(this.directionsDisplay);

        callback(from, to);
    }

    /**
     * Инициализирует infoWindow
     */
    initInfoWindow() {
        this.infoWindow = new google.maps.InfoWindow();
    }

    /**
     * Делает и сразу показывает на карте тултип
     * @param {Object} data - данные для тултипа
     * data.content - контент для тултипа
     * data.postion - местоположение на карте
     * data.positon.lat() - широта местоположение
     * data.positon.lat() - долгота  местоположение
     */
    createInfoWindow(data) {
        // добавляем контент в infoWindow
        this.infoWindow.setContent(data.content);

        // устанавливаем координаты infoWindow
        this.infoWindow.setPosition({
            lat: data.position.lat(),
            lng: data.position.lng()
        });

        // открываем infoWindow
        this.infoWindow.open(this.googleMap);
    }

    /**
     * Скрывает активный тултип
     */
    closeActiveInfoWindow() {
        this.activeInfoWindow.close();
    }

    /**
     * Отображение инфомации о маршруте
     * @param {Function} routeInfo - информация о маршруте
     * @see https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/maps.html#showRouteInfoWindow
     */
    showRouteInfoWindow(routeInfo) {
        const routeDuration = routeInfo.legs[0].duration.text;
        const routeDistance = routeInfo.legs[0].distance.text;
        const pathInfo = routeInfo.overview_path;
        const routeLength = pathInfo.length;
        const midPoint = pathInfo[parseInt(routeLength / 2)];
        const data = {
            content : `${routeDuration}, ${routeDistance}`,
            position: midPoint
        };

        // закрываем все infoWindow, кроме активного для текущего маршрута
        if (this.activeInfoWindow) {
            this.closeActiveInfoWindow();
        }

        this.createInfoWindow(data);

        // отмечаем infoWindow как активный
        this.activeInfoWindow = this.infowindow;
    }

    /**
     * Delete all routes
     * @param {Function} callback - callback function
     * @see https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/maps.html#clearRoutes
     */
    clearRoutes(callback = function() {}) {
        if (this.fromMarker) {
            this.removeMarker(this.fromMarker);
        }

        const absenceRoutes = 0;

        if (this.routes.length <= absenceRoutes) {
            console.error('No routes created!');

            return;
        }

        for (let i = 0; i < this.routes.length; i++) {
            this.routes[i].setMap(null);
        }

        this.routes = [];

        callback();
    }

    /**
     * если геокодинг требуется, то иницициализируем его
     * требуется - для пос object || arrayтроения маршрутов
     */
    initGeocode() {
        this.geocoder = new google.maps.Geocoder();
    }

    /**
     * @param {String} address - example - "Кондрикова 22"
     * @param {Function} callback - callback function
     */
    codeAddress(address, callback = function() {}) {
        const lat = 0;
        const lng = 1;

        this.geocoder.geocode({address}, results => {
            callback([results[lat].geometry.location.lat(), results[lng].geometry.location.lng()]);
        });
    }

    /**
     * Draw circles by radiuses of options
     * @param {Object} radiuses - one or more (array) options for radius
     * @example {stroke: {color: 'brown', opacity: .8, weight: 2}, fill: {color: 'brown', opacity: .8}, center: [67.92386, 32.924279], radius: 1000}
     * @param {Function} callback - callback function
     * @see https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/maps.html#setRadiuses
     */
    setRadiuses(radiuses, callback = function() {}) {
        if (typeof radiuses !== 'object' || radiuses === null) {
            console.error(`setRadiuses(${radiuses}) - is bad value. Please check documentation - https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#setRadiuses`);

            return;
        }

        // transformation of object into array
        const $radiuses = Array.isArray(radiuses) ? radiuses : Object.keys(radiuses);

        if (!$radiuses.length) {
            console.error(`setRadiuses(${radiuses}) - is empty value. Please check documentation - https://kelnik.gitbooks.io/kelnik-documentation/front-end/components/maps.html#setRadiuses`);

            return;
        }

        $radiuses.forEach(radius => {
            const circle = new google.maps.Circle(updateOptionsRadius(radius, this.options));

            circle.setMap(this.googleMap);
        });

        callback();
    }

    /**
     * Кластеризация
     */
    initClusters() {
        if (!this.clusterOptions) {
            return;
        }

        this.clusters = new MarkerClusterer(this.googleMap, this.gMarkers, this.clusterOptions);
    }

    /**
     * Cкрытие маркеров
     * @param {Object} markers - объект маркеров
     */
    hideMarkers(markers) {
        this.clusters.removeMarkers(markers);
    }

    /**
     * Отображение маркеров
     * @param {Object} markers - объект маркеров
     */
    showMarkers(markers) {
        this.clusters.addMarker(markers);
    }

    /**
     * Изменение области отображения карты, так чтобы были видны все маркеры
     * @param {Array} gMarkers - google маркеры
     */

    fitBounds(gMarkers) {
        this.bounds = new google.maps.LatLngBounds();
        gMarkers.forEach(markers => {
            this.bounds.extend(markers.getPosition());
        });
        this.googleMap.fitBounds(this.bounds);
        this.googleMap.setOptions({maxZoom: this.options.maxZoom});
    }

    developerMode() {
        google.maps.event.addListener(this.googleMap, 'click', function(event) {
            console.log(`[${event.latLng.lat()
                .toFixed(6)}, ${event.latLng.lng()
                .toFixed(6)}]`);// eslint-disable-line no-console
        });
    }
}

export default Map;
/* временно */
/* eslint-enable */
