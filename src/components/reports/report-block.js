import $ from 'jquery';
import Mediator from 'common/scripts/mediator';
import Utils from '../../common/scripts/utils';

const mediator = new Mediator();

class ReportBlock {
    constructor() {
        this.target = null;
        this.inputs = null;

        /**
         * Данные о состоянии инпутов блока, полученные от сервера
         */
        this.inputsData = {};
        this.approveClass = 'b-report-block_status_approved';
        this.rejectClass = 'b-report-block_status_rejected';
        // возможно флаг не нужен
        this.isBlockApproved = false;
        // this.inputsStatus = [];
        this.inputsStatus = {};

        this.SUCCESS_STATUS = 1;
        this.FAIL_STATUS = 0;
    }

    init(options) {
        this.target = options.target;
        this.inputsData = options.inputsData;
        this.inputs = Array.from(this.target.querySelectorAll('input'));

        this._initInputs();
        this._bindEvents();
    }

    _bindEvents() {
        this.inputs.forEach((input) => {
            // Отсылаем введенные пользователем данные при изменении значения текстовых полей
            input.addEventListener('change', (event) => {
                this.onChange(event.target);
            });

            input.addEventListener('focus', (event) => {
                event.target.closest('.b-input-block').classList.remove('b-input-block_is_untouched');
            });
        });
    }

    _initInputs() {
        this.inputs.forEach((input) => {
            // Заполнение инпутов данными с сервера
            this.inputsData.fields.forEach((fieldData) => {
                if (input.id === fieldData.id) {
                    switch (input.type) {
                        case 'radio': {
                            input.checked = fieldData.checked;
                            if (input.checked) {
                                mediator.publish('radioChecked', input);
                            }
                            break;
                        }
                        default:
                            input.value = fieldData.value;
                            break;
                    }
                }
            });
            // Выставление статуса инпутам
            // this.inputsStatus.push(this._getInputStatus(input));
            this.inputsStatus[input.id] = this.getInputStatus(input);
        });
        this.setBlockStatus();
    }

    getInputStatus(input) {
        switch (input.type) {
            case 'radio': {
                const checkboxGroup = input.closest('.b-radio-row').querySelectorAll('input[type="radio"]');

                for (let i = 0; i < checkboxGroup.length; i++) {
                    if (checkboxGroup[i].checked) {
                        return 'filled';
                    }
                }

                if (input.closest('.b-radio-row').querySelector('input[type="radio"]')) {
                    return 'filled';
                }
                break;
            }
            default: {
                if (input.value) {
                    return 'filled';
                }
                break;
            }
        }

        return 'empty';
    }

    setBlockStatus() {
        for (const key in this.inputsStatus) {
            if (this.inputsStatus[key] !== 'filled') {
                this.isBlockApproved = false;
                this.target.dataset.approved = 'false';
                this.target.classList.remove(this.approveClass);
                mediator.publish('blockStatusChanged');

                return;
            }
        }

        this.isBlockApproved = true;
        this.target.dataset.approved = 'true';
        this.target.classList.add(this.approveClass);
        mediator.publish('blockStatusChanged');
    }

    onChange(input) {
        const dataToSend = `action=update&${$(input).serialize()}`;
        const that = this;

        Utils.send(dataToSend, '/tests/reports/input-update.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    // const errorMessage = response.request.errors.join('</br>');

                    // that.showErrorMessage(input, errorMessage);
                } else if (response.request.status === that.SUCCESS_STATUS) {
                    that.inputsStatus[input.id] = that.getInputStatus(input);
                    that.setBlockStatus();
                    // that.removeErrorMessage(input);
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }
}

export default ReportBlock;
