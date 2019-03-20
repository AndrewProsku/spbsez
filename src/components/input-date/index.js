class InputDate {
    constructor(options) {
        this.target = options.target;
        this.name = this._getName();

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

                console.log('hi');
            });
        });
    }

    _getCalendar() {
        const rangeMonth = 5;
        const d = new Date(2018, 10);
        const year = d.getFullYear();
        const month = d.getMonth();

        const date = {month: []};
        const test = {
            months: [{
                name: 'Март',
                year: 2019,
                weeks: [{
                    days: [{
                        name: 1,
                        fullDate: '01.03.2019',
                        disabled: true
                    }, {
                        name: 2,
                        fullDate: '02.03.2019',
                        active: true
                    }]
                }, {
                    days: [{
                        name: 1,
                        fullDate: '01.03.2019'
                    }, {
                        name: 1,
                        fullDate: '01.03.2019'
                    }]
                }]
            }]
        };

        for (let i = 0; i < rangeMonth; i++) {
            const activeMonth = month + i;
            const activeDate = new Date(year, activeMonth);
            const monthNumber = activeDate.getMonth();
            const one = 1;
            const six = 6;
            const seven = 7;

            date.month[i] = {
                name: this.monthsName[activeDate.getMonth()],
                year: activeDate.getFullYear(),
                weeks: []
            };

            let week = [];

            let y = 0;

            while (activeDate.getMonth() === monthNumber) {
                week[y] = {
                    name: activeDate.getDate(),
                    fullDate: `${activeDate.getDate()}.${monthNumber}.${activeDate.getFullYear()}`
                };

                if (this._getDay(activeDate) === six || this._getDay(activeDate) === 5) {
                    week[y].disabled = true;
                }

                y = y + one;

                if (this._getDay(activeDate) % seven === six) {
                    date.month[i].weeks.push(week);
                    week = [];
                    y = 0;
                }

                activeDate.setDate(activeDate.getDate() + one);
            }


            if (week.length) {
                date.month[i].weeks.push(week);
            }
        }

        console.log(date);
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
