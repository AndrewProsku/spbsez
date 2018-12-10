/* временно */
/* eslint-disable */

/**
 * @version 1.0
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/ documentation
 */

/**
 * TODO
 * - [] Класс принимает данные и из этих данных генерит результаты
 * - [] Генерится в элементе. Элемент передают
 * - [] Принимает данные в виде объекта со всеми нужными нам данными
 * - [] Может быть несколько темплейтов для отображения - списком или карточками
 * - [] Update - обновляет разметку при смене вида и сортировке, либо при фильтрации
 * - [] Шаблонов может быть сколько угодно
 * - [] Шаблоны надо кешировать
 * - [] если не тгруппировки, то можно сделать инфинити скролл
 *
 * - [] кнопки смена типов - карточкой или таблицей
 * - [] кнопки смена типов не показываются на смартфонах (на планшетах показывать)
 * - [] свойство которое принимает разрешение с которого можно показывать кнопки типов
 *
 * - [] сортировка
 * - [] по какому полю и в каком направлении
 * - [] сортировка обязательно должна быть помечена как псевдокласс
 * - [] поля по которым можно сортировать должны быть кнопками и со стрелками
 * - [] сортировка может осуществляться только по одному полю одновременно
 *
 * - [] tr - должен быть кликабельным
 *
 * - [] у картинки должно быть превью в большем разрешении
 * - [] обычно это тултип
 * - [] тултип должен всегда помещаться в экран
 * - [] а на мобилках прижиматься книзу экрана и иметь крестик
 *
 * - [] необходимо запоминать сколько уже показано квартир, чтобы уметь сообщать такую инфу для сервера
 */


/**
 * DEPENDENCIES
 */
import './result.scss';
import Utils from 'common/scripts/utils';

/**
 * Tempaltes
 */
import tableWrapperTemplate from './template/table/table.twig';
import tableRowTemplate from './template/table/row.twig';
import cardWrapperTemplate from './template/card/cards.twig';
import cardItemTemplate from './template/card/item.twig';


/**
 * Component
 */
class ParamericResults {
    /**
     * @constructor
     */
    constructor(options) {
        this.init(options);
    }

    /**
     * Инициализация всего
     */
    init(options) {
        this.wrapper = options.element;
        //временный хак для определения мобильного устройства
        this.mobile = true;
        this.type = this.mobile ? 'card' : 'table'; // card or table
        this.buttonMore = this.wrapper.querySelector('.b-parametric__button-more');
        this.numberShows = options.numberShows || 20; //сколько нужно показать
        this.alreadyShows = 0; //сколько уже показано
        this.body = document.body;
        this.sortButton = this.body.querySelectorAll('.j-sort');
        this.bindEvents();
    }

    /**
     * создаем структуру лоадера
     */
    createLoader() {

    }

    /**
     * вставляем лоадер в DOM
     */
    insertLoader() {

    }

    /**
     * показаем loader
     */
    showLoader() {

    }

    /**
     * Скрываем loader
     */
    hideLoader() {

    }

    /**
     * Удаляем loader
     */
    removeLoader() {

    }

    /**
     * Отправить запрос на сервер за данными
     * @param {Object} data - данные для сервар
     * @param {String} url - адрес для запроса
     * @param {Function} callback - функция обратного вызова, срабатывает при ответе сервера, либо при ошибке.
     */
    send(data, url, callback = function() {
    }) {
        Utils.send(data, url, callback);
    }

    /**
     * Создаем шаблон
     * @param {String} view - вид - табличный или плиточный.
     * @param {Object} data - данные - которые необходимо вставить в шаблон.
     * @return {String} template - шаблон.
     */
    createTemplate(view, data) {
        if (view === 'card') {
            this.template = cardWrapperTemplate(data);
        } else if (view === 'table') {
            this.template = tableWrapperTemplate(data);
        }

        return this.template;
    }

    /**
     * Вставляем HTML в DOM
     * @param {Object} target - dom элемент внутрь которого будет вставлять шаблон.
     * @param {String} content - сам шаблон из функции this.createTemplate.
     */
    insert(target, content) {
        Utils.insetContent(target, content);
    }

    /**
     * после вставки шаблона в DOM надо найти всякие управляющие кнопки или будущие места вставки
     * допустим в таблице для новых рядов
     * само название мне кажется не очень, но пока оно отражает то, что делает.
     */
    findElement() {

    }

    /**
     * Вешаем слушателей, лучше на единого родителя и лучше, чтобы они дальше не всплывали
     */
    bindEvents() {
        this.wrapper.addEventListener('click', (event) => {
            event.preventDefault();
            if (event.target === this.buttonMore) {
                this.more();
            }
        });
        this.sortButton.addEventListener('click', this.sort.bind(this));
    }

    /**
     * сортировка
     */
    sort() {
        this.clear(this.wrapper);
        this.showLoader();
        this.send();
        this.hideLoader();
        this.insert(this.wrapper);
    }

    /**
     * удалить всё содержимое цели
     */
    clear(target) {
        Utils.clearHtml(target);
    }

    /**
     * смена вида с табличного на плиточный или наоборот
     */
    changeView() {
        this.clear();
        this.showLoader();
        this.send();
        this.insert();
        this.hideLoader();
        this.findElement();
    }

    /**
     * Показ квартир по клику на кнопку показать еще.
     */
    more() {
        const that = this;
        this.showLoader();
        this.alreadyShows = this.alreadyShows + this.numberShows;
        this.send(
            {
                numberShows : this.numberShows,
                alreadyShows: this.alreadyShows
            },
            '../tests/parametric.json',
            {
                success(req) {
                    that.hideLoader();
                    const html = that.template(req); //пока не понятно как определить какой шаблон нужно использовать.
                    that.insert(target, html);
                },
                error(err) {
                    console.error(`ошибка на сервере: ${err}`);
                }
            }
        );
    }

    /**
     * Метод, который принимает отфильтрованные значения и полностью перерисовывает всю выдачу.
     * @param {object} filterData - отфильтрованные значения.
     */
    repaint(filterData) {
        const that = this;
        this.clear(this.wrapper);
        this.showLoader();
        this.send(
            {
                filterData: filterData
            },
            '../tests/parametric.json',
            {
                success(req) {
                    this.hideLoader();
                    const html = that.template(req); //пока не понятно как определить какой шаблон нужно использовать.
                    that.insert(target, html);
                },
                error(err) {
                    console.error(`ошибка на сервере: ${err}`);
                }
            }
        );
    }
}

export default ParamericResults;

/* временно */
/* eslint-enable */
