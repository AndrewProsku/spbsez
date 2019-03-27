import InputTel from '../../components/forms/telephone/telephone';
import templatePass from './templates/form-add-pass.twig';
import Utils from '../../common/scripts/utils';

class RequestPass {
    constructor() {
        this.$administrators = document.querySelector('.j-request-pass');
        this.$passContainer = document.querySelector('.j-pass-container');
        this.$addPassButton = document.querySelector('.j-add-pass');

        this.deletePassClass = 'j-delete-pass';
        this.inputCalendarSelector = '.j-input-calendar';
        this.containerCalendar = document.querySelector('.j-form-block-calendars');
        this.calendarSelector = '.j-input-date';
        // 0 - close, 1 - open
        this.statusCalendars = 0;
    }

    init() {
        this.bindEvents();
        this.initTelInput();
        this.initDateInput();
        this._initDateCalendar();
    }

    bindEvents() {
        // Добавление контактного лица
        this.$addPassButton.addEventListener('click', () => {
            this.addPass();
        });

        window.onresize = () => {
            this._hideAllCalendar();
            this._showContainerCalendar();
            this.statusCalendars = 0;
        };

        Utils.clickOutside([this.calendarSelector, this.inputCalendarSelector], () => {
            this._hideAllCalendar();
            this.statusCalendars = 0;
        });
    }

    initTelInput() {
        const inputTel = new InputTel();
        const telInputs = Array.from(this.$administrators.querySelectorAll('input[type="tel"]'));

        inputTel.init({input: telInputs});
    }

    initDateInput() {
        const inputDate = new InputTel();
        const dateInputs = Array.from(this.$administrators.querySelectorAll('input[data-date="true"]'));

        inputDate.init({
            input: dateInputs,
            mask : '99.99.9999, 99:99'
        });
    }

    addPass() {
        Utils.insetContent(this.$passContainer, templatePass());
        this.initDelete();
    }

    initDelete() {
        Array.from(document.querySelectorAll(`.${this.deletePassClass}`)).forEach(($delete) => {
            $delete.addEventListener('click', () => {
                Utils.removeElement($delete.parentNode);
            });
        });
    }

    _initDateCalendar() {
        const inputCalendars = Array.from(document.querySelectorAll(this.inputCalendarSelector));

        if (!inputCalendars) {
            return;
        }

        inputCalendars.forEach((input) => {
            input.addEventListener('click', (event) => {
                event.preventDefault();

                const classCalendar = input.dataset.dateClass;
                const calendar = document.querySelector(`.${classCalendar}`);

                if (!calendar) {
                    return;
                }

                if (window.innerWidth >= 960) {
                    if (this.statusCalendars) {
                        this._hideContainerCalendar();
                        this._hideAllCalendar();
                    } else {
                        this._showContainerCalendar();
                        this._showAllCalendar();
                    }
                } else {
                    this._hideAllCalendar(calendar);
                    this._toggleCalendar(calendar);
                }
            });
        });
    }

    _toggleCalendar(calendar) {
        if (calendar.style.display === 'none' || !calendar.style.display) {
            Utils.show(calendar);
        } else {
            Utils.hide(calendar);
        }
    }

    _hideAllCalendar(beside) {
        const calendars = Array.from(document.querySelectorAll(this.calendarSelector));

        calendars.forEach((calendar) => {
            if (beside === calendar) {
                return;
            }

            Utils.hide(calendar);
        });
    }

    _showAllCalendar() {
        const calendars = Array.from(document.querySelectorAll(this.calendarSelector));

        calendars.forEach((calendar) => {
            Utils.show(calendar);
        });
    }

    _showContainerCalendar() {
        if (!this.containerCalendar) {
            return;
        }

        Utils.show(this.containerCalendar);
        this.statusCalendars = 1;
    }

    _hideContainerCalendar() {
        if (!this.containerCalendar) {
            return;
        }

        Utils.hide(this.containerCalendar);
        this.statusCalendars = 0;
    }
}

export default RequestPass;

