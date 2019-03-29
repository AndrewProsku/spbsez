/**
 * ЗАВИСИМОСТИ
 */
import Borders from './modules/borders';
import ionRangeSlider from 'ion-rangeslider'; // eslint-disable-line no-unused-vars
import Mediator from 'common/scripts/mediator';
import Utils from 'common/scripts/utils';

const mediator = new Mediator();

/**
 * КОМПОНЕНТ
 */
class RangeSlider {
    constructor(options) {
        this.outerOptions = options;
        this.rangeSlider = {};
        this.borders = new Borders();
    }

    /**
     * Инициализация слайдера
     */
    init() {
        this.setSettings(this.outerOptions);
        this.setOptions(this.sliderData);
        this.subscribes();
        this.$rangeSlider.ionRangeSlider(this.options);
        this.slider = this.$rangeSlider.data('ionRangeSlider');
        this.bindEvents();
        const id = this.slider.input.id;

        this.rangeSlider[id] = this.slider;
        this.initBorders();
        this.initDigit();

        this.slider.inputFrom = this.$inputFrom;
        this.slider.inputTo = this.$inputTo;
    }

    /**
     * Установка настроек
     * @param {Object} outerOptions - внешние данные слайдера
     */
    setSettings(outerOptions) {
        this.$wrapper = $(outerOptions.wrapper);
        this.$rangeSlider = this.$wrapper.find('.j-range-slider-base');
        this.sliderData = this.$rangeSlider.data();
        this.$inputs = this.$wrapper.find('.b-range-slider__input');
        this.$inputFrom = this.$inputs.first();
        this.$inputTo = this.sliderData.type === 'double' ? this.$inputs.last() : null;
        this.$labelFrom = this.$wrapper.find('.j-range-slider-min');
        this.$labelTo = this.$wrapper.find('.j-range-slider-max');
    }

    /**
     * Метод содержит в себе колбэки на события других модулей.
     */
    subscribes() {
        // Сброс слайдеров к дефолтному состоянию при сбросе фильтра в параметрическом.
        mediator.subscribe('paramfilterReset', () => {
            this.reset();
        });

        // Обновление слайдеров после получения данных от параметрического фильтра.
        mediator.subscribe('parametricfilterSend', (data) => {
            this.updateBorders(data);
        });
    }

    /**
     * Установка параметров
     * @param {Object} sliderData - данные слайдера
     */
    setOptions(sliderData) {
        this.options = {
            type            : sliderData.type,
            min             : sliderData.min,
            max             : sliderData.max,
            from            : sliderData.from,
            to              : sliderData.to,
            postfix         : sliderData.postfix,
            step            : sliderData.step,
            hide_from_to    : true, // eslint-disable-line camelcase
            hide_min_max    : true, // eslint-disable-line camelcase
            prettify_enabled: true, // eslint-disable-line camelcase
            onStart         : (data) => {
                mediator.publish('rangesliderStart', data, this);
            },
            onChange: (data) => {
                this.updateInputs(data);
                this.changeBorders(data);
                mediator.publish('rangesliderChange', data);
            },
            onFinish: (data) => {
                mediator.publish('rangesliderFinish', data);
            }
        };

        this.startOptions = this.options;
    }

    /**
     * Биндим события
     */
    bindEvents() {
        this.$inputFrom.on('change', this.onInputFromChange.bind(this));

        if (this.sliderData.type === 'double') {
            this.$inputTo.on('change', this.onInputToChange.bind(this));
        }

        this.$inputs.each((index, item) => {
            item.addEventListener('input', (event) => {
                const $target = $(event.target);
                const value = $target.val();

                this.validate($target);
                this.setDigit($target, value);
            });
        });
    }

    /**
     * Изменение инпута "от"
     * @param {Object} event - объект события
     */
    onInputFromChange(event) {
        // конвертируем в число, т.к после добавления разрядов получаем строку
        let val = this.getNumber($(event.target).val());
        const startValue = 0;
        const oneDecimal = 1;
        const type = $(event.target).data('validate');

        // Если это инпут с разрядами то округляем до 1 знака после запятой, а если нет то до 0
        const decimal = type === 'number_decimal' ? oneDecimal : startValue;

        // Если это одинарный инпут то шагом до сброса к мин и макс значению будет 0
        const step = this.sliderData.type === 'single' ? startValue : this.sliderData.step;

        if (val < this.sliderData.min) {
            val = this.sliderData.min;
        } else if (val > this.sliderData.max) {
            val = this.sliderData.to - step;
        }

        val = val.toFixed(decimal);

        $(event.target).val(val);

        // Конвертируем в число с разрядами
        this.setDigit($(event.target), val);
        this.slider.update({from: val});

        // Нужно заново переинициализировать бордеры, т.к update все вычишает
        this.initBorders();
    }

    /**
     * Изменение инпута "до"
     * @param {Object} event - объект события
     */
    onInputToChange(event) {
        // конвертируем в число, т.к после добавления разрядов получаем строку
        let val = this.getNumber($(event.target).val());
        const startValue = 0;
        const oneDecimal = 1;
        const type = $(event.target).data('validate');

        // Если это инпут с разрядами то округляем до 1 знака после запятой, а если нет то до 0
        const decimal = type === 'number_decimal' ? oneDecimal : startValue;

        if (val <= this.sliderData.from) {
            val = this.sliderData.from + this.sliderData.step;
        } else if (val > this.sliderData.max) {
            val = this.sliderData.max;
        }

        val = val.toFixed(decimal);

        $(event.target).val(val);
        // Конвертируем в число с разрядами, в данном случае не используется
        this.setDigit($(event.target), val);
        this.slider.update({to: val});

        // Нужно заново переинициализировать бордеры, т.к update все вычишает
        this.initBorders();
    }

