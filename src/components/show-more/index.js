/**
 * @version 1.0
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/
 */

import Language from '../language';
import Utils from 'common/scripts/utils';

const Lang = new Language();

class ShowMore {
    constructor() {
        // шаблон элемента
        this.template = null;

        // кнопка
        this.button = null;

        // путь до json'а
        this.url = '';

        // нода, в которую будут добавляться элементы
        this.target = null;

        // шаг для передачи на сервер
        this.step = 1;

        // счетчик шага
        this.counter = 1;

        // число, по сколько элементов присылать с сервера
        this.number = null;
    }

    /**
     * Инициализирует модуль
     * @param {object} options - опции для кнопки
     */
    init(options) {
        this.template = options.template;
        this.url = options.url;
        this.target = options.target;
        this.button = options.button;
        this.number = options.number;
        this._bindEvents();
    }

    /**
     * Биндит события
     * @private
     */
    _bindEvents() {
        this.button.addEventListener('click', () => {
            this._send({
                step  : this.step,
                number: this.number
            });
        }, false);
    }

    /**
     * Отправляет запрос на сервер
     * @param {object} data - шаг и количество
     * @private
     */
    _send(data) {
        Utils.send(data, this.url, {
            success: (respond) => {
                if (typeof respond !== 'object') {
                    console.error('Неверные данные');

                    return;
                }
                // respond = [{}, {}, {}]
                this._insertContent(respond.data);
                this._stepsCount();
                this._changeText(respond.request.next);

                if (respond.request.isLast) {
                    this._removeButton();
                }
            }
        });
    }

    /**
     * Вставляет контент в нужное место
     * @param {object} respond - данные с сервера
     * @private
     */
    _insertContent(respond) {
        respond.forEach((content) => {
            Utils.insetContent(this.target, this.template(content));
        });
    }

    /**
     * Считает шаг для передачи на сервер
     * @private
     */
    _stepsCount() {
        this.step = this.counter + this.step;
    }

    /**
     * Меняет текст в кнопке
     * @param {string} text - количество элементов для следующего шага
     * @private
     */
    _changeText(text) {
        this.button.innerHTML = `${Lang.get('showMore.button')} ${text}`;
    }

    /**
     * Удаляет кнопку
     * @private
     */
    _removeButton() {
        this.button.remove();
    }
}

export default ShowMore;
