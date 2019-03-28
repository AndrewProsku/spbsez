import $ from 'jquery';
import InputFile from 'components/forms/file';
import Mediator from 'common/scripts/mediator';
import Select from 'components/forms/select';
import templateError from './templates/input-error.twig';
import templateExportForm from './templates/export-countries.twig';
import templateInnovationForm from './templates/innovations.twig';
import templatePermissionForm from './templates/permission-form.twig';
import templateStageForm from './templates/stage-block.twig';
import templateTooltip from 'components/tooltip/custom-tooltip.twig';
import Tooltip from 'components/tooltip/';
import Utils from '../../common/scripts/utils';

const mediator = new Mediator();

class ReportBlock {
    constructor() {
        this.target = null;
        this.inputs = null;

        this.approveClass = 'b-report-block_status_approved';
        this.errorClass = 'b-report-block_status_error';
        this.untouchedIputClass = 'b-input-block_is_untouched';
        this.stageBlockClass = 'j-report-stage-block';
        this.stageSelectClass = 'j-reports-form-select';
        this.permissionFormClass = 'j-permission-form';
        this.stageAddButtonClass = 'j-report-stage-add';
        this.stageDeleteButtonClass = 'j-delete-stage';
        this.fileInputClass = 'j-file-input-block';
        this.resultDeleteButtonClass = 'j-delete-result';
        this.disabledInputClass = 'b-input-block_is_disabled';

        /**
         * Данные о состоянии инпутов блока, полученные от сервера
         */
        this.inputsData = {};
        this.inputsStatus = {};

        this.SUCCESS_STATUS = 1;
        this.FAIL_STATUS = 0;
        this.reportId = 0;
        this.baseUrl = '/api/report/';
    }

    /* eslint-disable max-statements, max-lines-per-function */
    init(options) {
        this.target = options.target;
        this.formID = options.formID;
        this.reportId = options.reportId || 0;
        this.baseUrl = options.baseUrl || this.baseUrl;
        this.isReadonly = options.isReadonly || false;
        this.isRejected = options.blockData.rejected || false;
        const blockData = options.blockData;
        const blockTypes = {
            'foreign-investors' : 'initForeignInvestorsBlock',
            taxes               : 'initTaxesBlock',
            'construction-stage': 'initConstructionStageBlock',
            'export-countries'  : 'initExportCountriesBlock',
            innovations         : 'initInnovationsBlock',
            results             : 'initResultBlock'
        };

        mediator.subscribe('formApproved', (formID) => {
            this.approveFormHandler(formID);
        });

        const methodName = Object.prototype.hasOwnProperty.call(blockTypes, blockData.type) ?
            blockTypes[blockData.type] :
            false;

        if (methodName && typeof this[methodName] === 'function') {
            this[methodName](blockData);
        } else {
            this.inputsData = blockData;
        }

        this.inputs = Array.from(this.target.querySelectorAll('input, select, textarea'));
        this._getInputsValues();
        this._bindInputsEvents(this.inputs);

        if (this.isReadonly) {
            this.inputs.forEach((input) => {
                input.disabled = true;
            });
        }

        // Инициализация тултипов
        this.initTooltips();
    }
    /* eslint-enable max-statements, max-lines-per-function */

    approveFormHandler(formID) {
        if (formID === this.formID) {
            this.inputs.forEach((input) => {
                delete input.dataset.prefilled;

                if (!input.closest(`.${this.permissionFormClass}`) &&
                    !input.closest(`.${this.disabledInputClass}`)) {
                    this.inputsStatus[input.id] = this.getInputStatus(input);
                }
            });
            this.setBlockStatus();
        }
    }