    validate($target) {
        const type = $target.data('validate');

        if (!type) {
            return;
        }

        switch (type) {
            case 'number':
                this.validateNumber($target);
                break;
            case 'number_decimal':
                this.validateNumberDecimal($target);
                break;
            default:
                this.validateNumber();
                break;
        }
    }

    validateNumber($target) {
        const value = $target.val();
        const regex = /\d*/u;

        $target.val(regex.exec(value));
    }

    validateNumberDecimal(target) {
        const value = target.val();
        const regex = /\d*\.?\d?/gu;

        target.val(regex.exec(value));
    }

    /**
     * Обновление инпутов
     * @param {Object} data - данные рэйндж-слайдера
     */
    updateInputs(data) {
        const one = 1;
        const zero = 0;

        this.$inputFrom.val(data.from);
        this.setDigit(this.$inputFrom, data.from);

        if (this.sliderData.type === 'double') {
            this.$inputTo.val(data.to);
            this.setDigit(this.$inputFrom, data.to);
        }

        if (this.$labelFrom) {
            let valFrom = data.from_pretty;

            if (Number(valFrom) % one === zero) {
                valFrom = `${data.from_pretty}.0`;
            }

            this.$labelFrom.html(valFrom);
        }

        if (this.$labelTo) {
            let valTo = data.to_pretty;

            if (Number(valTo) % one === zero) {
                valTo = `${data.to_pretty}.0`;
            }

            this.$labelTo.html(valTo);
        }
    }

    /**
     * Сбрасывает range-slider до первоначального состояния
     */
    reset() {
        // Значения от и до в сдвоенном слайдере сбрасываются на мин и макс;
        // Дефолтное значение в одиночном считается макс
        const first = 0;

        // Если этот слайдер не учавствтует в поиске, то ничего делать не требуется
        if (this.slider.input.classList.contains('is-not-search')) {
            return;
        }

        if (this.slider.options.type === 'double') {
            this.startOptions.from = this.startOptions.min;
            this.startOptions.to = this.startOptions.max;

            this.slider.inputFrom[first].setAttribute('value', this.startOptions.from);
            this.slider.inputTo[first].setAttribute('value', this.startOptions.to);
            this.$inputFrom.val(this.startOptions.from);
            this.$inputTo.val(this.startOptions.to);
            this.$labelTo.html(this.startOptions.to);
            this.$labelFrom.html(this.startOptions.from);
        } else {
            this.startOptions.from = this.startOptions.max;
            this.slider.inputFrom[first].setAttribute('value', this.startOptions.max);
            this.$inputFrom.val(this.startOptions.max);
            this.$labelFrom.html(this.startOptions.from);
        }

        // Возвращаем слайдер на мин и макс позиции
        this.slider.update(this.startOptions);
        this.updateInputs(this.startOptions);

        // Нужно заново переинициализировать бордеры, т.к update все вычишает
        this.initBorders();
    }

    /**
     * Иницализация модуля для визуализации границ.
     */
    initBorders() {
        const maxBorder = this.sliderData.borderMax;
        const minBorder = this.sliderData.borderMin;

        this.slider.input.dataset.to = this.slider.result.to;
        this.slider.input.dataset.from = this.slider.result.from;

        if (minBorder && maxBorder) {
            this.borders.init(this.slider);
        }
    }

    /**
     * Изменение модуля визуализации границ при change слайдера.
     * @param {HTMLDivElement} data -  Экземпляр слайдера на котором произошло событие.
     */
    changeBorders(data) {
        const maxBorder = this.sliderData.borderMax;
        const minBorder = this.sliderData.borderMin;

        if (minBorder && maxBorder) {
            this.borders.change(data);
        }
    }

    /**
     * Обновление модуля визуализации границ при получении ответа от сервера.
     * @param {object} data - Данные с бэкэнда.
     */
    updateBorders(data) {
        data.forEach((item) => {
            for (const key in item) {
                if (this.rangeSlider.hasOwnProperty(key)) { // eslint-disable-line
                    const borderMin = item[key].border_min;
                    const borderMax = item[key].border_max;

                    if (borderMin && borderMax) {
                        // Обновляем требуемые для модуля 'border' дата-атрибуты;
                        this.slider.input.dataset.from = this.slider.result.from;
                        this.slider.input.dataset.to = this.slider.result.to;
                        this.slider.input.dataset.borderMin = borderMin;
                        this.slider.input.dataset.borderMax = borderMax;
                        this.borders.update(this.slider);
                    }
                }
            }
        });
    }

    /**
     * Метод инициализирует разряды для значений инпута
     */
    initDigit() {
        this.$inputs.each((index, item) => {
            const type = $(item).data('digit');

            if (type) {
                this.setDigit($(item));
            }
        });
    }

    /**
     * Метод устанавливает разряды для значений инпута
     * @param {Object} $input - изменяемый инпут
     * @param {String} value - значение инпута, при перемещении ручек приходит от слайдера.
     */
    setDigit($input, value = '') {
        const type = $input.data('digit');

        if (!type) {
            return;
        }

        const data = value ? value : $input.val();
        const number = this.getNumber(data);
        const digit = this.getDigit(number);

        $input.val(digit);
    }

    /**
     * Добавление разрядов к цифрам
     * @param {Number} number - число для конвертации
     * @returns {string} значение с разрядом
     */
    getDigit(number) {
        return String(number).replace(/\B(?=(\d{3})+(?!\d))/gu, ' ');
    }

    /**
     * Конвертация из строки с разрядами в число
     * @param {String} str - значение с разрядом
     * @returns {Number} - значение без разрядов
     */
    getNumber(str) {
        const zero = 0;

        if (!str) {
            return zero;
        }

        return Utils.convertToNumber(str);
    }
}

export default RangeSlider;
