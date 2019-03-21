/* eslint-disable */
class InputDate {
    constructor(options) {
        const one = 1;
        const defShowMonth = 3;

        this.target = options.target;
        this.name = this._getName();

        this.showMonths = this.target.dataset.showMonths || defShowMonth;
        this.fromYear = this.target.dataset.fromYear || new Date().getFullYear();
        this.fromMonth = (this.target.dataset.fromMonth - one) || new Date().getMonth();

        this.activeDayClass = 'b-input-date__day_is_active';
        this.disabledDayClass = 'b-input-date__day_is_disabled';
        this.daySelector = '.j-input-date-day';
        this.monthsName = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
    }

    init() {
        this._initElements();
        this._bindEvents();
        this._getCalendar();

    }

    _initElements() {
        this.days = Array.from(document.querySelectorAll(this.daySelector));
    }

    _bindEvents() {
        this.days.forEach((day) => {
            day.addEventListener('click', (event) => {
                event.preventDefault();

                if (!this._activeDay(day)) {
                    return;
                }
            });
        });
    }

    _getCalendar() {
        const year = this.fromYear;
        const month = this.fromMonth;
        const sendDate = {month: []};

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

            sendDate.month[i] = {
                name : this.monthsName[activeDate.getMonth()],
                year : activeDate.getFullYear(),
                weeks: []
            };

            let week = [];
            let y = zero;

            while (activeDate.getMonth() === monthNumber) {
                week[y] = {
                    name    : activeDate.getDate(),
                    fullDate: `${activeDate.getDate()}.${monthNumber}.${activeDate.getFullYear()}`
                };

                if (this._getDay(activeDate) === sunday || this._getDay(activeDate) === saturday) {
                    week[y].disabled = true;
                }

                y = y + one;

                if (this._getDay(activeDate) % weekDays === six) {
                    sendDate.month[i].weeks.push(week);
                    week = [];
                    y = zero;
                }

                activeDate.setDate(activeDate.getDate() + one);
            }


            if (week.length) {
                sendDate.month[i].weeks.push(week);
            }
        }

        console.log(sendDate);
    }

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

    _getName() {
        const name = this.target.dataset.name;

        if (!name) {
            console.error('На найден аттрибут name в элементе', this.target);

            return false;
        }

        return name;
    }

    _activeDay(target) {
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
}

export default InputDate;
/* eslint-enable */