    initForeignInvestorsBlock(data) {
        this.inputsData.fields = [];
        const radios = Array.from(this.target.querySelectorAll('input[type=radio]'));
        const investorCountries = this.target.querySelector('.j-foreign-investors-field');
        const investorCountriesField = investorCountries.querySelector('input');

        radios.forEach((radio) => {
            radio.addEventListener('change', (event) => {
                radios.forEach((input) => {
                    delete input.dataset.prefilled;
                });
                delete investorCountriesField.dataset.prefilled;

                if (event.target.value === 'yes') {
                    investorCountries.classList.remove('b-input-block_is_disabled');
                    investorCountriesField.disabled = false;

                    this.inputsStatus['investors-countries'] = this.getInputStatus(investorCountriesField);
                    this.setBlockStatus();
                } else {
                    investorCountries.classList.add('b-input-block_is_disabled');
                    investorCountriesField.disabled = true;

                    delete this.inputsStatus['investors-countries'];
                    this.setBlockStatus();
                }
            });
        });
        data.fields.forEach((field) => {
            if (field.id === 'foreign-investors-yes' && field.checked) {
                investorCountries.classList.remove('b-input-block_is_disabled');
                investorCountriesField.disabled = false;
            }
            if (field.id === 'foreign-investors-yes' ||
                field.id === 'foreign-investors-no') {
                this.inputsData.fields.push(field);
            } else if (field.id === 'investors-countries') {
                if (!investorCountriesField.disabled) {
                    this.inputsData.fields.push(field);
                }
            }
        });
        investorCountriesField.addEventListener('change', () => {
            radios.forEach((radio) => {
                delete radio.dataset.prefilled;
                this.inputsStatus[radio.id] = this.getInputStatus(radio);
            });
            delete investorCountriesField.dataset.prefilled;
            this.inputsStatus[investorCountriesField.id] = this.getInputStatus(investorCountriesField);
        });
    }

    initTaxesBlock(data) {
        this.inputsData = data;
        const taxesAllInput = this.target.querySelector('#taxes-all');
        const taxesYearInput = this.target.querySelector('#taxes-year');
        let allTaxes = 0;
        let yearTaxes = 0;
        const NOT_FOUND = -1;

        const taxesAllInputs = Array.from(this.target.querySelectorAll('.j-taxes-all input'));
        const taxesYearInputs = Array.from(this.target.querySelectorAll('.j-taxes-year input'));

        taxesAllInputs.forEach((input, i, inputs) => {
            this.calculateTaxes(input, inputs, taxesAllInput);
        });
        taxesYearInputs.forEach((input, i, inputs) => {
            this.calculateTaxes(input, inputs, taxesYearInput);
        });

        this.inputsData.fields.forEach((field) => {
            if (field.id.indexOf('-all') !== NOT_FOUND) {
                allTaxes += Number(field.value);
            } else if (field.id.indexOf('-year') !== NOT_FOUND) {
                yearTaxes += Number(field.value);
            }
        });
        taxesAllInput.value = allTaxes;
        taxesYearInput.value = yearTaxes;
    }

    calculateTaxes(currentInput, allInputs, resultInput) {
        let previousValue = currentInput.value;

        currentInput.addEventListener('focus', (event) => {
            previousValue = event.target.value;
        });
        currentInput.addEventListener('change', (event) => {
            if ($.isNumeric(event.target.value)) {
                let newValue = 0;

                allInputs.forEach((input) => {
                    newValue += Number(input.value);
                });
                resultInput.value = newValue;
            } else {
                event.target.value = previousValue;
            }
        });
    }

    initConstructionStageBlock(data) {
        this.inputsData.fields = [];
        data.stages.forEach((stage) => {
            this.inputsData.fields.push(stage.fields);
            let hasExtraForm = false;

            stage.fields.forEach((field) => {
                this.inputsData.fields.push(field);
                // eslint-disable-next-line no-magic-numbers
                if (field.id.indexOf('construction-stage') !== -1) {
                    hasExtraForm = field.value === 'stage4' || field.value === 'stage6';
                }
            });
            // eslint-disable-next-line no-magic-numbers
            const isStagesDeletable = data.stages.length > 1 && !this.isReadonly;

            this.insertStageForm(stage.stageID, hasExtraForm, isStagesDeletable);
        });

        if (!this.isReadonly) {
            this.addStage();
        }
    }

    initExportCountriesBlock(data) {
        this.inputsData.fields = [];
        data.groups.forEach((group) => {
            this.inputsData.fields.push(group.fields);
            group.fields.forEach((field) => {
                this.inputsData.fields.push(field);
            });
            // eslint-disable-next-line no-magic-numbers
            const isGroupDeletable = data.groups.length > 1 && !this.isReadonly;

            this.insertExportGroupForm(group.ID, isGroupDeletable);
        });

        if (!this.isReadonly) {
            this.addExportGroup();
        }
    }

