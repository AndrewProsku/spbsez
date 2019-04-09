/**
 * @version 1.0alpha
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/forms/input-tel.html documentation
 */

/**
 * Dependencies
 */
import inputmask from 'inputmask';

/**
 * Модуль ввешает маску на input типом tel
 */
class InputTelephone {
    init(options) {
        if (options.mask) {
            this.mask = options.mask;
        } else {
            const lang = document.documentElement.getAttribute('lang') || 'ru';

            this.mask = lang === 'ru' ? '+7 999 999-99-99' : '+999 9999999999999';
        }

        this.input = options.input;

        this.setMask();
    }

    /**
     * Устанавливаем маску на инпут
     */
    setMask() {
        inputmask({mask: this.mask}).mask(this.input);
    }
}

export default InputTelephone;
