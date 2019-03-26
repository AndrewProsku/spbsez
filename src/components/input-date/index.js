import 'ion-rangeslider';
import $ from 'jquery';
import tmplMonths from './templates/months.twig';
import Utils from '../../common/scripts/utils';

class InputDate {
    /* eslint-disable */
    constructor(options) {
        const defShowMonth = 3;

        this.target = options.target;

        this.showMonths = this.target.dataset.showMonths || defShowMonth;
        this.fromYear = this.target.dataset.fromYear || new Date().getFullYear();
        this.fromMonth = this._getDefaultMonth();

        this.activeDayClass = 'b-input-date__day_is_active';
        this.disabledDayClass = 'b-input-date__day_is_disabled';

        this.mainSelector = '.j-input-date';
        this.contentSelector = '.j-input-date-content';
        this.daySelector = '.j-input-date-day';
        this.timeSelector = '.j-input-date-time';
        this.valueSelector = `.${this.target.dataset.valueSelector}` || '.j-input-value';

        this.monthsName = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

        this.data = '';
        this.time = '';
    }

    init() {
        this._initElements();
        this._displayCalendar();
        this._initSlider();
        this._bindEvents();
        this._displayDateTime();
    }

    _initElements() {
        this.main = document.querySelector(this.mainSelector);
        this.content = this.target.querySelector(this.contentSelector);
        this.value = document.querySelector(this.valueSelector);
    }

    _bindEvents() {
        this.days = Array.from(this.target.querySelectorAll(this.daySelector));

        this.days.forEach((day) => {
            day.addEventListener('click', (event) => {
                event.preventDefault();

                if (!this._toActiveDay(day)) {
                    return;
                }

                this.date = day.dataset.fullDate;
                this._displayDateTime();
            });
        });

        this.value.addEventListener('click', (event) => {
            event.preventDefault();

            this._toggleCalendar();
        });
    }

    _displayCalendar() {
        const calendar = this._getCalendar();

        Utils.insetContent(this.content, tmplMonths(calendar));
    }

    /* eslint-disable max-statements */

    // Возращает объект calendar месяцы разделеные на недели, дни
    _getCalendar() {
        const year = this.fromYear;
        const month = this.fromMonth;
        const calendar = {months: []};
        const now = new Date();
        let activeFount = false;

        for (let i = 0; i < this.showMonths; i++) {
            const activeMonth = month + i;
            const activeDate = new Date(year, activeMonth);
            const monthNumber = activeDate.getMonth();
            const one = 1;
            const zero = 0;
            const six = 6;
            const saturday = 5;
            const sunday = 6;
            const weekDays = 7;

            calendar.months[i] = {
                name : this.monthsName[activeDate.getMonth()],
                year : activeDate.getFullYear(),
                weeks: []
            };

            let week = {days: []};
            let y = zero;

            while (activeDate.getMonth() === monthNumber) {
                const dayPretty = `0${activeDate.getDate()}`.slice(-2);
                const monthPretty = `0${monthNumber}`.slice(-2);

                week.days[y] = {
                    name    : activeDate.getDate(),
                    fullDate: `${dayPretty}.${monthPretty}.${activeDate.getFullYear()}`
                };

                if (this._getDay(activeDate) === sunday || this._getDay(activeDate) === saturday) {
                    week.days[y].disabled = true;
                }

                if (now >= activeDate) {
                    week.days[y].disabled = true;
                }

                if (!activeFount && !week.days[y].disabled) {
                    week.days[y].active = true;
                    this.date = week.days[y].fullDate;
                    activeFount = true;
                }

                y = y + one;

                if (this._getDay(activeDate) % weekDays === six) {
                    calendar.months[i].weeks.push(week);
                    week = {days: []};
                    y = zero;
                }

                activeDate.setDate(activeDate.getDate() + one);
            }


            if (week.days.length) {
                calendar.months[i].weeks.push(week);
            }
        }

        return calendar;
    }

    /* eslint-enable max-statements */

    _getDay(date) {
        const firstDay = 0;
        const lastDay = 7;
        const one = 1;
        let day = date.getDay();

        if (day === firstDay) {
            day = lastDay;
        }

        return day - one;
    }

    _getDefaultMonth() {
        const one = 1;
        const month = this.target.dataset.fromMonth;

        if (!month) {
            return new Date().getMonth();
        }

        return month - one;
    }

    _toActiveDay(target) {
        if (target.classList.contains(this.disabledDayClass)) {
            return false;
        }

        if (target.classList.contains(this.activeDayClass)) {
            return false;
        }

        this.days.forEach((day) => {
            day.classList.remove(this.activeDayClass);
        });

        target.classList.add(this.activeDayClass);

        return target;
    }

    _initSlider() {
        const that = this;
        const active = 10;
        const timeValues = ['10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45', '12:00',
            '12:15', '12:30', '12:45', '13:00', '13:15', '13:30', '13:45', '14:00', '14:15', '14:30',
            '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15', '16:30', '16:45', '17:00',
            '17:15', '17:30', '17:45', '18:00'
        ];

        this.time = timeValues[10];

        $(this.timeSelector).ionRangeSlider({
            values  : timeValues,
            from    : active,
            onChange: function (data) {
                that.time = data.from_value;
                that._displayDateTime();
            }
        });
    }

    _displayDateTime() {
        if (!this.value) {
            return;
        }

        this.value.value = `${this.date}, ${this.time}`;
    }

    _toggleCalendar() {
        if (this.main.style.display === 'none' || !this.main.style.display) {
            Utils.show(this.main);
        } else {
            Utils.hide(this.main);
        }
    }
}

/* eslint-enable */

export default InputDate;