    initInnovationsBlock(data) {
        this.inputsData.fields = [];
        data.innovations.forEach((innovation) => {
            this.inputsData.fields.push(innovation.fields);
            innovation.fields.forEach((field) => {
                this.inputsData.fields.push(field);
            });
            // eslint-disable-next-line no-magic-numbers
            const isInnovationDeletable = data.innovations.length > 1 && !this.isReadonly;

            this.insertInnovationForm(innovation.ID, isInnovationDeletable);
        });
        if (!this.isReadonly) {
            this.addInnovation();
        }
    }

    initResultBlock(data) {
        if (!this.isReadonly) {
            const deleteButton = this.target.querySelector(`.${this.resultDeleteButtonClass}`);

            deleteButton.addEventListener('click', (event) => {
                const that = this;
                const dataToSend = `action=delResult&id=${event.target.dataset.id}`;

                Utils.send(dataToSend, '/tests/reports/input-update.json', {
                    success(response) {
                        if (response.request.status === that.FAIL_STATUS) {
                            return;
                        }

                        Utils.removeElement(that.target);
                        mediator.publish('resultBlockDeleted', that.formID);
                        mediator.publish('blockStatusChanged', that.formID);
                        delete this;
                    },
                    error(error) {
                        console.error(error);
                    }
                });
            });
        }

        this.inputsData = data;
    }

    initSelect(input) {
        if (this.isReadonly) {
            input.disabled = true;
        }
        new Select({
            element: $(input),

            disableSearch: true
        }).init();
        // eslint-disable-next-line no-magic-numbers
        if (!this.isReadonly && (input.id.indexOf('construction-stage') !== -1)) {
            input.onchange = this.stageSelectHandler.bind(this);
        }
    }

    initTooltips() {
        const helpTooltips = Array.from(this.target.querySelectorAll('.j-help'));

        if (helpTooltips.length) {
            helpTooltips.forEach((helpTooltip) => {
                const tooltip = new Tooltip();

                tooltip.init({
                    target  : helpTooltip,
                    template: templateTooltip
                });
            });
        }
    }

    /**
     * Инициализация кастомных инпутов
     * Отправка значений инпутов при их изменении
     * @param {Array} inputs - массив со всеми инпутами блока
     */
    _bindInputsEvents(inputs) {
        inputs.forEach((input) => {
            switch (input.type) {
                case 'text':
                case 'email':
                case 'tel':
                case 'textarea': {
                    this.bindTextInputsEvents(input);
                    break;
                }
                case 'file': {
                    this.initFileInputsEvents(input);

                    break;
                }
                case 'select-one': {
                    this.initSelect(input);
                    break;
                }
                case 'radio': {
                    input.addEventListener('change', (event) => {
                        if (Utils.keyExist(input.dataset, 'prefilled')) {
                            const checkboxGroup = input.closest('.b-radio-row').querySelectorAll('input[type="radio"]');

                            Array.from(checkboxGroup).forEach((checkbox) => {
                                delete checkbox.dataset.prefilled;
                            });
                        }
                        this.sendNewValue(event.target);
                    });
                    break;
                }
                default: break;
            }
        });
    }

    bindTextInputsEvents(input) {
        input.addEventListener('focus', (event) => {
            event.target.closest('.b-input-block').classList.remove(this.untouchedIputClass);
        });
        input.addEventListener('blur', (event) => {
            if (Utils.keyExist(input.dataset, 'prefilled')) {
                event.target.closest('.b-input-block').classList.add(this.untouchedIputClass);
            }
        });
        input.addEventListener('change', (event) => {
            delete input.dataset.prefilled;
            this.sendNewValue(event.target);
        });
    }

