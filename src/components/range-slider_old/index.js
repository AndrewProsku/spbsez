/**
 * ЗАВИСИМОСТИ
 */
import 'ion-rangeslider/css/ion.rangeSlider.css';
import 'ion-rangeslider/css/ion.rangeSlider.skinHTML5.css';
import './range-slider.scss';
import ionRangeSlider from 'ion-rangeslider'; // eslint-disable-line no-unused-vars
import Mediator from 'common/scripts/mediator';

const mediator = new Mediator();

/**
 * КОМПОНЕНТ
 */
class RangeSlider {
    constructor() {
        this.million = 1000000;
        this.millionConvert = 1.0e+6;
        this.decimal = 100;
        this.decimalCount = 1;
    }

    init(options) {
        const that = this;

        this.$target = $(options.target);
        this.sliderData = this.$target.data();
        this.$inputs = this.$target.find('input');
        this.inputsCount = 1;
        this.type = this.$inputs.length === this.inputsCount ? 'single' : 'double';
        this.$inputFrom = this.$inputs.first();
        this.$inputTo = this.type === 'double' ? this.$inputs.last() : null;
        this.options = {
            type   : this.type,
            min    : this.sliderData.min,
            max    : this.sliderData.max,
            from   : this.sliderData.from,
            to     : this.sliderData.to,
            postfix: this.sliderData.postfix,
            prettify(num) {
                if (num > that.million && that.sliderData.shortformat) {
                    const millionShort = Math.abs(Number(num)) / that.millionConvert;
                    const decimalRemove = Math.round(millionShort * that.decimal) / that.decimal;

                    return parseFloat(decimalRemove).toFixed(that.decimalCount)
                        .replace('.', ',');
                }

                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/u, ' ');
            },
            onStart() {
                setTimeout(() => {
                    that.addPrefix('from', 'от');
                    that.addPrefix('to', 'до');
                });
            },
            onChange(data) {
                that.updateInputs(data);
                that.addPrefix('from', 'от');
                that.addPrefix('to', 'до');
            },
            onFinish() {
                mediator.publish('sliderChanged');
                that.change();
            }
        };

        this.$inputFrom.ionRangeSlider(this.options);
        this.slider = this.$inputFrom.data('ionRangeSlider');
    }

    /**
     * Генерит событие change на первом инпуте, нужно для форм в которых находится range-slider
     */
    change() {
        const change = new Event('change');
        const input = 0;

        this.$inputFrom[input].dispatchEvent(change);
    }

    updateInputs(data) {
        this.$inputFrom.val(data.from);

        if (this.type === 'double') {
            this.$inputTo.val(data.to);
        }
    }

    /**
     * Сбрасывает range-slider до первоначального состояния
     */

    reset() {
        this.slider.update(this.options);
    }

    addPrefix(type, text) {
        $(`.j-${type}`).remove();
        $(`.irs-${type}`).prepend(`<span class=j-${type}>${text} </span>`);
    }
}

export default RangeSlider;
