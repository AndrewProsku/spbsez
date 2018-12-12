/**
 * @version 1.4
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/utils.html documentation
 */

/**
 * DEPENDENCIES
 */

class Utils {
    /**
     * Метод устанавливает комфорную задержку выполнения анимации.
     * @return {Number} comfortableAnimationTimeValue - значение в мс.
     */
    static comfortableAnimationTime() {
        const comfortableAnimationTimeValue = 300;

        return comfortableAnimationTimeValue;
    }

    /**
     * Метод полностью очищает весь html элемент.
     * @param {Object} element - DOM-элемент, который необходимо очистить.
     */
    static clearHtml(element) {
        element.innerHTML = '';
    }

    /**
     * Метод вставляет содержимое в блок.
     * @param {Object} element - элемент в который нужно вставить.
     * @param {Object/string} content - вставляемый контент.
     */
    static insetContent(element, content) {
        if (typeof content === 'string') {
            element.insertAdjacentHTML('beforeend', content);
        } else if (typeof content === 'object') {
            element.appendChild(content);
        }
    }

    /**
     * Метод полностью удаляет элемент из DOM-дерева.
     * @param {Object} element - элемент, который необходимо удалить.
     */
    static removeElement(element) {
        // node.remove() не работает в IE11
        element.parentNode.removeChild(element);
    }


    /**
     * Метод показывает элемент.
     * @param {Node} element - элемент, который необходимо показать.
     */
    static show(element) {
        element.style.display = 'block';
    }

    /**
     * Метод скрывает элемент.
     * @param {Node} element - элемент, который необходимо скрыть.
     */
    static hide(element) {
        element.style.display = 'none';
    }

    /**
     * Метод отправляет ajax запрос на сервер.
     * @param {Object} data - отправляемые данные.
     * @param {String} url - маршрут по которому нужно произвести запрос.
     * @param {Function} callback -  функция обратного вызова, которая при успехе вызовет success, а при ошибке error.
     */
    static send(data, url, callback = function() {}) {
        const xhr = new XMLHttpRequest();
        const statusSuccess = 200;
        let dataToSend = data;

        xhr.open('GET', url);

        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('x-requested-with', 'XMLHttpRequest');

        if (typeof dataToSend === 'object') {
            dataToSend = $.param(dataToSend);
        }

        xhr.send(dataToSend);

        xhr.onload = function XHR() {
            if (xhr.status === statusSuccess) {
                const req = JSON.parse(this.responseText);

                callback.success(req);
            } else {
                callback.error(xhr.status);
            }
        };
    }

    /**
     * Метод проверяет наличие интернета
     * @return {boolean} - При наличии результатом будет true, а при отсутсвии false.
     */
    static checkInternetConnection() {
        return navigator.onLine;
    }

    /**
     * Метод проверяет присутствует ли ключ в объекте
     * @param {Object} object - проверяем объект
     * @param {String} key - ключ, наличие которого проверяет в объекте
     * @return {boolean} - присутствует или нет ключ в объекте
     */
    static keyExist(object, key) {
        return Object.prototype.hasOwnProperty.call(object, key);
    }

    /**
     * Метод проверяет пустой объект или нет
     * @param {Object} object - объект проверяемый на пустоту
     * @return {boolean} - true если пустой и false если полный
     */
    static isEmptyObject(object) {
        const empty = 0;

        return Object.keys(object).length === empty;
    }

    /**
     * Проверяет переданные данные на строку
     * @param {String} string - данные на проверку
     * @return {boolean} - возращает true, если строка, и false наоборот
     */
    static isString(string) {
        return typeof string === 'string';
    }

    /**
     * Узнает index элемента в родительской элемент
     * Аналог jquery.index()
     * @param {Node} element - искомый элемент
     * @return {number} - порядковый номер (индекс) в родительском элементе
     */
    static getElementIndex(element) {
        return Array.from(element.parentNode.children).indexOf(element);
    }

    /**
     * Проверяет, поддерживает ли устройство touch-события
     * @return {boolean} - возращает true, если Touch-устройство, и false наоборот
     */
    static isTouch() {
        return Boolean(typeof window !== 'undefined' &&
            ('ontouchstart' in window ||
                (window.DocumentTouch &&
                    typeof document !== 'undefined' &&
                    document instanceof window.DocumentTouch))) ||
            Boolean(typeof navigator !== 'undefined' && (navigator.maxTouchPoints || navigator.msMaxTouchPoints));
    }

    /**
     * Узнает находится ли элемент во вьюпорте
     * @param {Node} element - искомый элемент
     * @return {boolean} - возращает true, если элемент виден на экране, и false наоборот
     */
    static isInViewport(element) {
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const windowWidth = window.innerWidth || document.documentElement.clientWidth;
        const belowViewport = 0;

        const verticalInView = (rect.top <= windowHeight) && ((rect.top + rect.height) >= belowViewport);
        const horizontalInView = (rect.left <= windowWidth) && ((rect.left + rect.width) >= belowViewport);

        return verticalInView && horizontalInView;
    }
}

export default Utils;