    initFileInputsEvents(input) {
        const that = this;
        const fileInputBlock = input.closest('.b-input-block');
        const fileInput = new InputFile();

        fileInput.init({
            target: fileInputBlock
        });

        input.addEventListener('change', (event) => {
            event.target.closest('.b-input-block').classList.remove(this.untouchedIputClass);
            const formData = new FormData(event.target.closest('form'));

            Utils.send(formData, '/tests/reports/input-update.json', {
                success(response) {
                    if (!response.request.status === that.SUCCESS_STATUS) {
                        return true;
                    }

                    return true;
                },
                error(error) {
                    console.error(error);
                }
            });
        });

        fileInputBlock.querySelector('.b-input-file__delete').addEventListener('click', (event) => {
            event.preventDefault();
            const permissionForm = event.target.closest(`.${that.permissionFormClass}`);
            const dataToSend = `action=delPermissionDoc&id=${permissionForm.dataset.stageId}`;

            Utils.send(dataToSend, '/tests/reports/input-update.json', {
                success(response) {
                    if (!response.request.status === that.SUCCESS_STATUS) {
                        return true;
                    }

                    return true;
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    /* eslint-disable max-lines-per-function, max-statements */
    /**
     * Заполнение инпутов данными с сервера и выставлнеие статуса блоку формы (зеленый фон)
     */
    _getInputsValues() {
        this.inputs.forEach((input) => {
            this.inputsData.fields.forEach((fieldData) => {
                if (input.id === fieldData.id) {
                    switch (input.type) {
                        case 'radio': {
                            if (fieldData.error) {
                                this.handleErrorField(input, fieldData.error, 'radio');
                            } else if (fieldData.isPrefilled) {
                                input.dataset.prefilled = '';
                            }
                            input.checked = fieldData.checked;
                            break;
                        }
                        case 'select-one': {
                            if (fieldData.error) {
                                this.handleErrorField(input, fieldData.error);
                            } else if (fieldData.isPrefilled && !input.closest(`.${this.permissionFormClass}`)) {
                                input.dataset.prefilled = '';
                            }
                            input.value = fieldData.value;
                            break;
                        }
                        case 'file': {
                            const fileInputWrapper = input.closest(`.${this.fileInputClass}`);
                            const pseudoInput = fileInputWrapper.querySelector('.b-input-file__text');

                            if (fieldData.error) {
                                this.handleErrorField(input, fieldData.error);
                            }

                            pseudoInput.textContent = fieldData.value;
                            if (fieldData.value) {
                                Utils.hide(fileInputWrapper.querySelector('.b-input-file__add'));
                                Utils.show(fileInputWrapper.querySelector('.b-input-file__delete'));
                            }
                            break;
                        }
                        default: {
                            if (fieldData.error) {
                                this.handleErrorField(input, fieldData.error);
                            } else if (fieldData.isPrefilled) {
                                input.closest('.b-input-block').classList.add(this.untouchedIputClass);
                                input.dataset.prefilled = '';
                            }
                            input.value = fieldData.value;
                            break;
                        }
                    }
                }
            });
            // Выставление статуса инпутам
            // Скипаем задисейбленные поля
            if (!input.closest(`.${this.disabledInputClass}`)) {
                this.inputsStatus[input.id] = this.getInputStatus(input);
            }
        });
        this.setBlockStatus();
    }
    /* eslint-enable max-lines-per-function, max-statements */

    handleErrorField(input, errorMessage, type) {
        this.target.classList.add(this.errorClass);
        this.target.classList.remove(this.approveClass);
        this.target.dataset.hasError = '';
        delete this.target.dataset.approved;
        input.dataset.hasError = '';

        let inputBlock = null;
        let errorBlock = null;

        if (type === 'radio') {
            inputBlock = input.closest('.b-radio-row');

            inputBlock.insertAdjacentHTML('afterend', templateError({
                errorMessage
            }));

            errorBlock = inputBlock.nextElementSibling;
        } else {
            inputBlock = input.closest('.b-input-block');
            Utils.insetContent(inputBlock, templateError({
                errorMessage
            }));

            errorBlock = inputBlock.querySelector('.b-input-error');
        }

        errorBlock.querySelector('.b-input-error__confirm').addEventListener('click', () => {
            const dataToSend = `action=confirmField&${$(input).serialize()}`;
            const that = this;

            Utils.send(dataToSend, '/tests/reports/input-update.json', {
                success(response) {
                    if (response.request.status === that.SUCCESS_STATUS) {
                        delete input.dataset.hasError;
                        Utils.removeElement(errorBlock);

                        that.inputsStatus[input.id] = that.getInputStatus(input);
                        that.setBlockStatus();
                    }
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    getInputStatus(input) {
        switch (input.type) {
            case 'radio': {
                const checkboxGroup = input.closest('.b-radio-row').querySelectorAll('input[type="radio"]');

                if (Utils.keyExist(input.dataset, 'hasError')) {
                    return 'hasError';
                } else if (Utils.keyExist(input.dataset, 'prefilled')) {
                    return 'prefilled';
                }
                for (let i = 0; i < checkboxGroup.length; i++) {
                    if (checkboxGroup[i].checked) {
                        return 'filled';
                    }
                }

                break;
            }
            default: {
                if (Utils.keyExist(input.dataset, 'hasError')) {
                    return 'hasError';
                } else if (Utils.keyExist(input.dataset, 'prefilled')) {
                    return 'prefilled';
                } else if (input.value) {
                    return 'filled';
                }
                break;
            }
        }

        // Дополнительные поля в блоках "Стадия строительства" не обяательны для заполнения
        // поэтому если в них нет ошибок они считаются заполненными
        return input.closest(`.${this.permissionFormClass}`) ? 'filled' : 'empty';
    }

    /* eslint-disable max-lines-per-function, max-statements */
    setBlockStatus() {
        let hasEmpty = false;

        for (const key in this.inputsStatus) {
            if (Utils.keyExist(this.inputsStatus, key)) {
                switch (this.inputsStatus[key]) {
                    case 'hasError': {
                        delete this.target.dataset.approved;
                        delete this.target.dataset.prefilled;
                        this.target.dataset.hasError = '';

                        this.target.classList.remove(this.approveClass);
                        this.target.classList.add(this.errorClass);

                        mediator.publish('blockStatusChanged', this.formID);

                        return;
                    }
                    case 'prefilled': {
                        delete this.target.dataset.approved;
                        delete this.target.dataset.hasError;
                        this.target.dataset.prefilled = '';

                        this.target.classList.remove(this.approveClass);
                        this.target.classList.remove(this.errorClass);

                        mediator.publish('blockStatusChanged', this.formID);

                        return;
                    }
                    case 'filled': {
                        if (hasEmpty) {
                            break;
                        }
                        delete this.target.dataset.hasError;
                        delete this.target.dataset.prefilled;
                        this.target.dataset.approved = '';

                        this.target.classList.remove(this.errorClass);
                        this.target.classList.add(this.approveClass);

                        break;
                    }
                    case 'empty': {
                        if (hasEmpty) {
                            break;
                        }
                        delete this.target.dataset.prefilled;
                        delete this.target.dataset.hasError;
                        delete this.target.dataset.approved;

                        this.target.classList.remove(this.approveClass);
                        this.target.classList.remove(this.errorClass);

                        hasEmpty = true;
                        break;
                    }
                    default: break;
                }
            }
        }

        mediator.publish('blockStatusChanged', this.formID);
    }
    /* eslint-enable max-lines-per-function, max-statements */

    sendNewValue(input) {
        const dataToSend = `a=update&id=${this.reportId}&field=${input.name}&val=${input.value}`;
        const that = this;

        Utils.send(dataToSend, '/api/report/', {
            success(response) {
                if (response.request.status !== that.SUCCESS_STATUS) {
                    return;
                }

                if (input.type === 'radio') {
                    const checkboxGroup = input.closest('.b-radio-row').querySelectorAll('input[type="radio"]');

                    Array.from(checkboxGroup).forEach((checkbox) => {
                        that.inputsStatus[checkbox.id] = that.getInputStatus(checkbox);
                    });
                } else {
                    that.inputsStatus[input.id] = that.getInputStatus(input);
                }

                that.setBlockStatus();
            },
            error(error) {
                console.error(error);
            }
        });
    }

    // ///
    // Методы для блока добавления стадий строительства
    // ///

    /**
     * Подстановка формы для блока со стадиями строительства
     * @param {String} stageID - ID стадии строительства
     * @param {Boolean} hasExtraForm - флаг необходимости подстановки формы с дополнительными полями
     * @param {Boolean} deletable - флаг указывающий необходимомть отображения кнопки удаления стадии
     */
    insertStageForm(stageID, hasExtraForm, deletable = false) {
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);

        Utils.insetContent(stageBlock, templateStageForm({
            stageID,
            deletable
        }));
        if (hasExtraForm) {
            const stageSelect = this.target.querySelector(`.${this.stageSelectClass}[data-stage-id="${stageID}"]`);

            stageSelect.insertAdjacentHTML('afterend', templatePermissionForm({id: stageID}));
        }

        // Биндим событя на кнопку удаления
        if (deletable) {
            this.bindRemoveStage(stageID);
        }
    }

    bindRemoveStage(stageID) {
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);
        const deleteButtonSelector = `.j-delete-stage[data-stage-id="${stageID}"]`;
        const deleteButton = stageBlock.querySelector(deleteButtonSelector);

        deleteButton.addEventListener('click', (event) => {
            this.removeStage(event.target);
        });
    }

    removeStage(input) {
        const that = this;
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);
        const elementsToDelete = Array.from(this.target.querySelectorAll(`[data-stage-id="${input.dataset.stageId}"]`));

        Utils.send(`a=delGroup&id=${this.reportId}&typeId=${input.dataset.stageId}`, this.baseUrl, {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }

                elementsToDelete.forEach((element) => {
                    Utils.removeElement(element);
                });

                // Сбрасываем статус блока после удаления стадии
                delete that.inputsStatus[`construction-stage[${input.dataset.stageId}]`];
                that.setBlockStatus();

                // Если осталась только одна стадия - её нельзя удалять
                const onlyOne = 1;

                if (stageBlock.querySelectorAll(`.${that.stageDeleteButtonClass}`).length === onlyOne) {
                    Utils.removeElement(stageBlock.querySelector(`.${that.stageDeleteButtonClass}`));
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    stageSelectHandler(event) {
        if (Utils.keyExist(event.target.dataset, 'prefilled')) {
            delete event.target.dataset.prefilled;
        }

        const selectWrapper = event.target.closest(`.${this.stageSelectClass}`);
        const stageID = selectWrapper.dataset.stageId;
        const needExtraForm = event.target.value === 'stage4' || event.target.value === 'stage6';
        const permissionForm = this.target.querySelector(`.${this.permissionFormClass}[data-stage-id="${stageID}"]`);

        if (needExtraForm && !permissionForm) {
            selectWrapper.insertAdjacentHTML('afterend', templatePermissionForm({id: stageID}));
            const extraFormInputs = Array.from(selectWrapper.nextSibling.querySelectorAll('input, select'));

            this._bindInputsEvents(extraFormInputs);
        } else if (!needExtraForm && permissionForm) {
            Utils.removeElement(permissionForm);
        }

        event.target.closest('.b-input-block').classList.remove(this.untouchedIputClass);
        this.sendNewValue(event.target);
    }

    addStage() {
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);
        const stageAddButton = this.target.querySelector(`.${this.stageAddButtonClass}`);
        const that = this;

        stageAddButton.addEventListener('click', () => {
            Utils.send(`a=addGroup&id=${this.reportId}&type=stages&form=${this.formID}`, this.baseUrl, {
                success(response) {
                    if (response.request.status === that.FAIL_STATUS) {
                        return;
                    }
                    const stageID = response.data.ID;

                    that.insertStageForm(stageID, false, true);
                    const newStageSelect = stageBlock.querySelector(`[data-stage-id="${stageID}"] select`);

                    that.initSelect(newStageSelect);

                    // Сбрасываем статус блока после добавления новой стадии
                    that.inputsStatus[newStageSelect.id] = that.getInputStatus(newStageSelect);
                    that.setBlockStatus();
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    // ///
    // Методы для блока добавления стран экспорта
    // ///

    /**
     * Подстановка формы для блока со странами экспорта
     * @param {String} groupID - ID группы стран
     * @param {Boolean} deletable - флаг указвающий необходимомть отображения кнопки удаления формы
     */
    insertExportGroupForm(groupID, deletable = false) {
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);

        Utils.insetContent(groupsBlock, templateExportForm({
            ID: groupID,
            deletable
        }));

        // Биндим событя на кнопку удаления
        if (deletable) {
            this.bindRemoveExportGroup(groupID);
        }
    }

    addExportGroup() {
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);
        const groupAddButton = this.target.querySelector(`.j-export-group-add`);
        const that = this;

        groupAddButton.addEventListener('click', () => {
            Utils.send(`a=addGroup&id=${this.reportId}&type=groups&form=${this.formID}`, this.baseUrl, {
                success(response) {
                    if (response.request.status === that.FAIL_STATUS) {
                        return;
                    }
                    const groupID = response.data.ID;

                    that.insertExportGroupForm(groupID, true);
                    // Сбрасываем статус блока после добавления новой группы
                    const inputs = groupsBlock.querySelectorAll(`[data-id="${groupID}"] input`);

                    Array.from(inputs).forEach((input) => {
                        that.bindTextInputsEvents(input);
                        that.inputsStatus[input.id] = that.getInputStatus(input);
                    });
                    that.setBlockStatus();
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    bindRemoveExportGroup(groupID) {
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);
        const deleteButtonSelector = `.j-delete-group[data-id="${groupID}"]`;
        const deleteButton = groupsBlock.querySelector(deleteButtonSelector);

        deleteButton.addEventListener('click', (event) => {
            this.removeExportGroup(event.target);
        });
    }

    removeExportGroup(input) {
        const that = this;
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);
        const elementsToDelete = Array.from(this.target.querySelectorAll(`[data-id="${input.dataset.id}"]`));

        Utils.send(`a=delGroup&id=${this.reportId}&typeId=${input.dataset.id}`, this.baseUrl, {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }

                elementsToDelete.forEach((element) => {
                    Utils.removeElement(element);
                });

                // Сбрасываем статус блока после удаления стадии
                delete that.inputsStatus[`export-countries[${input.dataset.id}]`];
                delete that.inputsStatus[`export-code[${input.dataset.id}]`];
                that.setBlockStatus();

                // Если осталась только одна группа - её нельзя удалять
                const onlyOne = 1;

                if (groupsBlock.querySelectorAll(`.j-delete-group`).length === onlyOne) {
                    Utils.removeElement(groupsBlock.querySelector(`.j-delete-group`));
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    // ///
    // Методы для блока добавлнеия технологических инноваций
    // ///

    /**
     * Подстановка формы для блока со странами экспорта
     * @param {String} innovationID - ID инновации
     * @param {Boolean} deletable - флаг указвающий необходимомть отображения кнопки удаления формы
     */
    insertInnovationForm(innovationID, deletable = false) {
        const innovationsBlock = this.target.querySelector(`.j-innovations-block`);

        Utils.insetContent(innovationsBlock, templateInnovationForm({
            ID: innovationID,
            deletable
        }));

        // Биндим событя на кнопку удаления
        if (deletable) {
            this.bindRemoveInnovation(innovationID);
        }
    }

    addInnovation() {
        const innovationsBlock = this.target.querySelector(`.j-innovations-block`);
        const innovationAddButton = this.target.querySelector(`.j-innovation-add`);
        const that = this;

        innovationAddButton.addEventListener('click', () => {
            Utils.send(`a=addGroup&id=${this.reportId}&type=innovations&form=${this.formID}`, this.baseUrl, {
                success(response) {
                    if (response.request.status === that.FAIL_STATUS) {
                        return;
                    }
                    const innovationID = response.data.ID;

                    that.insertInnovationForm(innovationID, true);
                    // Сбрасываем статус блока после добавления инновации
                    const inputs = innovationsBlock.querySelectorAll(`[data-id="${innovationID}"] input`);

                    Array.from(inputs).forEach((input) => {
                        that.bindTextInputsEvents(input);
                        that.inputsStatus[input.id] = that.getInputStatus(input);
                    });
                    that.setBlockStatus();
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    bindRemoveInnovation(innovationID) {
        const innovationsBlock = this.target.querySelector(`.j-innovations-block`);
        const deleteButtonSelector = `.j-delete-innovation[data-id="${innovationID}"]`;
        const deleteButton = innovationsBlock.querySelector(deleteButtonSelector);

        deleteButton.addEventListener('click', (event) => {
            this.removeInnovation(event.target);
        });
    }

    removeInnovation(input) {
        const that = this;
        const innovationsBlock = this.target.querySelector(`.j-innovations-block`);
        const elementsToDelete = Array.from(this.target.querySelectorAll(`[data-id="${input.dataset.id}"]`));

        Utils.send(`a=delGroup&id=${this.reportId}&typeId=${input.dataset.id}`, this.baseUrl, {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }

                elementsToDelete.forEach((element) => {
                    Utils.removeElement(element);
                });

                // Сбрасываем статус блока после удаления стадии
                delete that.inputsStatus[`innovation[${input.dataset.id}]`];
                that.setBlockStatus();

                // Если осталась только одна группа - её нельзя удалять
                const onlyOne = 1;

                if (innovationsBlock.querySelectorAll(`.j-delete-innovation`).length === onlyOne) {
                    Utils.removeElement(innovationsBlock.querySelector(`.j-delete-innovation`));
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }
}

export default ReportBlock;
