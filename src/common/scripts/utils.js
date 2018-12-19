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
        const dataToSend = data;

        xhr.open('GET', url);

        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('x-requested-with', 'XMLHttpRequest');

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

    /**
     * Проверяет зашел ли юзер с мобильного браузера, используя строку вьюпорта
     * @return {boolean} - возращает true, если пользователь использует мобильный браузер
     */
    static isMobile() {
        let check = false;
        const userAgent = navigator.userAgent || navigator.vendor || window.opera;
        /* eslint-disable */
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(userAgent)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(userAgent.substr(0,4))) {
            check = true;
        }
        /* eslint-disable */
        return check;
    }
}

export default Utils;
